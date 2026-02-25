@extends('layouts.student')

@section('content')

{{-- هيدر الصفحة --}}
<div class="page-header">
    <div style="position:relative;z-index:2;">
        <div style="font-size:18px;font-weight:800;color:#fff;margin-bottom:2px;">بوابة التدريب الذكي</div>
        <div style="font-size:12px;color:rgba(255,255,255,.65);">اختر القسم الذي تود مراجعته</div>
    </div>
</div>

@php
$categories = [
    ['id' => 'all',         'name' => 'تدريب شامل',       'desc' => 'كل الأسئلة عشوائياً',           'icon' => 'fa-layer-group',       'color' => '#4f6ef7', 'bg' => 'rgba(79,110,247,.1)'],
    ['id' => 'إشارات',      'name' => 'قسم الإشارات',     'desc' => 'إشارات المرور والطريق',         'icon' => 'fa-traffic-light',     'color' => '#f59e0b', 'bg' => 'rgba(245,158,11,.1)'],
    ['id' => 'قوانين سير',  'name' => 'القوانين',          'desc' => 'قواعد السير والأولويات',        'icon' => 'fa-gavel',             'color' => '#3b82f6', 'bg' => 'rgba(59,130,246,.1)'],
    ['id' => 'ميكانيكا',    'name' => 'ميكانيكا',          'desc' => 'أجزاء المركبة والأعطال',        'icon' => 'fa-tools',             'color' => '#10b981', 'bg' => 'rgba(16,185,129,.1)'],
    ['id' => 'errors',      'name' => 'بنك الأخطاء',       'desc' => 'الأسئلة التي أخطأت بها',       'icon' => 'fa-exclamation-circle','color' => '#ef4444', 'bg' => 'rgba(239,68,68,.1)'],
];
@endphp

<div style="padding:18px 14px;max-width:520px;margin:0 auto;">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">

        @foreach($categories as $i => $cat)
        <div onclick="goToPractice('{{ $cat['id'] }}')"
             class="practice-card fade-up"
             style="animation-delay:{{ $i * 0.07 }}s;background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:18px 14px;text-align:center;cursor:pointer;box-shadow:var(--sh);transition:var(--tr);
             {{ $cat['id'] === 'all' ? 'grid-column:1/-1;display:flex;align-items:center;gap:16px;text-align:right;' : '' }}">

            {{-- الأيقونة --}}
            <div style="width:54px;height:54px;border-radius:16px;background:{{ $cat['bg'] }};color:{{ $cat['color'] }};display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;
                        {{ $cat['id'] === 'all' ? '' : 'margin:0 auto 10px;' }}">
                <i class="fas {{ $cat['icon'] }}"></i>
            </div>

            <div style="{{ $cat['id'] === 'all' ? 'flex:1;' : '' }}">
                <div style="font-size:14px;font-weight:800;color:var(--text-1);margin-bottom:2px;">{{ $cat['name'] }}</div>
                <div class="xs" style="color:var(--text-3);margin-bottom:{{ $cat['id'] !== 'errors' ? '10px' : '0' }};">{{ $cat['desc'] }}</div>

                @if($cat['id'] !== 'errors')
                {{-- شريط التقدم --}}
                <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                    <span class="xs" style="color:var(--text-3);" id="count-{{ $cat['id'] }}">0/0</span>
                    <span class="xs" style="font-weight:700;color:{{ $cat['color'] }};" id="percent-{{ $cat['id'] }}">0%</span>
                </div>
                <div style="background:rgba(0,0,0,.06);border-radius:4px;height:5px;overflow:hidden;">
                    <div id="bar-{{ $cat['id'] }}"
                         style="height:100%;border-radius:4px;background:{{ $cat['color'] }};width:0%;transition:width .8s ease;"></div>
                </div>
                @else
                <div id="count-errors" class="xs" style="color:var(--danger);font-weight:700;"></div>
                @endif
            </div>

        </div>
        @endforeach

    </div>

</div>

<style>
    .practice-card:hover { box-shadow: var(--sh-md); transform: translateY(-2px); }
    .practice-card:active { transform: scale(.97); }
</style>
@endsection

@section('scripts')
<script>
const db = new Dexie("MasarOfflineDB");
db.version(11).stores({
    questions: "id, category, is_viewed",
    wrong_questions: "q_id, count"
});

const categories = @json($categories);

async function updateProgressUI() {
    try {
        if (!db.isOpen()) await db.open();
        const allQ    = await db.questions.toArray();
        const wrongQ  = await db.wrong_questions.toArray();

        for (const cat of categories) {
            if (cat.id === 'errors') {
                const errEl = document.getElementById('count-errors');
                if (errEl) {
                    errEl.innerText = wrongQ.length > 0
                        ? `${wrongQ.length} سؤال تعثرت به`
                        : 'لا أخطاء حالياً 🎉';
                }
                continue;
            }

            const catQ = cat.id === 'all'
                ? allQ
                : allQ.filter(q => q.category === cat.id);

            const total   = catQ.length;
            const viewed  = catQ.filter(q => q.is_viewed === 1).length;
            const percent = total > 0 ? Math.round((viewed / total) * 100) : 0;

            const countEl   = document.getElementById(`count-${cat.id}`);
            const percentEl = document.getElementById(`percent-${cat.id}`);
            const barEl     = document.getElementById(`bar-${cat.id}`);

            if (countEl)   countEl.innerText   = `${viewed}/${total}`;
            if (percentEl) percentEl.innerText = `${percent}%`;
            if (barEl)     barEl.style.width   = `${percent}%`;
        }
    } catch (e) {
        console.error("Progress update failed:", e);
    }
}

function goToPractice(catId) {
    const mode = (catId === 'all' || catId === 'errors') ? catId : 'category';
    window.location.href = `{{ route('student.practice.view') }}?category=${catId}&mode=${mode}`;
}

document.addEventListener('DOMContentLoaded', updateProgressUI);
</script>
@endsection
