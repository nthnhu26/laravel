@extends('layouts.app')

@section('title', 'Thanh toán bị hủy')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
            <h1 class="card-title mt-3">Thanh toán bị hủy</h1>
            <p class="card-text">Thanh toán của bạn không thành công hoặc đã bị hủy. Vui lòng thử lại.</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">Quay về trang chủ</a>
        </div>
    </div>
</div>
@endsection