@extends('layouts.admin')
@section('title', 'بنك الأسئلة | نظام مسار')
@section('page_section', 'إدارة النظام')
@section('page_title', 'بنك الأسئلة')

@section('styles')
<style>
.img-container-wrapper { display:flex; gap:5px; flex-wrap:wrap; justify-content:center; }
.img-mini-preview {
    width:48px; height:48px; object-fit:contain;
    background:rgba(255,255,255,.06); border:1px solid var(--border-md);
    border-radius:6px; transition:.2s; cursor:zoom-in;
}
.img-mini-preview:hover { transform:scale(1.15); z-index:10; border-color:var(--blue); }

.bulk-bar {
    display:none; position:fixed; bottom:24px; left:50%;
    transform:translateX(-50%); z-index:1060;
    background:var(--bg-surface); border:1px solid var(--border-md);
    color:var(--text-1); border-radius:50px;
    box-shadow:0 10px 40px rgba(0,0,0,.5);
    padding:10px 20px;
    display:none; align-items:center; gap:12px;
    font-size:.82rem; font-weight:700;
}

/* Quick view modal dark */
#quickViewModal .modal-content {
    background:var(--bg-card);
    border:1px solid var(--border-md);
    border-radius:var(--radius-lg);
}
.qv-ans {
    padding:10px 14px; border-radius:9px;
    font-size:.83rem; font-weight:600;
    border:1px solid var(--border-md);
    color:var(--text-2); background:rgba(255,255,255,.03);
    transition:.15s;
}
.qv-ans.correct {
    background:rgba(16,185,129,.12);
    border-color:rgba(16,185,129,.3);
    color:#6ee7b7;
}
/* Create/Edit modal dark */
.modal-pro .modal-content {
    background:var(--bg-card);
    border:1px solid var(--border-md);
    border-radius:var(--radius-lg);
}
.modal-pro .modal-header {
    background:var(--bg-surface);
    border-bottom:1px solid var(--border);
    padding:15px 20px;
}
.modal-pro .modal-title { color:var(--text-1); font-weight:800; font-size:.9rem; }
.modal-pro .modal-body  { padding:20px; }
.modal-pro .modal-footer {
    background:var(--bg-surface);
    border-top:1px solid var(--border);
    padding:13px 20px;
}
.form-q {
    width:100%; padding:8px 11px;
    border:1px solid var(--border-md); border-radius:var(--radius);
    font-family:'Tajawal',sans-serif; font-size:.82rem;
    color:var(--text-1); background:rgba(255,255,255,.04);
    outline:none; transition:.18s; direction:rtl;
}
.form-q:focus {
    border-color:var(--blue);
    box-shadow:0 0 0 3px var(--blue-glow);
    background:var(--bg-hover);
}
.form-q option { background:var(--bg-card); color:var(--text-1); }
.form-q::placeholder { color:var(--text-3); }
.form-lbl {
    display:block; font-size:.68rem; font-weight:800;
    color:var(--text-3); text-transform:uppercase;
    letter-spacing:.05em; margin-bottom:4px;
}
</style>
@endsection

