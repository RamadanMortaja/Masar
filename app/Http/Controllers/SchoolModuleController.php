<?php
namespace App\Http\Controllers;

use App\Models\Trainer;
use App\Models\Vehicle;
use App\Models\DrivingSession;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SchoolModuleController extends Controller
{
    private function schoolId(): int
    {
        return Auth::user()->school_id;
    }

    // ============================================================
    // TRAINERS
    // ============================================================

    public function trainersIndex(Request $request)
    {
        $sid   = $this->schoolId();
        $query = Trainer::where('school_id', $sid);

        if ($request->filled('search'))
            $query->where('name', 'like', '%'.$request->search.'%');
        if ($request->filled('status'))
            $query->where('status', $request->status);

        $trainers = $query->latest()->get();
        $stats = [
            'total'    => Trainer::where('school_id', $sid)->count(),
            'active'   => Trainer::where('school_id', $sid)->where('status','نشط')->count(),
            'expiring' => Trainer::where('school_id', $sid)
                              ->whereNotNull('license_expiry')
                              ->whereDate('license_expiry', '<=', now()->addDays(30))
                              ->count(),
        ];

        return view('school_admin.trainers.index', compact('trainers', 'stats'));
    }

    public function trainersStore(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $data = $request->only([
            'name','phone','identity_number','license_number',
            'license_expiry','specialization','status','notes',
        ]);
        $data['school_id'] = $this->schoolId();

        if ($request->hasFile('image'))
            $data['image'] = $request->file('image')->store('trainers', 'public');

        Trainer::create($data);
        return back()->with('success', 'تم إضافة المدرب بنجاح.');
    }

    public function trainersUpdate(Request $request, Trainer $trainer)
    {
        abort_if($trainer->school_id !== $this->schoolId(), 403);

        $data = $request->only([
            'name','phone','identity_number','license_number',
            'license_expiry','specialization','status','notes',
        ]);

        if ($request->hasFile('image')) {
            if ($trainer->image) Storage::disk('public')->delete($trainer->image);
            $data['image'] = $request->file('image')->store('trainers', 'public');
        }

        $trainer->update($data);
        return back()->with('success', 'تم تحديث بيانات المدرب.');
    }

    public function trainersDestroy(Trainer $trainer)
    {
        abort_if($trainer->school_id !== $this->schoolId(), 403);
        if ($trainer->image) Storage::disk('public')->delete($trainer->image);
        $trainer->delete();
        return back()->with('success', 'تم حذف المدرب.');
    }

    // ============================================================
    // VEHICLES
    // ============================================================

    public function vehiclesIndex(Request $request)
    {
        $sid   = $this->schoolId();
        $query = Vehicle::where('school_id', $sid);

        if ($request->filled('status'))
            $query->where('status', $request->status);
        if ($request->filled('type'))
            $query->where('vehicle_type', $request->type);

        $vehicles = $query->latest()->get();
        $stats = [
            'total'       => Vehicle::where('school_id', $sid)->count(),
            'active'      => Vehicle::where('school_id', $sid)->where('status','نشط')->count(),
            'maintenance' => Vehicle::where('school_id', $sid)->where('status','صيانة')->count(),
            'alerts'      => Vehicle::where('school_id', $sid)
                                ->where(function($q){
                                    $q->whereDate('insurance_expiry','<=', now()->addDays(14))
                                      ->orWhereDate('license_expiry','<=', now()->addDays(14))
                                      ->orWhereDate('next_maintenance','<=', now()->addDays(7));
                                })->count(),
        ];

        return view('school_admin.vehicles.index', compact('vehicles', 'stats'));
    }

    public function vehiclesStore(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:20',
        ]);

        $data = $request->only([
            'plate_number','brand','model','year','color',
            'gear_type','vehicle_type','status',
            'insurance_expiry','license_expiry',
            'last_maintenance','next_maintenance','km_reading','notes',
        ]);
        $data['school_id'] = $this->schoolId();

        Vehicle::create($data);
        return back()->with('success', 'تم إضافة المركبة بنجاح.');
    }

    public function vehiclesUpdate(Request $request, Vehicle $vehicle)
    {
        abort_if($vehicle->school_id !== $this->schoolId(), 403);
        $vehicle->update($request->only([
            'plate_number','brand','model','year','color',
            'gear_type','vehicle_type','status',
            'insurance_expiry','license_expiry',
            'last_maintenance','next_maintenance','km_reading','notes',
        ]));
        return back()->with('success', 'تم تحديث بيانات المركبة.');
    }

    public function vehiclesDestroy(Vehicle $vehicle)
    {
        abort_if($vehicle->school_id !== $this->schoolId(), 403);
        $vehicle->delete();
        return back()->with('success', 'تم حذف المركبة.');
    }

    // ============================================================
    // SESSIONS (جدول الحصص)
    // ============================================================

    public function sessionsIndex(Request $request)
    {
        $sid   = $this->schoolId();
        $query = DrivingSession::with(['student','trainer','vehicle'])
                    ->where('school_id', $sid);

        if ($request->filled('date'))
            $query->whereDate('session_date', $request->date);
        if ($request->filled('trainer_id'))
            $query->where('trainer_id', $request->trainer_id);
        if ($request->filled('status'))
            $query->where('status', $request->status);

        // افتراضي: اليوم والمستقبل
        if (!$request->filled('date') && !$request->filled('all'))
            $query->whereDate('session_date', '>=', now()->toDateString());

        $sessions  = $query->orderBy('session_date')->orderBy('start_time')->paginate(20);
        $trainers  = Trainer::where('school_id', $sid)->where('status','نشط')->get();
        $students  = Student::where('school_id', $sid)->where('status','يدرس')->get();
        $vehicles  = Vehicle::where('school_id', $sid)->where('status','نشط')->get();

        $todayCount = DrivingSession::where('school_id',$sid)->whereDate('session_date', today())->count();
        $weekCount  = DrivingSession::where('school_id',$sid)
                          ->whereBetween('session_date',[now()->startOfWeek(), now()->endOfWeek()])->count();

        return view('school_admin.sessions.index', compact(
            'sessions','trainers','students','vehicles','todayCount','weekCount'
        ));
    }

    public function sessionsStore(Request $request)
    {
        $request->validate([
            'student_id'   => 'required|exists:students,id',
            'trainer_id'   => 'required|exists:trainers,id',
            'session_date' => 'required|date',
            'start_time'   => 'required',
        ]);

        DrivingSession::create([
            'school_id'        => $this->schoolId(),
            'student_id'       => $request->student_id,
            'trainer_id'       => $request->trainer_id,
            'vehicle_id'       => $request->vehicle_id ?: null,
            'session_date'     => $request->session_date,
            'start_time'       => $request->start_time,
            'end_time'         => $request->end_time,
            'duration_minutes' => $request->duration_minutes ?? 60,
            'status'           => $request->status ?? 'مجدولة',
            'location'         => $request->location,
            'notes'            => $request->notes,
        ]);

        return back()->with('success', 'تم جدولة الحصة بنجاح.');
    }

    public function sessionsUpdate(Request $request, DrivingSession $session)
    {
        abort_if($session->school_id !== $this->schoolId(), 403);
        $session->update($request->only([
            'student_id','trainer_id','vehicle_id','session_date',
            'start_time','end_time','duration_minutes','status','location','rating','notes',
        ]));
        return back()->with('success', 'تم تحديث الحصة.');
    }

    public function sessionsDestroy(DrivingSession $session)
    {
        abort_if($session->school_id !== $this->schoolId(), 403);
        $session->delete();
        return back()->with('success', 'تم حذف الحصة.');
    }

    // ============================================================
    // PAYMENTS (سندات القبض)
    // ============================================================

    public function paymentsIndex(Request $request)
    {
        $sid   = $this->schoolId();
        $query = Payment::with('student')->where('school_id', $sid);

        if ($request->filled('student_id'))
            $query->where('student_id', $request->student_id);
        if ($request->filled('from'))
            $query->whereDate('payment_date', '>=', $request->from);
        if ($request->filled('to'))
            $query->whereDate('payment_date', '<=', $request->to);

        $payments  = $query->latest('payment_date')->paginate(20);
        $students  = Student::where('school_id', $sid)->orderBy('name')->get();

        $totalCollected = Payment::where('school_id', $sid)->sum('amount');
        $totalOwed      = Student::where('school_id', $sid)
                              ->selectRaw('SUM(total_amount - paid_amount) as owed')
                              ->value('owed') ?? 0;
        $monthCollected = Payment::where('school_id', $sid)
                              ->whereMonth('payment_date', now()->month)
                              ->sum('amount');

        return view('school_admin.payments.index', compact(
            'payments','students','totalCollected','totalOwed','monthCollected'
        ));
    }

    public function paymentsStore(Request $request)
    {
        $request->validate([
            'student_id'   => 'required|exists:students,id',
            'amount'       => 'required|numeric|min:1',
            'payment_date' => 'required|date',
        ]);

        $sid    = $this->schoolId();
        $amount = $request->amount;

        DB::transaction(function() use ($request, $sid, $amount) {
            Payment::create([
                'school_id'      => $sid,
                'student_id'     => $request->student_id,
                'receipt_number' => Payment::generateReceiptNumber($sid),
                'amount'         => $amount,
                'payment_method' => $request->payment_method ?? 'نقدي',
                'payment_date'   => $request->payment_date,
                'description'    => $request->description,
                'received_by'    => Auth::user()->name,
            ]);

            // تحديث paid_amount في جدول الطلاب
            Student::where('id', $request->student_id)
                   ->increment('paid_amount', $amount);
        });

        return back()->with('success', 'تم تسجيل الدفعة وتحديث رصيد الطالب.');
    }

    public function paymentsDestroy(Payment $payment)
    {
        abort_if($payment->school_id !== $this->schoolId(), 403);

        DB::transaction(function() use ($payment) {
            // رد المبلغ من paid_amount
            Student::where('id', $payment->student_id)
                   ->decrement('paid_amount', $payment->amount);
            $payment->delete();
        });

        return back()->with('success', 'تم حذف السند وتصحيح رصيد الطالب.');
    }

    // ============================================================
    // REPORTS
    // ============================================================

    public function reportsIndex()
    {
        $sid = $this->schoolId();

        $summary = [
            'students_count'   => Student::where('school_id', $sid)->count(),
            'studying'         => Student::where('school_id', $sid)->where('status','يدرس')->count(),
            'passed'           => Student::where('school_id', $sid)->where('status','ناجح')->count(),
            'total_revenue'    => Student::where('school_id', $sid)->sum('total_amount'),
            'collected'        => Student::where('school_id', $sid)->sum('paid_amount'),
            'trainers_count'   => Trainer::where('school_id', $sid)->count(),
            'vehicles_count'   => Vehicle::where('school_id', $sid)->count(),
            'sessions_month'   => DrivingSession::where('school_id', $sid)
                                    ->whereMonth('session_date', now()->month)->count(),
        ];

        $summary['outstanding'] = $summary['total_revenue'] - $summary['collected'];

        $recentPayments = Payment::with('student')
                              ->where('school_id', $sid)
                              ->latest('payment_date')->take(10)->get();

        $topStudents = Student::where('school_id', $sid)
                          ->where('status','يدرس')
                          ->orderByDesc('paid_amount')
                          ->take(5)->get();

        return view('school_admin.reports.index',
            compact('summary','recentPayments','topStudents'));
    }
}
