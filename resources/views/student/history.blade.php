@extends('layouts.student')

@section('content')

{{-- هيدر الصفحة --}}
<div class="page-header">
    <div style="display:flex;align-items:center;justify-content:space-between;position:relative;z-index:2;">
        <a href="{{ route('student.dashboard') }}"
           style="width:34px;height:34px;background:rgba(255,255,255,.15);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;">
            <i class="fas fa-arrow-right fa-sm"></i>
        </a>
        <div style="text-align:center;">
            <div style="font-size:17px;font-weight:800;color:#fff;">سجل الامتحانات</div>
            <div style="font-size:11px;color:rgba(255,255,255,.65);">جميع امتحاناتك السابقة</div>
        </div>
        <div style="width:34px;"></div>
    </div>
</div>

<div style="padding:18px 16px 0;max-width:520px;margin:0 auto;">

    {{-- ملخص سريع --}}
    <div class="s-card fade-up" style="display:grid;grid-template-columns:1fr 1fr 1fr;margin-bottom:16px;">
        <div style="padding:14px 8px;text-align:center;border-left:1px solid var(--border);">
            <div style="font-size:20px;font-weight:800;color:var(--accent);" id="hist-total">-</div>
            <div class="xs" style="color:var(--text-3);">إجمالي</div>
        </div>
        <div style="padding:14px 8px;text-align:center;border-left:1px solid var(--border);">
            <div style="font-size:20px;font-weight:800;color:var(--success);" id="hist-success">-</div>
            <div class="xs" style="color:var(--text-3);">نجاح</div>
        </div>
        <div style="padding:14px 8px;text-align:center;">
            <div style="font-size:20px;font-weight:800;color:var(--warn);" id="hist-best">-</div>
            <div class="xs" style="color:var(--text-3);">أعلى درجة</div>
        </div>
    </div>

    {{-- قائمة النتائج --}}
    <div id="history-list"></div>

</div>

<style>
    .result-row {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        flex-direction: row-reverse;
        gap: 12px;
        margin-bottom: 10px;
        box-shadow: var(--sh);
        transition: var(--tr);
        animation: fu .4s ease both;
    }
    .result-row:hover { box-shadow: var(--sh-md); }
    .result-icon { width:44px; height:44px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
    .result-icon.s  { background:var(--success-lt); color:var(--success); }
    .result-icon.f  { background:var(--danger-lt);  color:var(--danger); }
    .result-status  { font-size:13px; font-weight:700; color:var(--text-1); margin-bottom:3px; }
    .result-date    { font-size:11px; color:var(--text-3); }
    .result-score   { font-size:22px; font-weight:900; }
    .result-score.s { color:var(--success); }
    .result-score.f { color:var(--danger); }
    .sync-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
    @keyframes fu { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:none} }

    .empty-state {
        text-align:center;
        padding:48px 20px;
        color:var(--text-3);
    }
    .empty-state .empty-icon { font-size:48px; opacity:.3; margin-bottom:12px; }
    .empty-state p { font-size:13px; }
</style>
@endsection

@section('scripts')
<script>
const db = new Dexie("MasarOfflineDB");
db.version(11).stores({
    questions: "id, category",
    traffic_signs: "id, category, code",
    exam_results: "++id, student_id, score, status, date, synced",
    wrong_questions: "q_id, count"
});

async function loadHistory() {
    try {
        if (!db.isOpen()) await db.open();

        // مزامنة من السيرفر أولاً
        await syncResultsFromServer();

        const results = await db.exam_results.toArray();
        const list = document.getElementById('history-list');

        if (results.length === 0) {
            list.innerHTML = `
                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <p>لا توجد امتحانات مسجلة بعد<br>ابدأ امتحانك الأول الآن!</p>
                    <a href="{{ route('student.exam') }}"
                       style="display:inline-flex;align-items:center;gap:6px;margin-top:14px;padding:10px 20px;background:var(--accent-lt);color:var(--accent);border:1px solid rgba(79,110,247,.2);border-radius:10px;font-size:13px;font-weight:700;text-decoration:none;">
                        <i class="fas fa-play fa-sm"></i> ابدأ امتحان
                    </a>
                </div>`;
            return;
        }

        // إزالة التكرار
        const uniqueMap = new Map();
        results.forEach(res => {
            const key = `${res.score}-${res.date.substring(0, 16)}`;
            if (!uniqueMap.has(key)) {
                uniqueMap.set(key, res);
            } else {
                const ex = uniqueMap.get(key);
                if (res.synced && !ex.synced) uniqueMap.set(key, res);
            }
        });

        const finalResults = Array.from(uniqueMap.values())
            .sort((a, b) => new Date(b.date) - new Date(a.date));

        // إحصائيات
        const total   = finalResults.length;
        const success = finalResults.filter(r => r.status === 'ناجح').length;
        const best    = Math.max(...finalResults.map(r => r.score), 0);

        document.getElementById('hist-total').innerText   = total;
        document.getElementById('hist-success').innerText = success;
        document.getElementById('hist-best').innerText    = best;

        // بناء القائمة
        let html = '';
        finalResults.forEach((res, idx) => {
            const isSuccess = res.status === 'ناجح';
            const dateStr = new Date(res.date).toLocaleString('ar-EG', {
                year:'numeric', month:'long', day:'numeric',
                hour:'2-digit', minute:'2-digit'
            });
            html += `
                <div class="result-row" style="animation-delay:${idx * 0.05}s;">
                    <div class="result-icon ${isSuccess ? 's' : 'f'}">
                        <i class="fas ${isSuccess ? 'fa-check' : 'fa-times'}"></i>
                    </div>
                    <div style="flex:1;">
                        <div class="result-status">${res.status}</div>
                        <div class="result-date"><i class="far fa-calendar-alt" style="margin-left:4px;"></i>${dateStr}</div>
                    </div>
                    <div class="result-score ${isSuccess ? 's' : 'f'}">${res.score}<span style="font-size:12px;opacity:.5;">/30</span></div>
                    <div class="sync-dot" style="background:${res.synced ? 'var(--success)' : 'var(--warn)'};"
                         title="${res.synced ? 'مزامن مع السيرفر' : 'في انتظار المزامنة'}">
                    </div>
                </div>`;
        });
        list.innerHTML = html;

    } catch (e) {
        console.error("Error loading history:", e);
    }
}

// مزامنة النتائج من السيرفر — نفس اللوجيك الأصلي
async function syncResultsFromServer() {
    try {
        const response = await fetch('/student/get-sync-data');
        if (!response.ok) return;
        const data = await response.json();

        if (data.exam_results && data.exam_results.length > 0) {
            for (let res of data.exam_results) {
                const serverDate = res.created_at || res.exam_date || res.date;
                const exists = await db.exam_results
                    .where('score').equals(res.score)
                    .filter(item => item.date.substring(0, 16) === serverDate.substring(0, 16))
                    .first();

                if (!exists) {
                    await db.exam_results.add({
                        student_id: res.student_id,
                        score: res.score,
                        status: res.status,
                        date: serverDate,
                        synced: 1
                    });
                } else if (exists && !exists.synced) {
                    await db.exam_results.update(exists.id, { synced: 1 });
                }
            }
        }
    } catch (err) {
        console.warn("Sync failed - showing local data only");
    }
}

document.addEventListener('DOMContentLoaded', loadHistory);
</script>
@endsection
