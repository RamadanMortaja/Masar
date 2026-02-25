@extends('layouts.student')

@section('content')

<div style="min-height:calc(100vh - 80px);display:flex;align-items:center;justify-content:center;padding:20px;">

    <div style="width:100%;max-width:420px;">

        {{-- بطاقة النتيجة --}}
        <div class="s-card" id="result-card" style="padding:32px 24px;text-align:center;animation:cardIn .6s ease;">

            {{-- الأيقونة --}}
            <div id="result-icon" style="font-size:72px;margin-bottom:16px;animation:iconBounce .8s .2s both;"></div>

            {{-- الحالة --}}
            <div id="result-status" style="font-size:26px;font-weight:900;margin-bottom:6px;"></div>
            <div id="result-message" style="font-size:13px;color:var(--text-3);margin-bottom:24px;"></div>

            {{-- الدرجة --}}
            <div style="background:var(--surface2);border:1px solid var(--border);border-radius:18px;padding:20px;margin-bottom:20px;">
                <div style="font-size:11px;color:var(--text-3);margin-bottom:6px;">نتيجتك النهائية</div>
                <div id="score-display" style="font-size:52px;font-weight:900;line-height:1;"></div>
                <div style="font-size:13px;color:var(--text-3);margin-top:4px;">من 30 سؤالاً</div>

                {{-- شريط النتيجة --}}
                <div style="margin-top:14px;">
                    <div class="s-progress-wrap" style="height:8px;">
                        <div id="score-bar" class="s-progress-fill" style="width:0%;transition:width 1.2s ease;"></div>
                    </div>
                </div>
            </div>

            {{-- إحصائيات --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:22px;">
                <div style="background:var(--success-lt);border:1px solid rgba(16,185,129,.15);border-radius:var(--r-sm);padding:14px 10px;">
                    <div style="font-size:22px;font-weight:800;color:var(--success);" id="correct-count">-</div>
                    <div class="xs" style="color:var(--success);opacity:.8;">إجابات صحيحة</div>
                </div>
                <div style="background:var(--danger-lt);border:1px solid rgba(239,68,68,.15);border-radius:var(--r-sm);padding:14px 10px;">
                    <div style="font-size:22px;font-weight:800;color:var(--danger);" id="wrong-count">-</div>
                    <div class="xs" style="color:var(--danger);opacity:.8;">إجابات خاطئة</div>
                </div>
            </div>

            {{-- الأزرار --}}
            <div style="display:grid;gap:10px;">
                <a href="{{ route('student.exam') }}"
                   style="display:flex;align-items:center;justify-content:center;gap:8px;padding:14px;background:linear-gradient(135deg,var(--accent),#818cf8);color:#fff;border-radius:var(--r-sm);font-size:14px;font-weight:700;text-decoration:none;box-shadow:0 4px 14px var(--accent-glow);transition:var(--tr);">
                    <i class="fas fa-redo"></i> إعادة الامتحان
                </a>
                <a href="{{ route('student.dashboard') }}"
                   style="display:flex;align-items:center;justify-content:center;gap:8px;padding:13px;background:var(--surface2);color:var(--text-2);border:1px solid var(--border);border-radius:var(--r-sm);font-size:13px;font-weight:700;text-decoration:none;transition:var(--tr);">
                    <i class="fas fa-home fa-sm"></i> العودة للرئيسية
                </a>
            </div>
        </div>

    </div>
</div>

<style>
    @keyframes cardIn { from{opacity:0;transform:scale(.92) translateY(20px)} to{opacity:1;transform:none} }
    @keyframes iconBounce { 0%{transform:scale(0) rotate(-30deg);opacity:0} 70%{transform:scale(1.15)} 100%{transform:scale(1);opacity:1} }
</style>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
@endsection

@section('scripts')
<script>
window.onload = function() {
    const params  = new URLSearchParams(window.location.search);
    const score   = parseInt(params.get('score')) || 0;
    const status  = params.get('status') || 'راسب';
    const isPass  = status === 'ناجح';

    // عرض الأيقونة والحالة
    document.getElementById('result-icon').innerText    = isPass ? '🏆' : '💪';
    document.getElementById('result-status').innerText  = status;
    document.getElementById('result-status').style.color = isPass ? 'var(--success)' : 'var(--danger)';
    document.getElementById('result-message').innerText = isPass
        ? 'تهانينا! أنت مستعد للامتحان الحقيقي.'
        : 'لا تقلق، راجع أخطاءك وحاول مرة أخرى.';

    // الدرجة
    document.getElementById('score-display').innerText = score;
    document.getElementById('score-display').style.color = isPass ? 'var(--success)' : 'var(--danger)';
    document.getElementById('correct-count').innerText = score;
    document.getElementById('wrong-count').innerText   = 30 - score;

    // شريط النتيجة
    setTimeout(() => {
        const bar = document.getElementById('score-bar');
        bar.style.width = `${(score / 30) * 100}%`;
        bar.style.background = isPass
            ? 'linear-gradient(90deg,#10b981,#6ee7b7)'
            : 'linear-gradient(90deg,#ef4444,#fca5a5)';
    }, 300);

    // احتفال عند النجاح
    if (isPass) {
        setTimeout(() => {
            confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 } });
            setTimeout(() => confetti({ particleCount: 80, angle: 60, spread: 55, origin: { x: 0 } }), 400);
            setTimeout(() => confetti({ particleCount: 80, angle: 120, spread: 55, origin: { x: 1 } }), 700);
        }, 500);
    }

    // تنظيف التكرار — نفس اللوجيك الأصلي
    cleanDuplicateResults();
};

async function cleanDuplicateResults() {
    if (typeof Dexie === 'undefined') return;
    const db = new Dexie("MasarOfflineDB");
    db.version(11).stores({ exam_results: "++id, student_id, score, status, date, synced" });
    const results  = await db.exam_results.toArray();
    const seen     = new Set();
    const toDelete = [];
    results.forEach(res => {
        const key = `${res.score}-${res.date.substring(0, 16)}`;
        if (seen.has(key)) { toDelete.push(res.id); }
        else { seen.add(key); }
    });
    if (toDelete.length > 0) {
        await db.exam_results.bulkDelete(toDelete);
        console.log(`تم حذف ${toDelete.length} نتيجة مكررة`);
    }
}
</script>
@endsection
