@extends('layouts.student')

@section('content')

<div style="background:var(--bg);min-height:calc(100vh - 80px);padding-bottom:130px;">

    {{-- شريط التدريب الثابت --}}
    <div style="position:sticky;top:0;z-index:100;background:var(--surface);border-bottom:1px solid var(--border);box-shadow:0 2px 12px rgba(79,110,247,.07);padding:12px 16px 8px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
            <a href="{{ route('student.practice.modes') }}"
               style="width:34px;height:34px;background:var(--surface2);border:1px solid var(--border);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--text-2);text-decoration:none;">
                <i class="fas fa-arrow-right fa-sm"></i>
            </a>
            <div id="category-title"
                 style="font-size:14px;font-weight:700;color:var(--text-1);">جاري تحميل الأسئلة...</div>
            <div id="question-counter"
                 style="background:var(--accent-lt);color:var(--accent);padding:6px 12px;border-radius:10px;font-size:12px;font-weight:700;border:1px solid rgba(79,110,247,.15);">
                0 / 0
            </div>
        </div>
        <div class="s-progress-wrap" style="height:6px;">
            <div id="progress-bar" class="s-progress-fill"
                 style="width:0%;background:linear-gradient(90deg,var(--success),#6ee7b7);"></div>
        </div>
    </div>

    {{-- محتوى السؤال --}}
    <div style="padding:14px 14px 0;max-width:560px;margin:0 auto;">

        {{-- بطاقة السؤال --}}
        <div class="s-card" id="question-card" style="margin-bottom:12px;overflow:visible;">

            {{-- منطقة الصور --}}
            <div id="question-image-container"
                 style="display:none;background:var(--surface2);padding:16px;text-align:center;border-bottom:1px solid var(--border);">
            </div>

            {{-- السؤال والخيارات --}}
            <div style="padding:20px 16px;">
                <div id="question-text"
                     style="font-size:15px;font-weight:700;color:var(--text-1);line-height:1.7;margin-bottom:18px;text-align:right;"></div>
                <div id="options-container"></div>
            </div>
        </div>

        {{-- صندوق الشرح --}}
        <div id="explanation-box"
             style="display:none;background:var(--accent-lt);border:1px solid rgba(79,110,247,.2);border-right:4px solid var(--accent);border-radius:var(--r-sm);padding:14px 16px;animation:fu .4s ease;">
            <div style="font-size:12px;font-weight:700;color:var(--accent);margin-bottom:6px;display:flex;align-items:center;gap:5px;">
                <i class="fas fa-lightbulb fa-sm"></i> توضيح الإجابة
            </div>
            <p id="explanation-text" style="font-size:13px;color:var(--text-2);line-height:1.7;margin:0;"></p>
        </div>

    </div>

    {{-- شريط أزرار التنقل --}}
    <div style="position:fixed;bottom:0;right:0;left:0;z-index:90;background:var(--surface);border-top:1px solid var(--border);padding:10px 14px 16px;box-shadow:0 -4px 20px rgba(79,110,247,.08);">
        <div style="display:flex;gap:10px;max-width:560px;margin:0 auto;">
            <button onclick="prevQuestion()" id="prev-btn"
                    style="flex:1;padding:12px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);font-family:'Cairo',sans-serif;font-size:13px;font-weight:700;color:var(--text-2);cursor:pointer;transition:var(--tr);visibility:hidden;">
                <i class="fas fa-chevron-right fa-sm"></i> السابق
            </button>
            <button onclick="nextQuestion()" id="next-btn"
                    style="flex:1;padding:12px;background:linear-gradient(135deg,var(--accent),#818cf8);border:none;border-radius:var(--r-sm);font-family:'Cairo',sans-serif;font-size:13px;font-weight:700;color:#fff;cursor:pointer;transition:var(--tr);box-shadow:0 4px 12px var(--accent-glow);">
                التالي <i class="fas fa-chevron-left fa-sm"></i>
            </button>
        </div>
    </div>
</div>

