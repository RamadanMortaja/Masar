@extends('layouts.student')

@section('content')

{{-- هيدر الملف الشخصي --}}
<div style="background:linear-gradient(145deg,#1e3a8a 0%,#4f6ef7 60%,#818cf8 100%);padding:28px 20px 40px;position:relative;overflow:hidden;text-align:center;">
    <div style="position:absolute;top:-40px;left:-40px;width:160px;height:160px;background:rgba(255,255,255,.05);border-radius:50%;"></div>
    <div style="position:absolute;bottom:-60px;right:-30px;width:200px;height:200px;background:rgba(255,255,255,.04);border-radius:50%;"></div>

    {{-- رسالة نجاح التصفير --}}
    @if(session('success_reset'))
        <div style="background:rgba(16,185,129,.2);border:1px solid rgba(16,185,129,.35);border-radius:12px;padding:10px 14px;margin-bottom:16px;font-size:12px;color:#6ee7b7;display:flex;align-items:center;gap:8px;justify-content:center;position:relative;z-index:2;">
            <i class="fas fa-check-circle"></i> {{ session('success_reset') }}
        </div>
    @endif

    {{-- صورة الطالب --}}
    <div style="position:relative;display:inline-block;margin-bottom:12px;z-index:2;">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name ?? 'ST') }}&background=ffffff&color=4f6ef7&size=160&bold=true"
             style="width:80px;height:80px;border-radius:22px;border:3px solid rgba(255,255,255,.4);box-shadow:0 8px 24px rgba(0,0,0,.25);">
        <div style="position:absolute;bottom:-4px;right:-4px;width:20px;height:20px;background:var(--success);border-radius:50%;border:2px solid #fff;"></div>
    </div>

    <div style="font-size:19px;font-weight:800;color:#fff;position:relative;z-index:2;">{{ $student->name ?? 'الطالب' }}</div>
    <div style="font-size:12px;color:rgba(255,255,255,.65);margin-top:3px;position:relative;z-index:2;">
        رقم الهوية: {{ $student->id_number ?? $student->id }}
    </div>
</div>

{{-- بطاقة الإحصائيات --}}
<div style="margin:-20px 16px 0;position:relative;z-index:5;margin-bottom:16px;">
    <div class="s-card" style="display:grid;grid-template-columns:1fr 1fr 1fr;">
        <div style="padding:16px 8px;text-align:center;border-left:1px solid var(--border);">
            <div style="font-size:22px;font-weight:800;color:var(--accent);">{{ $examStats->total_exams ?? 0 }}</div>
            <div class="xs" style="color:var(--text-3);">امتحان</div>
        </div>
        <div style="padding:16px 8px;text-align:center;border-left:1px solid var(--border);">
            <div style="font-size:22px;font-weight:800;color:var(--success);">{{ $examStats->success_count ?? 0 }}</div>
            <div class="xs" style="color:var(--text-3);">نجاح</div>
        </div>
        <div style="padding:16px 8px;text-align:center;">
            <div style="font-size:22px;font-weight:800;color:var(--warn);">{{ $examStats->best_score ?? 0 }}</div>
            <div class="xs" style="color:var(--text-3);">أعلى درجة</div>
        </div>
    </div>
</div>

<div style="padding:0 16px;max-width:520px;margin:0 auto;">

    {{-- التقدم الدراسي --}}
    <div class="s-card fade-up" style="padding:18px 20px;margin-bottom:16px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <div style="font-size:14px;font-weight:700;color:var(--text-1);display:flex;align-items:center;gap:7px;">
                <div style="width:7px;height:7px;border-radius:50%;background:var(--success);box-shadow:0 0 0 3px var(--success-lt);"></div>
                التقدم الدراسي
            </div>
            <span style="font-size:18px;font-weight:800;color:var(--success);">{{ $progressPercent }}%</span>
        </div>
        <div class="s-progress-wrap" style="height:10px;margin-bottom:8px;">
            <div class="s-progress-fill"
                 id="prof-progress"
                 style="width:{{ $progressPercent }}%;background:linear-gradient(90deg,var(--success),#6ee7b7);">
            </div>
        </div>
        <div class="xs" style="color:var(--text-3);">
            أنجزت {{ $progressPercent }}% من المنهج الكلي
        </div>
    </div>

    {{-- بيانات الملف التدريبي --}}
    <div class="s-card fade-up" style="margin-bottom:16px;">
        <div style="padding:16px 18px 10px;border-bottom:1px solid var(--border);">
            <div style="font-size:14px;font-weight:700;color:var(--text-1);display:flex;align-items:center;gap:7px;">
                <div style="width:7px;height:7px;border-radius:50%;background:var(--accent);box-shadow:0 0 0 3px var(--accent-lt);"></div>
                الملف التدريبي الكامل
            </div>
        </div>
        <div style="padding:16px 18px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="s-icon s-icon-blue"><i class="fas fa-school fa-sm"></i></div>
                    <div>
                        <div class="xs" style="color:var(--text-3);">المدرسة</div>
                        <div style="font-size:13px;font-weight:700;color:var(--text-1);">{{ $student->school_name ?? 'غير محدد' }}</div>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="s-icon s-icon-green"><i class="fas fa-id-badge fa-sm"></i></div>
                    <div>
                        <div class="xs" style="color:var(--text-3);">نوع الرخصة</div>
                        <div style="font-size:13px;font-weight:700;color:var(--text-1);">{{ $student->license_type ?? 'غير محدد' }}</div>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="s-icon s-icon-amber"><i class="fas fa-file-alt fa-sm"></i></div>
                    <div>
                        <div class="xs" style="color:var(--text-3);">نوع الامتحان</div>
                        <div style="font-size:13px;font-weight:700;color:var(--text-1);">{{ $student->theory_type ?? 'غير محدد' }}</div>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="s-icon s-icon-purple"><i class="fas fa-car fa-sm"></i></div>
                    <div>
                        <div class="xs" style="color:var(--text-3);">ناقل الحركة</div>
                        <div style="font-size:13px;font-weight:700;color:var(--text-1);">{{ $student->gear_type === 'auto' ? 'أتوماتيك' : 'يدوي' }}</div>
                    </div>
                </div>

                @if($student->trainer_name)
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="s-icon s-icon-teal"><i class="fas fa-user-tie fa-sm"></i></div>
                    <div>
                        <div class="xs" style="color:var(--text-3);">المدرب</div>
                        <div style="font-size:13px;font-weight:700;color:var(--text-1);">{{ $student->trainer_name }}</div>
                    </div>
                </div>
                @endif

                @if($student->car_info)
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="s-icon s-icon-amber"><i class="fas fa-car-side fa-sm"></i></div>
                    <div>
                        <div class="xs" style="color:var(--text-3);">السيارة</div>
                        <div style="font-size:13px;font-weight:700;color:var(--text-1);">{{ $student->car_info }}</div>
                    </div>
                </div>
                @endif

                {{-- النتائج --}}
                <div style="grid-column:1/-1;padding-top:12px;border-top:1px solid var(--border);">
                    <div class="xs" style="color:var(--text-3);font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px;">نتائج الامتحانات</div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        @if($student->theory_exam_result)
                            <span class="s-badge {{ $student->theory_exam_result === 'ناجح' ? 's-badge-success' : 's-badge-danger' }}">
                                <i class="fas {{ $student->theory_exam_result === 'ناجح' ? 'fa-check' : 'fa-times' }} fa-xs" style="margin-left:3px;"></i>
                                نظري: {{ $student->theory_exam_result }}
                            </span>
                        @endif
                        @if($student->practical_test_result)
                            <span class="s-badge {{ $student->practical_test_result === 'ناجح' ? 's-badge-success' : 's-badge-danger' }}">
                                <i class="fas {{ $student->practical_test_result === 'ناجح' ? 'fa-check' : 'fa-times' }} fa-xs" style="margin-left:3px;"></i>
                                عملي: {{ $student->practical_test_result }}
                            </span>
                        @endif
                        @if($student->medical_test_result)
                            <span class="s-badge {{ $student->medical_test_result === 'لائق' ? 's-badge-success' : 's-badge-danger' }}">
                                <i class="fas fa-heartbeat fa-xs" style="margin-left:3px;"></i>
                                طبي: {{ $student->medical_test_result }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- الفحص الطبي --}}
                <div style="grid-column:1/-1;padding-top:12px;border-top:1px solid var(--border);">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="s-icon s-icon-red"><i class="fas fa-notes-medical fa-sm"></i></div>
                        <div style="flex:1;">
                            <div class="xs" style="color:var(--text-3);margin-bottom:5px;">صلاحية الفحص الطبي</div>
                            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                <span class="s-badge s-badge-muted"><i class="fas fa-calendar-plus fa-xs" style="margin-left:3px;"></i>إصدار: {{ $student->medical_test_date ?? '---' }}</span>
                                <span class="s-badge s-badge-danger"><i class="fas fa-calendar-times fa-xs" style="margin-left:3px;"></i>انتهاء: {{ $student->medical_test_expiry ?? '---' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- تواريخ الامتحانات --}}
                <div style="grid-column:1/-1;padding-top:12px;border-top:1px solid var(--border);">
                    <div class="xs" style="color:var(--text-3);font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px;">مواعيد الامتحانات</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                        @if($student->theory_exam_date)
                        <div style="background:var(--surface2);border-radius:10px;padding:10px;font-size:12px;">
                            <div style="color:var(--text-3);margin-bottom:2px;">امتحان نظري</div>
                            <div style="font-weight:700;color:var(--text-1);" dir="ltr">{{ $student->theory_exam_date }}</div>
                        </div>
                        @endif
                        @if($student->practical_test_date)
                        <div style="background:var(--surface2);border-radius:10px;padding:10px;font-size:12px;">
                            <div style="color:var(--text-3);margin-bottom:2px;">امتحان عملي</div>
                            <div style="font-weight:700;color:var(--text-1);" dir="ltr">{{ $student->practical_test_date }}</div>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- جدول الحصص --}}
    @if(isset($drivingSessions) && $drivingSessions->count() > 0)
    <div class="s-card fade-up" style="margin-bottom:16px;">
        <div style="padding:14px 18px 10px;border-bottom:1px solid var(--border);">
            <div style="font-size:14px;font-weight:700;color:var(--text-1);display:flex;align-items:center;gap:7px;">
                <div style="width:7px;height:7px;border-radius:50%;background:var(--success);box-shadow:0 0 0 3px var(--success-lt);"></div>
                جدول الحصص
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:12px;">
                <thead>
                    <tr style="background:var(--surface2);">
                        <th style="padding:9px 12px;color:var(--text-3);font-weight:700;text-align:right;border-bottom:1px solid var(--border);">التاريخ</th>
                        <th style="padding:9px 12px;color:var(--text-3);font-weight:700;text-align:right;border-bottom:1px solid var(--border);">المدرب</th>
                        <th style="padding:9px 12px;color:var(--text-3);font-weight:700;text-align:right;border-bottom:1px solid var(--border);">المدة</th>
                        <th style="padding:9px 12px;color:var(--text-3);font-weight:700;text-align:right;border-bottom:1px solid var(--border);">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($drivingSessions as $session)
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:9px 12px;color:var(--text-1);font-weight:600;" dir="ltr">{{ \Carbon\Carbon::parse($session->session_date)->format('Y-m-d') }}</td>
                    <td style="padding:9px 12px;color:var(--text-2);">{{ $session->trainer->name ?? '—' }}</td>
                    <td style="padding:9px 12px;color:var(--text-2);">{{ $session->duration_minutes ?? 60 }} د</td>
                    <td style="padding:9px 12px;">
                        @php $sc = $session->status ?? 'مكتمل'; @endphp
                        <span class="s-badge {{ $sc === 'مكتمل' ? 's-badge-success' : ($sc === 'ملغى' ? 's-badge-danger' : 's-badge-accent') }}">
                            {{ $sc }}
                        </span>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- أزرار الإجراءات --}}
    <div class="fade-up" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
        <a href="{{ route('student.exam.history') }}"
           style="background:var(--surface);border:1px solid var(--border);border-radius:var(--r-sm);padding:16px 12px;text-align:center;text-decoration:none;transition:var(--tr);display:block;">
            <i class="fas fa-history" style="font-size:20px;color:var(--warn);display:block;margin-bottom:6px;"></i>
            <span style="font-size:12px;font-weight:700;color:var(--text-1);">سجل النتائج</span>
        </a>
        <a href="{{ route('student.exam') }}"
           style="background:var(--accent-lt);border:1px solid rgba(79,110,247,.2);border-radius:var(--r-sm);padding:16px 12px;text-align:center;text-decoration:none;transition:var(--tr);display:block;">
            <i class="fas fa-play" style="font-size:20px;color:var(--accent);display:block;margin-bottom:6px;"></i>
            <span style="font-size:12px;font-weight:700;color:var(--accent);">بدء امتحان</span>
        </a>
    </div>

    {{-- زر تصفير التقدم --}}
    <div class="fade-up" style="margin-bottom:14px;">
        <button onclick="resetStudyFull()"
                style="width:100%;padding:13px;background:var(--warn-lt);border:1px solid rgba(245,158,11,.2);border-radius:var(--r-sm);color:var(--warn);font-family:'Cairo',sans-serif;font-size:13px;font-weight:700;cursor:pointer;transition:var(--tr);display:flex;align-items:center;justify-content:center;gap:7px;">
            <i class="fas fa-redo-alt"></i> تصفير التقدم الدراسي
        </button>
    </div>

    {{-- زر تسجيل الخروج --}}
    <div class="fade-up" style="margin-bottom:20px;">
        <form action="{{ route('student.logout') }}" method="POST" id="studentLogoutForm">
            @csrf
            <button type="button" onclick="studentLogout()"
                    style="width:100%;padding:13px;background:var(--danger-lt);border:1px solid rgba(239,68,68,.15);border-radius:var(--r-sm);color:var(--danger);font-family:'Cairo',sans-serif;font-size:13px;font-weight:700;cursor:pointer;transition:var(--tr);display:flex;align-items:center;justify-content:center;gap:7px;">
                <i class="fas fa-power-off"></i> تسجيل الخروج
            </button>
        </form>
    </div>

