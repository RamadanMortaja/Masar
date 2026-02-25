@extends('layouts.student')

@section('content')

<div id="exam-wrap" style="background:var(--bg);min-height:calc(100vh - 80px);padding-bottom:170px;">

    {{-- شريط الامتحان الثابت --}}
    <div id="exam-topbar" style="position:sticky;top:0;z-index:100;background:var(--surface);border-bottom:1px solid var(--border);box-shadow:0 2px 12px rgba(79,110,247,.07);padding:12px 16px 8px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">

            {{-- المؤقت --}}
            <div id="timer-wrap" style="display:flex;align-items:center;gap:6px;background:var(--danger-lt);padding:7px 12px;border-radius:10px;border:1px solid rgba(239,68,68,.15);">
                <i class="fas fa-clock" style="color:var(--danger);font-size:13px;"></i>
                <span id="exam-timer" style="font-size:16px;font-weight:800;color:var(--danger);font-variant-numeric:tabular-nums;">40:00</span>
            </div>

            {{-- رقم السؤال --}}
            <div id="exam-counter" style="background:var(--accent-lt);color:var(--accent);padding:7px 14px;border-radius:10px;font-size:13px;font-weight:700;border:1px solid rgba(79,110,247,.15);">
                السؤال 1 / 30
            </div>

            {{-- زر الإنهاء --}}
            <button onclick="finishExam()" id="finish-btn-top"
                    style="background:linear-gradient(135deg,var(--success),#6ee7b7);color:#fff;border:none;border-radius:10px;padding:7px 14px;font-family:'Cairo',sans-serif;font-size:13px;font-weight:700;cursor:pointer;transition:var(--tr);">
                إنهاء
            </button>
        </div>

        {{-- شريط التقدم --}}
        <div class="s-progress-wrap" style="height:6px;">
            <div id="exam-progress" class="s-progress-fill"
                 style="width:0%;background:linear-gradient(90deg,var(--accent),#818cf8);"></div>
        </div>
    </div>

    {{-- محتوى السؤال --}}
    <div style="padding:16px;max-width:560px;margin:0 auto;">
        <div id="question-card-wrapper"></div>
    </div>

    {{-- شريط التنقل السفلي للامتحان --}}
    <div style="position:fixed;bottom:0;right:0;left:0;z-index:90;background:var(--surface);border-top:1px solid var(--border);box-shadow:0 -4px 20px rgba(79,110,247,.08);padding:10px 14px 16px;">
        {{-- شبكة الأسئلة --}}
        <div id="questions-grid" style="display:flex;flex-wrap:wrap;gap:6px;justify-content:center;margin-bottom:10px;"></div>
        {{-- أزرار التنقل --}}
        <div style="display:flex;justify-content:space-between;gap:10px;">
            <button onclick="changeQuestion(currentIndex - 1)" id="prev-btn"
                    style="flex:1;padding:11px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);font-family:'Cairo',sans-serif;font-size:13px;font-weight:700;color:var(--text-2);cursor:pointer;transition:var(--tr);">
                <i class="fas fa-chevron-right fa-sm"></i> السابق
            </button>
            <button onclick="handleNextButton()" id="next-btn"
                    style="flex:1;padding:11px;background:linear-gradient(135deg,var(--accent),#818cf8);border:none;border-radius:var(--r-sm);font-family:'Cairo',sans-serif;font-size:13px;font-weight:700;color:#fff;cursor:pointer;transition:var(--tr);box-shadow:0 4px 12px var(--accent-glow);">
                التالي <i class="fas fa-chevron-left fa-sm"></i>
            </button>
        </div>
    </div>

</div>