<style>
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
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        justify-content: flex-end;
    }
    .option-btn:hover { background:var(--accent-lt); border-color:rgba(79,110,247,.3); }
    .option-btn:active { transform: scale(.98); }
    .option-btn.correct { background:var(--success-lt)!important; border-color:var(--success)!important; color:var(--success)!important; }
    .option-btn.wrong   { background:var(--danger-lt)!important;  border-color:var(--danger)!important;  color:var(--danger)!important; }
    .option-btn.disabled { pointer-events:none; }
    @keyframes fu { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:none} }
</style>
@endsection

@section('scripts')
<script>
    // ============================================================
    // لوجيك التدريب — نفس الأصل بالكامل
    // ============================================================
    const db = new Dexie("MasarOfflineDB");
    db.version(11).stores({
        questions: "id, category, is_viewed",
        traffic_signs: "id, category, code",
        exam_results: "++id, student_id, score, status, date, synced",
        wrong_questions: "q_id, count"
    });

    let currentQuestions = [];
    let currentIndex     = 0;
    let answeredQuestions = {};

    async function initPractice() {
        const params   = new URLSearchParams(window.location.search);
        let category   = params.get('category');
        let mode       = params.get('mode');
        let questions  = [];

        if (mode === 'errors' || category === 'errors') {
            document.getElementById('category-title').innerText = 'مراجعة الأخطاء';
            const wrongEntries = await db.wrong_questions.toArray();
            const wrongIds     = wrongEntries.map(e => Number(e.q_id));

            if (wrongIds.length === 0) {
                alert("رائع! لقد أنهيت حل جميع الأخطاء بنجاح 🎉");
                window.location.href = "{{ route('student.practice.modes') }}";
                return;
            }
            questions = await db.questions.where('id').anyOf(wrongIds).toArray();
        } else {
            document.getElementById('category-title').innerText = category === 'all' ? 'تدريب شامل' : `قسم ${category}`;
            if (category && category !== 'all') {
                questions = await db.questions
                    .where('category').equals(category)
                    .and(q => q.is_viewed !== 1)
                    .toArray();
            } else {
                questions = await db.questions.filter(q => q.is_viewed !== 1).toArray();
            }

            if (questions.length === 0) {
                const restart = confirm("لقد أنهيت جميع الأسئلة الجديدة. هل تريد البدء من جديد؟");
                if (restart) {
                    if (category && category !== 'all') {
                        await db.questions.where('category').equals(category).modify({ is_viewed: 0 });
                    } else {
                        await db.questions.toCollection().modify({ is_viewed: 0 });
                    }
                    return initPractice();
                } else {
                    window.location.href = "{{ route('student.dashboard') }}";
                    return;
                }
            }
        }

        currentQuestions = questions.sort(() => 0.5 - Math.random());
        renderQuestion();
    }

    function renderQuestion() {
        if (!currentQuestions[currentIndex]) return;
        const q    = currentQuestions[currentIndex];
        const card = document.getElementById('question-card');

        card.style.animation = 'none';
        card.offsetHeight;
        card.style.animation = 'fu .3s ease';

        document.getElementById('question-counter').innerText = `${currentIndex + 1} / ${currentQuestions.length}`;
        document.getElementById('progress-bar').style.width   = `${((currentIndex + 1) / currentQuestions.length) * 100}%`;
        document.getElementById('question-text').innerText    = q.question_text;

        // الصور
        const imgContainer = document.getElementById('question-image-container');
        imgContainer.innerHTML = '';
        let imagesArr = Array.isArray(q.images) ? q.images : (q.image ? q.image.toString().split(';') : []);

        if (imagesArr.length > 0) {
            imgContainer.style.display = 'block';
            imagesArr.forEach(imgData => {
                if (!imgData) return;
                const img = document.createElement('img');
                let src = imgData.toString().startsWith('http') ? imgData : `/images/signs/all/${imgData.trim()}`;
                if (!src.match(/\.(jpg|jpeg|png|gif|svg)$/i)) src += '.png';
                img.src = src;
                img.style.cssText = `max-height:110px;max-width:90%;object-fit:contain;border-radius:10px;margin:4px;`;
                img.onerror = () => handleImageError(img);
                imgContainer.appendChild(img);
            });
        } else {
            imgContainer.style.display = 'none';
        }

        // الخيارات
        const container = document.getElementById('options-container');
        container.innerHTML = '';
        q.options.forEach((opt, index) => {
            if (!opt || opt.trim() === "") return;
            const btn = document.createElement('button');
            btn.className = 'option-btn';

            if (isImage(opt)) {
                const img = document.createElement('img');
                let optSrc = opt.includes('.') ? opt : opt.trim() + '.png';
                img.src = `/images/signs/all/${optSrc}`;
                img.style.maxHeight = '65px';
                img.style.pointerEvents = 'none';
                img.onerror = () => { if (!handleImageError(img)) btn.innerText = opt; };
                btn.appendChild(img);
            } else {
                btn.innerText = opt;
            }

            const optionNum = index + 1;
            btn.onclick = () => checkAnswer(optionNum, q.correct_option, btn);
            if (answeredQuestions[currentIndex]) applyStyles(btn, optionNum, q.correct_option, answeredQuestions[currentIndex]);
            container.appendChild(btn);
        });

        if (answeredQuestions[currentIndex]) showExplanation(q.explanation);
        else document.getElementById('explanation-box').style.display = 'none';

        document.getElementById('prev-btn').style.visibility = currentIndex === 0 ? 'hidden' : 'visible';
        document.getElementById('next-btn').innerText = currentIndex === currentQuestions.length - 1
            ? 'إنهاء التمرن'
            : 'التالي ';
    }

    async function checkAnswer(selected, correct, btn) {
        if (answeredQuestions[currentIndex]) return;
        answeredQuestions[currentIndex] = selected;

        const currentQ = currentQuestions[currentIndex];

        await db.questions.update(currentQ.id, { is_viewed: 1 });
        fetch('/student/mark-viewed', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ question_id: currentQ.id })
        }).catch(() => {});

        if (selected == correct) {
            const params = new URLSearchParams(window.location.search);
            if (params.get('mode') === 'errors') {
                await db.wrong_questions.delete(currentQ.id);
                fetch('/student/sync-error', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ question_id: currentQ.id, count: 0 })
                }).catch(() => {});
            }
        } else {
            if (typeof recordWrongAnswer === 'function') {
                await recordWrongAnswer(currentQ.id);
                const errorRecord = await db.wrong_questions.get(currentQ.id);
                fetch('/student/sync-error', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ question_id: currentQ.id, count: errorRecord ? errorRecord.count : 1 })
                }).catch(() => {});
            }
        }

        document.querySelectorAll('.option-btn').forEach((b, i) => applyStyles(b, i + 1, correct, selected));
        showExplanation(currentQ.explanation);
    }

    function applyStyles(btn, idx, correct, selected) {
        btn.classList.add('disabled');
        if (idx == correct) btn.classList.add('correct');
        else if (idx == selected) btn.classList.add('wrong');
    }

    function showExplanation(text) {
        const box = document.getElementById('explanation-box');
        document.getElementById('explanation-text').innerText = text || "الإجابة الصحيحة موضحة أعلاه.";
        box.style.display = 'block';
    }

    function nextQuestion() {
        if (currentIndex < currentQuestions.length - 1) {
            currentIndex++;
            renderQuestion();
            window.scrollTo(0, 0);
        } else {
            window.location.href = "{{ route('student.practice.modes') }}";
        }
    }

    function prevQuestion() {
        if (currentIndex > 0) {
            currentIndex--;
            renderQuestion();
            window.scrollTo(0, 0);
        }
    }

    function handleImageError(imgTag) {
        const src = imgTag.src.toLowerCase();
        if (src.endsWith('.png'))  { imgTag.src = imgTag.src.replace('.png',  '.svg'); return true; }
        if (src.endsWith('.svg'))  { imgTag.src = imgTag.src.replace('.svg',  '.jpg'); return true; }
        imgTag.style.display = 'none'; return false;
    }

    function isImage(text) {
        return text.match(/\.(jpeg|jpg|gif|png|svg)$/i)
            || (text.trim().length > 0 && !isNaN(text.trim()) && text.trim().length < 6);
    }

    window.onload = initPractice;
</script>
@endsection
