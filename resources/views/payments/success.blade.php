@extends('layouts.app')

@section('title', 'Thanh toán thành công')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
            <h1 class="card-title mt-3">Thanh toán thành công!</h1>
            <p class="card-text">Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi. Đặt chỗ của bạn đã được xác nhận.</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">Quay về trang chủ</a>
        </div>
    </div>
</div>
@endsection