</div>

<form id="reset-form" action="{{ route('student.reset_study') }}" method="POST" style="display:none;">@csrf</form>
@endsection

@section('scripts')
<script>
// Load Dexie from CDN with fallback
function loadScript(src, callback) {
    const s = document.createElement('script');
    s.src = src;
    s.onload = callback;
    s.onerror = callback; // proceed even if fails
    document.head.appendChild(s);
}

async function studentLogout() {
    // Clear storage immediately (doesn't need Dexie)
    try { localStorage.clear(); } catch(e) {}
    try { sessionStorage.clear(); } catch(e) {}
    
    // Try to clear Dexie DB
    async function clearDexie() {
        if (typeof Dexie === 'undefined') return;
        try {
            const db = new Dexie("MasarOfflineDB");
            db.version(11).stores({
                wrong_questions: "q_id, count",
                questions_progress: "q_id, is_viewed",
                exam_results: "++id, score, status"
            });
            await db.open();
            await Promise.all([
                db.wrong_questions.clear(),
                db.questions_progress.clear(),
                db.exam_results.clear()
            ]);
            await db.close();
        } catch (e) {
            // Try to delete the whole DB as fallback
            try { await Dexie.delete("MasarOfflineDB"); } catch(e2) {}
        }
    }

    // Also clear via IndexedDB directly as backup
    async function clearIDB() {
        try {
            if ('indexedDB' in window) {
                const dbs = await window.indexedDB.databases();
                for (const db of dbs) {
                    window.indexedDB.deleteDatabase(db.name);
                }
            }
        } catch(e) {}
    }

    await Promise.allSettled([clearDexie(), clearIDB()]);
    document.getElementById('studentLogoutForm').submit();
}

async function resetStudyFull() {
    if (!confirm('سيتم حذف كل تقدمك (الأسئلة المشاهدة، الأخطاء، والنتائج المحلية). هل أنت متأكد؟')) return;
    try {
        const db = new Dexie("MasarOfflineDB");
        db.version(11).stores({
            wrong_questions: "q_id, count",
            questions_progress: "q_id, is_viewed",
            exam_results: "++id, score, status"
        });
        await db.open();
        await Promise.all([
            db.wrong_questions.clear(),
            db.questions_progress.clear(),
            db.exam_results.clear()
        ]);
        localStorage.clear();
        sessionStorage.clear();
        document.getElementById('reset-form').submit();
    } catch (e) {
        document.getElementById('reset-form').submit();
    }
}
</script>
@endsection
