@extends('layouts.school')

@section('title', 'قريبا!')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="height: 80vh; flex-direction: column;">
    <h1 class="display-4 text-warning mb-3">🚧 صفحة {{ $feature }} تحت الصيانة</h1>
    <p class="lead text-center">نأسف للإزعاج، هذه الميزة ستتوفر قريبًا.</p>
    <a href="{{ route('school.dashboard') }}" class="btn btn-primary mt-3">العودة للوحة التحكم</a>
</div>
@endsection