@section('content')
<div class="page-hd">
    <div>
        <div class="page-hd-title">
            <i class="fas fa-traffic-light" style="color:var(--blue-light);margin-left:8px;"></i>
            بنك الأسئلة
        </div>
        <div class="page-hd-sub">إدارة الأسئلة مع دعم الصور المتعددة</div>
    </div>
    <div style="display:flex;gap:9px;">
        <button class="btn-pro btn-blue" data-bs-toggle="modal" data-bs-target="#createQuestionModal">
            <i class="fas fa-plus"></i> إضافة سؤال
        </button>
        <div class="dropdown">
            <button class="btn-pro btn-ghost dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-file-export"></i> أدوات البيانات
            </button>
            <ul class="dropdown-menu dropdown-menu-start shadow text-end"
                style="background:var(--bg-card);border:1px solid var(--border-md);border-radius:var(--radius);">
                <li>
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importModal"
                       style="color:var(--text-2);font-size:.82rem;padding:9px 16px;">
                        <i class="fas fa-file-import" style="color:var(--green);margin-left:7px;"></i>استيراد من Excel
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('questions.export') }}"
                       style="color:var(--text-2);font-size:.82rem;padding:9px 16px;">
                        <i class="fas fa-file-excel" style="color:var(--blue-light);margin-left:7px;"></i>تصدير البنك
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<form action="{{ route('questions.index') }}" method="GET">
<div class="filter-bar">
    <input type="text" name="search" class="f-input" placeholder="ابحث بنص السؤال..." value="{{ request('search') }}" style="min-width:200px;">
    <select name="exam_type" class="f-select">
        <option value="">نوع الامتحان</option>
        <option value="تحريري" {{ request('exam_type')=='تحريري'?'selected':'' }}>تحريري</option>
        <option value="شفوي"   {{ request('exam_type')=='شفوي'  ?'selected':'' }}>شفوي</option>
    </select>
    <select name="license_type" class="f-select">
        <option value="">نوع الرخصة</option>
        @foreach(['ملاكي','تجاري','عمومي','حمولة'] as $type)
            <option value="{{ $type }}" {{ request('license_type')==$type?'selected':'' }}>{{ $type }}</option>
        @endforeach
    </select>
    <select name="theory_type" class="f-select">
        <option value="">التصنيف</option>
        @foreach(['إشارات','قوانين سير','ميكانيكا','عام'] as $th)
            <option value="{{ $th }}" {{ request('theory_type')==$th?'selected':'' }}>{{ $th }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-pro btn-blue btn-sm"><i class="fas fa-filter"></i> بحث</button>
    <a href="{{ route('questions.index') }}" class="btn-pro btn-ghost btn-sm" style="text-decoration:none;"><i class="fas fa-undo"></i></a>
</div>
</form>

{{-- Table --}}
<div class="c-card">
    <div style="overflow-x:auto;">
    <table class="c-table">
        <thead>
            <tr>
                <th style="width:38px;"><input type="checkbox" id="selectAll" style="accent-color:var(--blue);"></th>
                <th>ID</th>
                <th>الصور</th>
                <th>نص السؤال</th>
                <th>التصنيف</th>
                <th>الامتحان</th>
                <th>الإجابة الصحيحة</th>
                <th style="text-align:right;padding-right:8px;">إجراءات</th>
            </tr>
        </thead>
        <tbody>
        @forelse($questions as $question)
        @php
            $allSignImages = [];
            if($question->sign_id) {
                foreach(explode(';', $question->sign_id) as $id) {
                    $id = trim($id);
                    foreach(['svg','png','jpg'] as $ext) {
                        $path = "images/signs/all/{$id}.{$ext}";
                        if(file_exists(public_path($path))) {
                            $allSignImages[] = asset($path); break;
                        }
                    }
                }
            }
            $dbImg = $question->question_picture ? asset('storage/'.$question->question_picture) : null;
        @endphp
        <tr class="question-row">
            <td><input type="checkbox" class="question-checkbox" value="{{ $question->id }}" style="accent-color:var(--blue);"></td>
            <td style="font-family:monospace;font-size:.7rem;color:var(--text-3);">#{{ $question->id }}</td>
            <td>
                <div class="img-container-wrapper">
                    @foreach($allSignImages as $imgUrl)
                        <img src="{{ $imgUrl }}" class="img-mini-preview qv-img-btn"
                             data-qid="{{ $question->id }}" style="cursor:zoom-in;">
                    @endforeach
                    @if($dbImg)
                        <img src="{{ $dbImg }}" class="img-mini-preview qv-img-btn" style="border-color:var(--blue);cursor:zoom-in;"
                             data-qid="{{ $question->id }}">
                    @endif
                </div>
            </td>
            <td>
                <div style="font-weight:700;color:var(--text-1);font-size:.83rem;">{{ Str::limit($question->question_text, 70) }}</div>
                <div style="font-size:.68rem;color:var(--text-3);margin-top:2px;">
                    الرخصة: {{ $question->license_type }} | Sign: {{ $question->sign_id ?: 'بدون' }}
                </div>
            </td>
            <td><span class="badge-pro badge-purple">{{ $question->theory_type }}</span></td>
            <td><span style="font-size:.78rem;color:var(--text-3);">{{ $question->exam_type }}</span></td>
            <td><span style="font-weight:800;color:#6ee7b7;">{{ $question->correct_answer }}</span></td>
            <td>
                <div style="display:flex;gap:5px;justify-content:flex-end;padding-left:8px;">
                    <button class="btn-pro btn-ghost btn-icon qv-btn"
                            data-qid="{{ $question->id }}"
                            title="معاينة" style="color:var(--cyan);">
                        <i class="fas fa-eye" style="font-size:.7rem;"></i>
                    </button>
                    <button class="btn-pro btn-amber btn-icon edit-btn"
                            data-id="{{ $question->id }}"
                            data-text="{{ $question->question_text }}"
                            data-ans1="{{ $question->answer_1 }}" data-ans2="{{ $question->answer_2 }}"
                            data-ans3="{{ $question->answer_3 }}" data-ans4="{{ $question->answer_4 }}"
                            data-correct="{{ $question->correct_answer }}" data-license="{{ $question->license_type }}"
                            data-theory="{{ $question->theory_type }}" data-exam="{{ $question->exam_type }}"
                            data-sign="{{ $question->sign_id }}" data-exp="{{ $question->question_explanation }}"
                            data-bs-toggle="modal" data-bs-target="#editQuestionModal"
                            title="تعديل">
                        <i class="fas fa-pen" style="font-size:.68rem;"></i>
                    </button>
                    <form action="{{ route('questions.destroy', $question->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-pro btn-red btn-icon"
                                onclick="return confirm('حذف هذا السؤال؟')" title="حذف">
                            <i class="fas fa-trash" style="font-size:.68rem;"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="8"><div class="empty-state"><i class="fas fa-database"></i><strong>لا توجد أسئلة</strong><span>لم يتطابق أي سؤال مع الفلتر الحالي</span></div></td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
    @if($questions->hasPages())
    <div style="padding:13px 18px;border-top:1px solid var(--border);">
        {{ $questions->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- Hidden JS data store for quick view --}}
<script id="questions-data-json" type="application/json">
{
@foreach($questions as $q)
@php
    $qSignImgs = [];
    if($q->sign_id) {
        foreach(explode(';', $q->sign_id) as $sid) {
            $sid = trim($sid);
            foreach(['svg','png','jpg'] as $ext) {
                $p = "images/signs/all/{$sid}.{$ext}";
                if(file_exists(public_path($p))) { $qSignImgs[] = asset($p); break; }
            }
        }
    }
    $qDbImg = $q->question_picture ? asset('storage/'.$q->question_picture) : null;
@endphp
"{{ $q->id }}": {
    "id": {{ $q->id }},
    "question_text": @json($q->question_text),
    "answer_1": @json($q->answer_1),
    "answer_2": @json($q->answer_2),
    "answer_3": @json($q->answer_3),
    "answer_4": @json($q->answer_4),
    "correct_answer": @json((string)$q->correct_answer),
    "question_explanation": @json($q->question_explanation),
    "theory_type": @json($q->theory_type),
    "exam_type": @json($q->exam_type),
    "license_type": @json($q->license_type),
    "sign_id": @json($q->sign_id),
    "signs": @json($qSignImgs),
    "db_img": @json($qDbImg)
}{{ !$loop->last ? ',' : '' }}
@endforeach
}
</script>

{{-- ══ Modal: Quick View ══ --}}
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-pro" dir="rtl">
        <div class="modal-content">
            <div class="modal-body" style="padding:22px;">
                <div style="display:flex;justify-content:flex-end;margin-bottom:14px;">
                    <div style="width:28px;height:28px;border-radius:7px;background:rgba(255,255,255,.06);border:1px solid var(--border-md);display:flex;align-items:center;justify-content:center;font-size:.7rem;color:var(--text-2);cursor:pointer;" data-bs-dismiss="modal"><i class="fas fa-times"></i></div>
                </div>
                <div id="qv-images" style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center;margin-bottom:18px;"></div>
                <h5 id="qv-text" style="font-weight:800;color:var(--text-1);text-align:center;margin-bottom:16px;font-size:.95rem;"></h5>
                <div id="qv-answers" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;"></div>
                <div id="qv-exp" style="padding:12px 16px;background:rgba(59,130,246,.07);border:1px solid rgba(59,130,246,.15);border-radius:9px;font-size:.79rem;color:var(--text-2);"></div>
            </div>
        </div>
    </div>
</div>

{{-- ══ Modal: Create Question ══ --}}
<div class="modal fade modal-pro" id="createQuestionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" dir="rtl">
        <div class="modal-content">
            <div class="modal-header" style="display:flex;align-items:center;justify-content:space-between;">
                <h5 class="modal-title"><i class="fas fa-plus" style="color:var(--green);margin-left:6px;"></i>إضافة سؤال جديد</h5>
                <div style="width:28px;height:28px;border-radius:7px;background:rgba(255,255,255,.06);border:1px solid var(--border-md);display:flex;align-items:center;justify-content:center;font-size:.7rem;color:var(--text-2);cursor:pointer;" data-bs-dismiss="modal"><i class="fas fa-times"></i></div>
            </div>
            <form action="{{ route('questions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div style="display:grid;gap:12px;">
                        <div>
                            <label class="form-lbl">نص السؤال *</label>
                            <textarea name="question_text" class="form-q" rows="3" required></textarea>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                            <div><label class="form-lbl">إجابة 1</label><input type="text" name="answer_1" class="form-q" required></div>
                            <div><label class="form-lbl">إجابة 2</label><input type="text" name="answer_2" class="form-q" required></div>
                            <div><label class="form-lbl">إجابة 3</label><input type="text" name="answer_3" class="form-q"></div>
                            <div><label class="form-lbl">إجابة 4</label><input type="text" name="answer_4" class="form-q"></div>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;">
                            <div><label class="form-lbl">الإجابة الصحيحة (1-4)</label><input type="number" name="correct_answer" class="form-q" min="1" max="4" required></div>
                            <div><label class="form-lbl">الرخصة</label>
                                <select name="license_type" class="form-q">
                                    @foreach(['ملاكي','تجاري','عمومي','حمولة'] as $l)<option value="{{ $l }}">{{ $l }}</option>@endforeach
                                </select>
                            </div>
                            <div><label class="form-lbl">التصنيف</label>
                                <select name="theory_type" class="form-q">
                                    @foreach(['إشارات','قوانين سير','ميكانيكا','عام'] as $c)<option value="{{ $c }}">{{ $c }}</option>@endforeach
                                </select>
                            </div>
                            <div><label class="form-lbl">الامتحان</label>
                                <select name="exam_type" class="form-q">
                                    <option value="تحريري">تحريري</option>
                                    <option value="شفوي">شفوي</option>
                                </select>
                            </div>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                            <div><label class="form-lbl">Sign ID (مثال: 61;111)</label><input type="text" name="sign_id" class="form-q"></div>
                            <div><label class="form-lbl">رفع صورة (اختياري)</label><input type="file" name="question_picture" class="form-q" style="padding:6px;"></div>
                        </div>
                        <div><label class="form-lbl">شرح الإجابة</label><textarea name="question_explanation" class="form-q" rows="2"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer" style="display:flex;gap:9px;">
                    <button type="button" class="btn-pro btn-ghost" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn-pro btn-blue"><i class="fas fa-save"></i> حفظ السؤال</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══ Modal: Edit Question ══ --}}
<div class="modal fade modal-pro" id="editQuestionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" dir="rtl">
        <div class="modal-content">
            <div class="modal-header" style="display:flex;align-items:center;justify-content:space-between;">
                <h5 class="modal-title"><i class="fas fa-pen" style="color:var(--amber);margin-left:6px;"></i>تعديل السؤال</h5>
                <div style="width:28px;height:28px;border-radius:7px;background:rgba(255,255,255,.06);border:1px solid var(--border-md);display:flex;align-items:center;justify-content:center;font-size:.7rem;color:var(--text-2);cursor:pointer;" data-bs-dismiss="modal"><i class="fas fa-times"></i></div>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div style="display:grid;gap:12px;">
                        <div><label class="form-lbl">نص السؤال *</label><textarea name="question_text" id="edit_text" class="form-q" rows="3" required></textarea></div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                            <div><label class="form-lbl">إجابة 1</label><input type="text" name="answer_1" id="edit_ans1" class="form-q"></div>
                            <div><label class="form-lbl">إجابة 2</label><input type="text" name="answer_2" id="edit_ans2" class="form-q"></div>
                            <div><label class="form-lbl">إجابة 3</label><input type="text" name="answer_3" id="edit_ans3" class="form-q"></div>
                            <div><label class="form-lbl">إجابة 4</label><input type="text" name="answer_4" id="edit_ans4" class="form-q"></div>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;">
                            <div><label class="form-lbl">الإجابة الصحيحة</label><input type="number" name="correct_answer" id="edit_correct" class="form-q" min="1" max="4"></div>
                            <div><label class="form-lbl">الرخصة</label>
                                <select name="license_type" id="edit_license" class="form-q">
                                    @foreach(['ملاكي','تجاري','عمومي','حمولة'] as $l)<option value="{{ $l }}">{{ $l }}</option>@endforeach
                                </select>
                            </div>
                            <div><label class="form-lbl">التصنيف</label>
                                <select name="theory_type" id="edit_theory" class="form-q">
                                    @foreach(['إشارات','قوانين سير','ميكانيكا','عام'] as $c)<option value="{{ $c }}">{{ $c }}</option>@endforeach
                                </select>
                            </div>
                            <div><label class="form-lbl">الامتحان</label>
                                <select name="exam_type" id="edit_exam" class="form-q">
                                    <option value="تحريري">تحريري</option>
                                    <option value="شفوي">شفوي</option>
                                </select>
                            </div>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                            <div><label class="form-lbl">Sign ID</label><input type="text" name="sign_id" id="edit_sign" class="form-q"></div>
                            <div><label class="form-lbl">تغيير الصورة</label><input type="file" name="question_picture" class="form-q" style="padding:6px;"></div>
                        </div>
                        <div><label class="form-lbl">شرح الإجابة</label><textarea name="question_explanation" id="edit_exp" class="form-q" rows="2"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer" style="display:flex;gap:9px;">
                    <button type="button" class="btn-pro btn-ghost" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn-pro btn-blue"><i class="fas fa-save"></i> حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══ Modal: Import ══ --}}
<div class="modal fade modal-pro" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" dir="rtl">
        <div class="modal-content">
            <div class="modal-header" style="display:flex;align-items:center;justify-content:space-between;">
                <h5 class="modal-title"><i class="fas fa-file-import" style="color:var(--green);margin-left:6px;"></i>استيراد من Excel</h5>
                <div style="width:28px;height:28px;border-radius:7px;background:rgba(255,255,255,.06);border:1px solid var(--border-md);display:flex;align-items:center;justify-content:center;font-size:.7rem;color:var(--text-2);cursor:pointer;" data-bs-dismiss="modal"><i class="fas fa-times"></i></div>
            </div>
            <form action="{{ route('questions.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group" style="margin-bottom:12px;">
                        <label class="form-lbl" style="color:var(--text-3);font-size:.68rem;font-weight:800;text-transform:uppercase;">ملف Excel</label>
                        <input type="file" name="excel_file" class="form-q" required style="padding:6px;">
                    </div>
                    <div>
                        <label class="form-lbl" style="color:var(--text-3);font-size:.68rem;font-weight:800;text-transform:uppercase;">نوع الامتحان</label>
                        <select name="exam_type" class="form-q">
                            <option value="تحريري">تحريري</option>
                            <option value="شفوي">شفوي</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="display:flex;gap:9px;">
                    <button type="button" class="btn-pro btn-ghost" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn-pro btn-blue"><i class="fas fa-upload"></i> بدء الاستيراد</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Quick View - load from embedded JSON data store
let questionsData = {};
try {
    const jsonEl = document.getElementById('questions-data-json');
    if (jsonEl) questionsData = JSON.parse(jsonEl.textContent);
} catch(e) { console.warn('Could not parse questions data', e); }

document.addEventListener('click', function(e) {
    const btn = e.target.closest('.qv-btn, .qv-img-btn');
    if (!btn) return;
    const qid = btn.dataset.qid;
    const q = questionsData[qid];
    if (!q) { console.warn('Question not found:', qid); return; }
    showQuickView(q, q.signs || [], q.db_img || '');
});

function showQuickView(q, signImgs, dbImg) {
    const imgBox = document.getElementById('qv-images');
    imgBox.innerHTML = '';
    if(signImgs && signImgs.length) signImgs.forEach(src => {
        imgBox.innerHTML += `<img src="${src}" style="max-height:130px;border-radius:8px;border:1px solid var(--border-md);">`;
    });
    if(dbImg) imgBox.innerHTML += `<img src="${dbImg}" style="max-height:130px;border-radius:8px;border:2px solid var(--blue);">`;

    document.getElementById('qv-text').textContent = q.question_text || '';
    document.getElementById('qv-exp').innerHTML = `<strong style="color:var(--blue-light);">الشرح:</strong><br>${q.question_explanation || 'لا يوجد شرح.'}`;

    const ansBox = document.getElementById('qv-answers');
    ansBox.innerHTML = '';
    [1,2,3,4].forEach(i => {
        if(q['answer_'+i]) {
            const isCorrect = String(q.correct_answer) === String(i);
            ansBox.innerHTML += `<div class="qv-ans ${isCorrect?'correct':''}">${i}. ${q['answer_'+i]}</div>`;
        }
    });
    new bootstrap.Modal(document.getElementById('quickViewModal')).show();
}
// Inline image clicks still use old window.quickView
window.quickView = showQuickView;

// Edit modal fill
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        document.getElementById('editForm').action = `/admin/questions/${id}`;
        document.getElementById('edit_text').value    = this.dataset.text;
        document.getElementById('edit_ans1').value   = this.dataset.ans1;
        document.getElementById('edit_ans2').value   = this.dataset.ans2;
        document.getElementById('edit_ans3').value   = this.dataset.ans3;
        document.getElementById('edit_ans4').value   = this.dataset.ans4;
        document.getElementById('edit_correct').value= this.dataset.correct;
        document.getElementById('edit_license').value= this.dataset.license;
        document.getElementById('edit_theory').value = this.dataset.theory;
        document.getElementById('edit_exam').value   = this.dataset.exam;
        document.getElementById('edit_sign').value   = this.dataset.sign;
        document.getElementById('edit_exp').value    = this.dataset.exp;
    });
});

// Select all
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.question-checkbox').forEach(cb => cb.checked = this.checked);
});
</script>
@endsection