<style>
    /* بطاقة السؤال */
    .q-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r);
        box-shadow: var(--sh);
        overflow: hidden;
        animation: slideIn .3s ease;
    }
    @keyframes slideIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:none} }

    .q-img-area {
        background: var(--surface2);
        padding: 16px;
        text-align: center;
        border-bottom: 1px solid var(--border);
    }

    .q-body { padding: 20px 16px; }

    .q-text {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-1);
        line-height: 1.7;
        margin-bottom: 18px;
        text-align: right;
    }

    /* خيارات الإجابة */
    .option-btn {
        width: 100%;
        padding: 14px 16px;
        background: var(--surface2);
        border: 2px solid var(--border);
        border-radius: var(--r-sm);
        text-align: right;
        font-family: 'Cairo', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-1);
        cursor: pointer;
        transition: var(--tr);
        display: block;
        margin-bottom: 8px;
    }
    .option-btn:hover { background: var(--accent-lt); border-color: rgba(79,110,247,.3); transform: translateX(-2px); }
    .option-btn.selected { background: var(--accent-lt); border-color: var(--accent); color: var(--accent); }
    .option-btn.disabled { pointer-events: none; }
    .option-btn.correct { background: var(--success-lt) !important; border-color: var(--success) !important; color: var(--success) !important; }
    .option-btn.wrong   { background: var(--danger-lt) !important;  border-color: var(--danger) !important;  color: var(--danger) !important; }

    /* شبكة الأسئلة */
    .grid-item {
        width: 30px; height: 30px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 700;
        cursor: pointer;
        border: 1px solid var(--border);
        background: var(--surface2);
        color: var(--text-3);
        transition: var(--tr);
    }
    .grid-item.current  { border: 2px solid var(--accent); color: var(--accent); background: var(--accent-lt); }
    .grid-item.answered { background: var(--accent); color: #fff; border-color: var(--accent); }
</style>
@endsection

@section('scripts')
<script>
    // ============================================================
    // لوجيك الامتحان — نفس الأصل بالكامل
    // ============================================================
    const db = new Dexie("MasarOfflineDB");
    db.version(11).stores({
        questions: "id, category",
        traffic_signs: "id, category, code",
        exam_results: "++id, student_id, score, status, date, synced",
        wrong_questions: "q_id, count"
    });

    let examQuestions = [];
    let currentIndex  = 0;
    let studentAnswers = {};
    let timeLeft      = 40 * 60;
    let timerInterval;

    // خوارزمية اختيار الأسئلة (20 قوانين، 7 ميكانيكا، 3 إشارات)
    async function initExam() {
        const all  = await db.questions.toArray();
        const laws  = all.filter(q => q.category.includes('قوانين')).sort(() => 0.5 - Math.random()).slice(0, 20);
        const mech  = all.filter(q => q.category.includes('ميكانيكا')).sort(() => 0.5 - Math.random()).slice(0, 7);
        const signs = all.filter(q => q.category.includes('إشارات')).sort(() => 0.5 - Math.random()).slice(0, 3);

        examQuestions = [...laws, ...mech, ...signs];

        if (examQuestions.length < 30) {
            const usedIds = examQuestions.map(q => q.id);
            const extra = all.filter(q => !usedIds.includes(q.id)).sort(() => 0.5 - Math.random()).slice(0, 30 - examQuestions.length);
            examQuestions = [...examQuestions, ...extra];
        }

        if (examQuestions.length < 30) {
            alert("لا يوجد أسئلة كافية. يرجى المزامنة أولاً.");
            window.location.href = "{{ route('student.dashboard') }}";
            return;
        }

        examQuestions = examQuestions.sort(() => 0.5 - Math.random());
        buildGrid();
        renderQuestion();
        startTimer();
        window.onbeforeunload = () => "هل أنت متأكد من مغادرة الامتحان؟";
    }

    function renderQuestion() {
        const q       = examQuestions[currentIndex];
        const wrapper = document.getElementById('question-card-wrapper');

        // معالجة الصور
        let imagesHTML = '';
        let imagesArr  = Array.isArray(q.image) ? q.image : (q.image ? q.image.toString().split(';') : []);
        if (imagesArr.length > 0) {
            const imgs = imagesArr.map(imgData => {
                if (!imgData) return '';
                let src = imgData.toString().startsWith('http') ? imgData : `/images/signs/all/${imgData.trim()}`;
                if (!src.match(/\.(jpg|jpeg|png|svg)$/i)) src += '.png';
                return `<img src="${src}" style="max-height:110px;max-width:90%;object-fit:contain;border-radius:10px;" onerror="this.src=this.src.replace('.png','.svg')">`;
            }).join('');
            imagesHTML = `<div class="q-img-area">${imgs}</div>`;
        }

        // الخيارات
        let optionsHTML = '';
        q.options.forEach((opt, i) => {
            if (!opt || opt.trim() === "") return;
            const isSelected = studentAnswers[currentIndex] === (i + 1);
            optionsHTML += `<button class="option-btn ${isSelected ? 'selected' : ''}" onclick="selectOption(${i + 1})">${opt}</button>`;
        });

        wrapper.innerHTML = `
            <div class="q-card">
                ${imagesHTML}
                <div class="q-body">
                    <div class="q-text">${q.question_text}</div>
                    <div>${optionsHTML}</div>
                </div>
            </div>`;

        updateUI();
    }

    function selectOption(optionNum) {
        studentAnswers[currentIndex] = optionNum;
        renderQuestion();
        buildGrid();
    }

    function buildGrid() {
        const grid = document.getElementById('questions-grid');
        grid.innerHTML = '';
        for (let i = 0; i < 30; i++) {
            const item = document.createElement('div');
            item.className = `grid-item ${currentIndex === i ? 'current' : ''} ${studentAnswers[i] ? 'answered' : ''}`;
            item.innerText = i + 1;
            item.onclick   = () => changeQuestion(i);
            grid.appendChild(item);
        }
    }

    function changeQuestion(index) {
        if (index >= 0 && index < 30) {
            currentIndex = index;
            renderQuestion();
            window.scrollTo(0, 0);
        }
    }

    function updateUI() {
        document.getElementById('exam-counter').innerText = `سؤال ${currentIndex + 1} / 30`;
        const answeredCount = Object.keys(studentAnswers).length;
        document.getElementById('exam-progress').style.width = `${(answeredCount / 30) * 100}%`;
        document.getElementById('prev-btn').style.visibility = currentIndex === 0 ? 'hidden' : 'visible';
        document.getElementById('next-btn').innerText = currentIndex === 29
            ? 'إنهاء الامتحان'
            : 'التالي ';
    }

    function handleNextButton() {
        if (currentIndex === 29) { finishExam(); }
        else { changeQuestion(currentIndex + 1); }
    }

    function startTimer() {
        timerInterval = setInterval(() => {
            timeLeft--;
            const mins = Math.floor(timeLeft / 60).toString().padStart(2, '0');
            const secs = (timeLeft % 60).toString().padStart(2, '0');
            document.getElementById('exam-timer').innerText = `${mins}:${secs}`;

            if (timeLeft <= 300) {
                document.getElementById('timer-wrap').style.animation = 'pulse 1s infinite';
            }
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                processFinish();
            }
        }, 1000);
    }

    function finishExam() {
        const answeredCount = Object.keys(studentAnswers).length;
        const msg = answeredCount < 30
            ? `أجبت على ${answeredCount} من 30 سؤالاً. هل تريد الإنهاء؟`
            : 'هل تريد إنهاء الامتحان والحصول على النتيجة؟';
        if (confirm(msg)) processFinish();
    }

    async function processFinish() {
        if (timerInterval) clearInterval(timerInterval);
        window.onbeforeunload = null;

        const btn = document.getElementById('finish-btn-top');
        if (btn) {
            btn.disabled   = true;
            btn.innerHTML  = '<span class="spinner-border spinner-border-sm"></span>';
        }

        let finalScore = 0;
        const viewedIds = [];
        const wrongIds  = [];

        for (let i = 0; i < examQuestions.length; i++) {
            const q = examQuestions[i];
            viewedIds.push(q.id);
            if (studentAnswers[i] == q.correct_option) {
                finalScore++;
                await db.wrong_questions.delete(q.id);
            } else {
                wrongIds.push(q.id);
                const existing = await db.wrong_questions.get(q.id);
                await db.wrong_questions.put({ q_id: q.id, count: (existing ? existing.count + 1 : 1) });
            }
        }

        const status     = finalScore >= 25 ? 'ناجح' : 'راسب';
        const resultData = {
            student_id: "{{ session('student_id') }}",
            score: finalScore,
            status: status,
            date: new Date().toISOString(),
            synced: 0
        };

        const localId = await db.exam_results.add(resultData);

        try {
            const response = await fetch("/student/sync-batch", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ viewed_ids: viewedIds, wrong_ids: wrongIds, result: resultData })
            });
            if (response.ok) {
                await db.exam_results.update(localId, { synced: 1 });
            }
        } catch (e) {
            console.error("Sync failed:", e);
        }

        window.location.href = `{{ route('student.exam_result') }}?score=${finalScore}&status=${status}`;
    }

    window.onload = initExam;
</script>
@endsection
