// ==========================================
// 1. تهيئة قاعدة البيانات (نفس النسخة 11)
// ==========================================
if (typeof window.db === 'undefined') {
    window.db = new Dexie("MasarOfflineDB");
    db.version(11).stores({
        questions: "id, category",
        traffic_signs: "id, category, code", 
        exam_results: "++id, student_id, score, status, date, synced",
        wrong_questions: "q_id, count"
    });
}

// ==========================================
// 2. إدارة الأخطاء والتقدم (المزامنة الفورية)
// ==========================================

// دالة موحدة لتسجيل الخطأ (محلي + سحابي)
async function recordWrongAnswer(questionId) {
    try {
        const existing = await db.wrong_questions.get(questionId);
        const newCount = existing ? existing.count + 1 : 1;
        
        // تحديث محلي سريع
        await db.wrong_questions.put({ q_id: questionId, count: newCount });

        // رفعه للسيرفر بصمت (Background)
        if (navigator.onLine) {
            fetch('/student/sync-error', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                },
                body: JSON.stringify({ question_id: questionId, count: newCount })
            }).catch(() => console.warn("Error sync queued locally."));
        }
    } catch (e) { console.error("recordWrongAnswer Error:", e); }
}
window.recordWrongAnswer = recordWrongAnswer;

// دالة موحدة لتسجيل المشاهدة (محلي + سحابي)
async function markViewedAndSync(questionId) {
    try {
        await db.questions.update(questionId, { is_viewed: 1 });

        if (navigator.onLine) {
            fetch('/student/mark-viewed', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                },
                body: JSON.stringify({ question_id: questionId })
            }).catch(() => {});
        }
    } catch (e) { console.error("markViewed Error:", e); }
}

// ==========================================
// 3. محرك المزامنة (التحسين للجملة - Bulk)
// ==========================================

async function syncQuestions() {
    try {
        const response = await fetch('/api/student/sync-questions');
        if (!response.ok) return;
        const remoteData = await response.json();

        // جلب الحالة المحلية الحالية لضمان عدم ضياع is_viewed
        const localQuestions = await db.questions.toArray();
        const localMap = new Map(localQuestions.map(q => [q.id, q.is_viewed]));

        const processedData = remoteData.map(q => ({
            ...q,
            image: q.image ? q.image.replace(/\\/g, '/') : null,
            is_viewed: localMap.get(q.id) || 0 // الحفاظ على التقدم المحلي
        }));

        // استخدام bulkPut لسرعة خرافية (عملية واحدة بدلاً من مئات)
        await db.questions.bulkPut(processedData);

        // تنظيف الأسئلة المحذوفة من السيرفر
        const remoteIds = remoteData.map(q => q.id);
        await db.questions.filter(q => !remoteIds.includes(q.id)).delete();
        
        console.log("✅ Questions Synced via BulkPut");
    } catch (e) { console.error("syncQuestions Error:", e); }
}

async function syncTrafficSigns() {
    try {
        const response = await fetch('/api/student/sync-traffic-signs');
        if (response.ok) {
            const signsData = await response.json();
            await db.traffic_signs.clear();
            await db.traffic_signs.bulkAdd(signsData);
        }
    } catch (e) { console.warn("Traffic signs sync failed (offline)"); }
}

// ==========================================
// 4. المزامنة الشاملة (من وإلى السحابة)
// ==========================================

async function syncAllFromCloud() {
    if (!navigator.onLine) return;
    try {
        const response = await fetch('/student/get-sync-data');
        if (!response.ok) return;
        const cloudData = await response.json();

        // 1. مزامنة بنك الأخطاء
        if (cloudData.errors?.length > 0) {
            const errorBatch = cloudData.errors.map(err => ({ q_id: parseInt(err.q_id), count: err.count }));
            await db.wrong_questions.bulkPut(errorBatch);
        }

        // 2. مزامنة التقدم (is_viewed) بالجملة
        if (cloudData.viewed_ids?.length > 0) {
            await db.questions.where('id').anyOf(cloudData.viewed_ids).modify({ is_viewed: 1 });
        }

        // 3. مزامنة سجل النتائج (بدون تكرار)
        if (cloudData.exam_results?.length > 0) {
            for (let res of cloudData.exam_results) {
                const exists = await db.exam_results.where('date').equals(res.date).and(x => x.score == res.score).first();
                if (!exists) {
                    await db.exam_results.add({ ...res, synced: 1 });
                }
            }
        }
        console.log("✅ Global Sync Complete");
    } catch (e) { console.error("Global Sync Error:", e); }
}

// رفع نتائج الامتحانات التي تمت أوفلاين
async function uploadPendingResults() {
    const pending = await db.exam_results.where('synced').equals(0).toArray();
    for (let res of pending) {
        try {
            const resp = await fetch('/api/student/save-exam', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
                body: JSON.stringify(res)
            });
            if (resp.ok) await db.exam_results.update(res.id, { synced: 1 });
        } catch (e) { break; } // توقف لو انقطع النت
    }
}

// ==========================================
// 5. التشغيل الذكي عند التحميل
// ==========================================

async function initializeAppSync() {
    const loader = document.getElementById('sync-loader');
    if (loader) loader.style.display = 'block';

    // 1. ارفع النتائج المعلقة أولاً
    await uploadPendingResults();

    // 2. إذا كنا في الداشبورد أو السجل، اسحب البيانات الجديدة
    const path = window.location.pathname;
    if (path.includes('dashboard') || path.includes('history') || path.includes('exam-result')) {
        await syncAllFromCloud();
    }

    // 3. حدث الأسئلة والإشارات (مرة واحدة في الجلسة أو عند الحاجة)
    if (path.includes('dashboard')) {
        await syncQuestions();
        await syncTrafficSigns();
    }

    if (loader) loader.style.display = 'none';
    if (typeof updateDashboardUI === 'function') updateDashboardUI();
}

document.addEventListener('DOMContentLoaded', initializeAppSync);