@extends('layouts.student')

@section('content')

@php
    $payPercent = $totalFees > 0 ? round(($paidAmount / $totalFees) * 100) : 0;
    $isFullyPaid = $payPercent >= 100;
@endphp

{{-- هيدر الصفحة --}}
<div class="page-header">
    <div style="display:flex; align-items:center; justify-content:space-between; position:relative; z-index:2;">
        <a href="{{ route('student.dashboard') }}"
           style="width:34px;height:34px;background:rgba(255,255,255,.15);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;">
            <i class="fas fa-arrow-right fa-sm"></i>
        </a>
        <div style="text-align:center;">
            <div style="font-size:17px;font-weight:800;color:#fff;">الحساب المالي</div>
            <div style="font-size:11px;color:rgba(255,255,255,.65);">متابعة الرسوم والمدفوعات</div>
        </div>
        <div style="width:34px;"></div>
    </div>
</div>

<div style="padding:18px 16px 0; max-width:520px; margin:0 auto;">

    {{-- بطاقة الرصيد الرئيسية --}}
    <div class="s-card fade-up" style="margin-bottom:16px; overflow:visible;">

        {{-- الجزء العلوي - المبلغ المتبقي --}}
        <div style="background:linear-gradient(135deg,#1e3a8a,#4f6ef7);padding:28px 22px 32px;text-align:center;position:relative;overflow:hidden;">
            <div style="position:absolute;top:-30px;left:-30px;width:120px;height:120px;background:rgba(255,255,255,.05);border-radius:50%;"></div>
            <div style="position:absolute;bottom:-40px;right:-20px;width:150px;height:150px;background:rgba(255,255,255,.04);border-radius:50%;"></div>
            <div style="font-size:12px;color:rgba(255,255,255,.65);margin-bottom:8px;position:relative;z-index:2;">
                {{ $isFullyPaid ? '🎉 تم السداد الكامل' : 'المبلغ المتبقي' }}
            </div>
            <div style="font-size:40px;font-weight:900;color:#fff;line-height:1;position:relative;z-index:2;">
                {{ number_format($remainingAmount) }}
                <span style="font-size:18px;opacity:.7;">₪</span>
            </div>
            @if($isFullyPaid)
                <div style="margin-top:10px;background:rgba(16,185,129,.2);border:1px solid rgba(16,185,129,.4);border-radius:20px;display:inline-flex;align-items:center;gap:5px;padding:5px 14px;font-size:12px;color:#6ee7b7;position:relative;z-index:2;">
                    <i class="fas fa-check-circle"></i> اكتمل السداد
                </div>
            @endif
        </div>

        {{-- الجزء السفلي - التفاصيل --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;background:var(--surface);">
            <div style="padding:16px;text-align:center;border-left:1px solid var(--border);">
                <div class="xs" style="color:var(--text-3);margin-bottom:4px;">إجمالي الرسوم</div>
                <div style="font-size:18px;font-weight:800;color:var(--text-1);">{{ number_format($totalFees) }} ₪</div>
            </div>
            <div style="padding:16px;text-align:center;">
                <div class="xs" style="color:var(--text-3);margin-bottom:4px;">إجمالي المدفوع</div>
                <div style="font-size:18px;font-weight:800;color:var(--success);">{{ number_format($paidAmount) }} ₪</div>
            </div>
        </div>
    </div>

    {{-- شريط نسبة السداد --}}
    <div class="s-card fade-up" style="padding:18px 20px;margin-bottom:16px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
            <div style="font-size:13px;font-weight:700;color:var(--text-1);">نسبة السداد</div>
            <div style="font-size:14px;font-weight:800;color:{{ $isFullyPaid ? 'var(--success)' : 'var(--accent)' }};">
                {{ $payPercent }}%
            </div>
        </div>
        <div class="s-progress-wrap" style="height:10px;">
            <div class="s-progress-fill"
                 style="width:{{ $payPercent }}%;background:{{ $isFullyPaid ? 'linear-gradient(90deg,#10b981,#6ee7b7)' : 'linear-gradient(90deg,var(--accent),#818cf8)' }};">
            </div>
        </div>
        <div style="display:flex;justify-content:space-between;margin-top:8px;">
            <span class="xs" style="color:var(--text-3);">0 ₪</span>
            <span class="xs" style="color:var(--text-3);">{{ number_format($totalFees) }} ₪</span>
        </div>
    </div>

    {{-- سجل الدفعات --}}
    @if(count($payments) > 0)
    <div class="s-card fade-up" style="margin-bottom:16px;">
        <div style="padding:16px 18px 10px;border-bottom:1px solid var(--border);">
            <div style="font-size:14px;font-weight:700;color:var(--text-1);display:flex;align-items:center;gap:8px;">
                <div style="width:7px;height:7px;border-radius:50%;background:var(--success);box-shadow:0 0 0 3px var(--success-lt);"></div>
                سجل الدفعات
            </div>
        </div>
        @foreach($payments as $pay)
        <div style="padding:14px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:12px;flex-direction:row-reverse;">
            <div class="s-icon s-icon-green"><i class="fas fa-check"></i></div>
            <div style="flex:1;">
                <div style="font-size:13px;font-weight:700;color:var(--text-1);">دفعة مالية</div>
                <div class="xs" style="color:var(--text-3);">{{ $pay->date ?? '---' }}</div>
            </div>
            <div style="font-size:15px;font-weight:800;color:var(--success);">{{ number_format($pay->amount ?? 0) }} ₪</div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ملاحظة --}}
    <div class="fade-up" style="background:var(--warn-lt);border:1px solid rgba(245,158,11,.2);border-radius:var(--r-sm);padding:14px 16px;display:flex;align-items:flex-start;gap:10px;flex-direction:row-reverse;margin-bottom:16px;">
        <div class="s-icon s-icon-amber" style="width:36px;height:36px;flex-shrink:0;margin-top:2px;">
            <i class="fas fa-info-circle fa-sm"></i>
        </div>
        <div>
            <div style="font-size:13px;font-weight:700;color:var(--warn);margin-bottom:3px;">ملاحظة مالية</div>
            <div class="xs" style="color:var(--text-2);line-height:1.7;">
                يرجى مراجعة إدارة المدرسة في حال وجود أي اختلاف في المبالغ المسجلة أو لتسديد الدفعات المتبقية.
            </div>
        </div>
    </div>

</div>
@endsection
