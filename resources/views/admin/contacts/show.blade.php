<!-- BLADE VIEW: resources/views/admin/contacts/show.blade.php -->
@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Chi tiết liên hệ</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin liên hệ</h6>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-2 font-weight-bold">Tên:</div>
                        <div class="col-md-10">{{ $contact->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-2 font-weight-bold">Email:</div>
                        <div class="col-md-10">{{ $contact->email }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-2 font-weight-bold">Ngày gửi:</div>
                        <div class="col-md-10">{{ $contact->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-2 font-weight-bold">Trạng thái:</div>
                        <div class="col-md-10">
                            @if($contact->status == 'new')
                                <span class="badge bg-danger text-white">Mới</span>
                            @elseif($contact->status == 'read')
                                <span class="badge bg-warning text-dark">Đã đọc</span>
                            @else
                                <span class="badge bg-success text-white">Đã trả lời</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-2 font-weight-bold">Nội dung:</div>
                        <div class="col-md-10">
                            <div class="p-3 bg-light rounded">
                                {{ $contact->message }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form trả lời -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Phản hồi</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.contacts.reply', $contact->contact_id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="reply_message">Nội dung phản hồi:</label>
                            <textarea class="form-control" id="reply_message" name="reply_message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="fas fa-paper-plane"></i> Gửi phản hồi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection