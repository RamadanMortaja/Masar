{{--
    ============================================
    صفحة موسوعة الإشارات - traffic_signs.blade.php
    ============================================
--}}
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
            <div style="font-size:17px;font-weight:800;color:#fff;">موسوعة الإشارات</div>
            <div style="font-size:11px;color:rgba(255,255,255,.65);">تعلم إشارات المرور بذكاء</div>
        </div>
        <div style="width:34px;"></div>
    </div>
</div>

@php
$cats = [
    ['id' => 'warning',         'name' => 'تحذير وتنبيه',    'icon' => 'warning.svg',          'color' => '#f59e0b', 'bg' => 'rgba(245,158,11,.1)'],
    ['id' => 'priority',        'name' => 'حق الأولوية',     'icon' => 'priority.svg',         'color' => '#3b82f6', 'bg' => 'rgba(59,130,246,.1)'],
    ['id' => 'prohibition',     'name' => 'منع وتقييد',      'icon' => 'prohibition.svg',      'color' => '#ef4444', 'bg' => 'rgba(239,68,68,.1)'],
    ['id' => 'mandatory',       'name' => 'إرشاد وإلزام',    'icon' => 'mandatory.svg',        'color' => '#4f6ef7', 'bg' => 'rgba(79,110,247,.1)'],
    ['id' => 'info',            'name' => 'استعلامات',        'icon' => 'info.jpg',             'color' => '#10b981', 'bg' => 'rgba(16,185,129,.1)'],
    ['id' => 'lights',          'name' => 'إشارات ضوئية',    'icon' => 'lights.jpg',           'color' => '#8b5cf6', 'bg' => 'rgba(139,92,246,.1)'],
    ['id' => 'road_markings',   'name' => 'على الطريق',       'icon' => 'road_markings.jpg',    'color' => '#14b8a6', 'bg' => 'rgba(20,184,166,.1)'],
    ['id' => 'public_transport','name' => 'مواصلات عامة',    'icon' => 'public_transport.png', 'color' => '#f97316', 'bg' => 'rgba(249,115,22,.1)'],
];
@endphp

<div style="padding:16px;max-width:520px;margin:0 auto;" dir="rtl">

    {{-- شبكة الأقسام --}}
    <div id="categories-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        @foreach($cats as $i => $c)
        <div onclick="loadSignsByCategory('{{ $c['id'] }}', '{{ $c['name'] }}')"
             class="sign-cat-card fade-up"
             style="animation-delay:{{ $i * 0.06 }}s;background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:20px 14px;text-align:center;cursor:pointer;box-shadow:var(--sh);transition:var(--tr);">
            <img src="{{ asset('images/signs/cats/' . $c['icon']) }}"
                 onerror="this.src='{{ asset('images/signs/cats/warning.svg') }}'"
                 style="width:64px;height:64px;object-fit:contain;margin-bottom:10px;filter:drop-shadow(0 3px 6px rgba(0,0,0,.1));">
            <div style="font-size:13px;font-weight:700;color:var(--text-1);">{{ $c['name'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- عرض الإشارات --}}
    <div id="signs-display-section" style="display:none;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <h6 id="current-category-title" style="font-size:15px;font-weight:800;color:var(--text-1);margin:0;padding-right:12px;border-right:4px solid var(--accent);"></h6>
            <button onclick="backToCategories()"
                    style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:8px 14px;font-family:'Cairo',sans-serif;font-size:12px;font-weight:700;color:var(--text-2);cursor:pointer;display:flex;align-items:center;gap:6px;">
                <i class="fas fa-arrow-right fa-sm"></i> رجوع
            </button>
        </div>
        <div id="signs-grid-container" style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;"></div>
    </div>

</div>

{{-- مودال تفاصيل الإشارة --}}
<div class="modal fade" id="signDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="margin:0 16px;max-width:none;">
        <div class="modal-content" style="border:none;border-radius:24px;background:var(--surface);">
            <div style="height:6px;background:linear-gradient(90deg,var(--accent),#818cf8);border-radius:24px 24px 0 0;"></div>
            <div class="modal-body" style="padding:24px;text-align:center;">
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        style="position:absolute;top:16px;left:16px;"></button>

                <div style="background:var(--surface2);border-radius:18px;padding:20px;display:inline-block;margin-bottom:16px;">
                    <img id="modal-sign-img" src="" style="max-height:140px;max-width:200px;object-fit:contain;">
                </div>

                <div style="margin-bottom:14px;">
                    <span id="modal-sign-code-badge"
                          style="display:inline-block;background:var(--accent-lt);color:var(--accent);font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px;margin-bottom:8px;"></span>
                    <div id="modal-sign-title" style="font-size:18px;font-weight:800;color:var(--text-1);"></div>
                </div>

                <div style="background:var(--surface2);border-right:4px solid var(--warn);border-radius:12px;padding:14px;text-align:right;">
                    <p id="modal-sign-desc" style="font-size:13px;color:var(--text-2);line-height:1.8;margin:0;"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .sign-cat-card:hover { box-shadow: var(--sh-md); transform: translateY(-2px); }
    .sign-cat-card:active { transform: scale(.97); }
    .sign-item {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        padding: 14px 10px;
        text-align: center;
        cursor: pointer;
        transition: var(--tr);
        box-shadow: var(--sh);
    }
    .sign-item:hover { box-shadow: var(--sh-md); border-color: rgba(79,110,247,.25); }
    .sign-item:active { transform: scale(.96); }
    .sign-code { font-size:10px; font-weight:700; color:var(--accent); background:var(--accent-lt); padding:2px 8px; border-radius:8px; display:inline-block; margin-top:6px; }
</style>
@endsection

@section('scripts')
<script>
async function loadSignsByCategory(categoryId, categoryName) {
    document.getElementById('categories-grid').style.display = 'none';
    const sec = document.getElementById('signs-display-section');
    sec.style.display = 'block';
    document.getElementById('current-category-title').innerText = categoryName;

    const grid = document.getElementById('signs-grid-container');
    grid.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:32px;"><div class="spinner-border text-primary"></div></div>`;

    try {
        if (!db.isOpen()) await db.open();
        const signs = await db.traffic_signs.where('category').equals(categoryId).toArray();
        grid.innerHTML = '';

        if (signs.length === 0) {
            grid.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:32px;color:var(--text-3);font-size:13px;">لا يوجد بيانات. تأكد من المزامنة.</div>`;
            return;
        }

        signs.forEach(sign => {
            const cleanName = sign.image.split('/').pop();
            const fullPath  = `/images/signs/all/${cleanName}`;
            grid.innerHTML += `
                <div class="sign-item" onclick="openSignDetail(${sign.id})">
                    <img src="${fullPath}"
                         onerror="this.src='/images/signs/cats/warning.svg'"
                         style="height:52px;width:100%;object-fit:contain;margin-bottom:4px;">
                    <div class="sign-code">#${sign.code}</div>
                </div>`;
        });
    } catch (e) {
        grid.innerHTML = `<div style="grid-column:1/-1;text-align:center;color:var(--danger);font-size:12px;padding:20px;">خطأ في جلب البيانات</div>`;
    }
}

async function openSignDetail(signId) {
    const sign = await db.traffic_signs.get(signId);
    if (!sign) return;
    const cleanName = sign.image.split('/').pop();
    document.getElementById('modal-sign-img').src            = `/images/signs/all/${cleanName}`;
    document.getElementById('modal-sign-title').innerText    = sign.title;
    document.getElementById('modal-sign-code-badge').innerText = `إشارة رقم: ${sign.code}`;
    document.getElementById('modal-sign-desc').innerText     = sign.description || 'وصف الإشارة غير متوفر حالياً.';
    new bootstrap.Modal(document.getElementById('signDetailModal')).show();
}

function backToCategories() {
    document.getElementById('categories-grid').style.display = 'grid';
    document.getElementById('signs-display-section').style.display = 'none';
}
</script>
@endsection
