<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\School;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
   
    /**
     * لوحة تحكم مدير المدرسة
     */
    public function schoolDashboard()
    {
        if (!in_array(Auth::user()->role, ['school_admin', 'admin'])) {
            return redirect()->route('login');
        }

        $school = Auth::user()->school;

        if (!$school) {
            return redirect()->route('login')->with('error', 'لا توجد مدرسة مرتبطة بحسابك.');
        }

        return view('school_admin.dashboard', compact('school'));
    }
}
