@extends('admin.layouts.app')
@section('title', 'Tổng quan Quản trị')

@section('content')
    <div class="container-fluid">
        <h1>Tổng quan Quản trị</h1>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Quản lý Địa điểm</h5>
                        <p>Thêm, sửa, xóa các điểm tham quan.</p>
                       
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Quản lý Dịch vụ</h5>
                        <p>Quản lý khách sạn, tour, phương tiện.</p>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Kiểm duyệt Đánh giá</h5>
                        <p>Xem và duyệt đánh giá từ người dùng.</p>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection