<?php
namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Student;
use App\Models\Question;
use App\Models\ActivityLog;
use App\Models\SubscriptionPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /* ══════════════════════════════════════
       DASHBOARD
    ══════════════════════════════════════ */
    public function dashboard()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('school.dashboard');
        }

        $stats = [
            'schools_total'   => School::count(),
            'schools_active'  => School::where('is_active', true)->count(),
            'students_total'  => Student::count(),
            'students_month'  => Student::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'studying'        => Student::where('status', 'يدرس')->count(),
            'passed'          => Student::where('status', 'ناجح')->count(),
            'failed'          => Student::where('status', 'راسب')->count(),
            'stopped'         => Student::where('status', 'موقوف')->count(),
            'questions_total' => Question::count(),
            // إيرادات النظام من اشتراكات المدارس (ليس مالية الطلاب)
            'subscription_revenue' => SubscriptionPayment::sum('amount'),
        ];

        // Alerts
        $criticalCount = School::where('is_active', true)
            ->whereDate('subscription_end', '<=', now()->addDays(7))->count();
        $warningCount = School::where('is_active', true)
            ->whereDate('subscription_end', '>', now()->addDays(7))
            ->whereDate('subscription_end', '<=', now()->addDays(30))->count();

        $latestSchools = School::withCount('students')->latest()->take(8)->get();
        $topSchools    = School::withCount('students')->orderByDesc('students_count')->take(5)->get();
        $recentLogs    = ActivityLog::with('user')->latest()->take(8)->get();

        // Chart data
        $chartSchools = School::withCount('students')->where('is_active', true)->latest()->take(10)->get();
        $chartData = [
            'names'  => $chartSchools->pluck('name')->map(fn($n) => mb_substr($n, 0, 12))->values()->toArray(),
            'counts' => $chartSchools->pluck('students_count')->values()->toArray(),
            'limits' => $chartSchools->pluck('student_limit')->values()->toArray(),
        ];

        return view('admin.dashboard', compact(
            'stats', 'criticalCount', 'warningCount',
            'latestSchools', 'topSchools', 'recentLogs', 'chartData'
        ));
    }

    /* ══════════════════════════════════════
       SUBSCRIPTIONS (مالية النظام فقط)
    ══════════════════════════════════════ */
    public function subscriptions(Request $request)
    {
        $query = School::with(['subscriptionPayments'])->withCount('students');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('school_code', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('plan'))
            $query->where('plan', $request->plan);

        if ($request->filled('status')) {
            match($request->status) {
                'active'   => $query->where('is_active', true)->whereDate('subscription_end', '>', now()),
                'expiring' => $query->where('is_active', true)
                                    ->whereDate('subscription_end', '<=', now()->addDays(30))
                                    ->whereDate('subscription_end', '>', now()),
                'expired'  => $query->whereDate('subscription_end', '<=', now()),
                default    => null,
            };
        }

        $schools = $query->orderBy('subscription_end')->paginate(20);
        $allSchools = School::orderBy('name')->get();

        // Revenue per month (last 6 months)
        $monthlyRevenue = collect();
        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $monthlyRevenue->push([
                'month' => $m->translatedFormat('M'),
                'total' => SubscriptionPayment::whereYear('paid_at', $m->year)
                                               ->whereMonth('paid_at', $m->month)
                                               ->sum('amount'),
            ]);
        }
        $monthlyRevenue = $monthlyRevenue->values();

        // All subscription payments for summary
        $subscriptionPayments = SubscriptionPayment::all();

        $stats = [
            'active'        => School::where('is_active', true)->whereDate('subscription_end', '>', now())->count(),
            'expired'       => School::whereDate('subscription_end', '<=', now())->count(),
            'expiring'      => School::where('is_active', true)
                                     ->whereDate('subscription_end', '<=', now()->addDays(30))
                                     ->whereDate('subscription_end', '>', now())->count(),
            'total_revenue' => SubscriptionPayment::sum('amount'),
        ];

        return view('admin.subscriptions.index', compact(
            'schools', 'allSchools', 'stats', 'monthlyRevenue', 'subscriptionPayments'
        ));
    }

    /**
     * تجديد اشتراك مدرسة + تسجيل الدفعة (اختياري)
     */
    public function renewSubscription(Request $request, School $school)
    {
        $request->validate([
            'subscription_end'    => 'required|date',
            'student_limit'       => 'required|integer|min:1',
            'subscription_amount' => 'nullable|numeric|min:0',
        ]);

        $school->update([
            'subscription_end' => $request->subscription_end,
            'student_limit'    => $request->student_limit,
            'is_active'        => true,
        ]);

        // سجّل الدفعة إن وُجدت
        if ($request->filled('subscription_amount') && $request->subscription_amount > 0) {
            SubscriptionPayment::create([
                'school_id' => $school->id,
                'amount'    => $request->subscription_amount,
                'paid_at'   => now(),
                'note'      => $request->subscription_note ?? 'تجديد اشتراك',
                'recorded_by' => Auth::id(),
            ]);
        }

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'تجديد اشتراك',
            'model_type'  => School::class,
            'model_id'    => $school->id,
            'description' => 'تجديد اشتراك مدرسة ' . $school->name . ' حتى ' . $request->subscription_end,
        ]);

        return back()->with('success', 'تم تجديد اشتراك ' . $school->name . ' بنجاح.');
    }

    /**
     * تسجيل دفعة اشتراك منفصلة
     */
    public function storeSubscriptionPayment(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'amount'    => 'required|numeric|min:1',
            'paid_at'   => 'required|date',
        ]);

        SubscriptionPayment::create([
            'school_id'   => $request->school_id,
            'amount'      => $request->amount,
            'paid_at'     => $request->paid_at,
            'note'        => $request->note,
            'recorded_by' => Auth::id(),
        ]);

        $school = School::find($request->school_id);
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'تسجيل دفعة',
            'model_type'  => School::class,
            'model_id'    => $request->school_id,
            'description' => 'دفعة اشتراك ' . number_format($request->amount) . ' ₪ من ' . ($school->name ?? ''),
        ]);

        return back()->with('success', 'تم تسجيل الدفعة بنجاح.');
    }

    /* ══════════════════════════════════════
       ACTIVITY LOG
    ══════════════════════════════════════ */
    public function logs(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('search'))
            $query->where('description', 'like', '%'.$request->search.'%');
        if ($request->filled('action'))
            $query->where('action', 'like', '%'.$request->action.'%');
        if ($request->filled('model'))
            $query->where('model_type', 'like', '%'.$request->model);
        if ($request->filled('from'))
            $query->whereDate('created_at', '>=', $request->from);
        if ($request->filled('to'))
            $query->whereDate('created_at', '<=', $request->to);

        $logs = $query->latest()->paginate(30);
        return view('admin.logs.index', compact('logs'));
    }

    public function clearOldLogs()
    {
        $deleted = ActivityLog::where('created_at', '<', now()->subDays(90))->delete();
        return back()->with('success', "تم حذف {$deleted} سجل قديم (أقدم من 90 يوماً).");
    }
}
