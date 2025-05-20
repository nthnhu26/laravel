@extends('admin.layouts.app')

@section('title', 'Quản lý Khách sạn - Ba Động Tourism')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Quản lý Khách sạn</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="main-content">
        <div class="content-header">
            <h2>Quản lý Khách sạn</h2>
            <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Thêm Khách sạn
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="hotels-table">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Tên</th>
                                <th width="15%">Loại</th>
                                <th width="15%">Ngôn ngữ</th>
                                <th width="10%">Trạng thái</th>
                                <th width="30%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hotels as $hotel)
                                <tr>
                                    <td>{{ $hotel->hotel_id }}</td>
                                    <td>{{ $hotel->getTranslation('name', 'vi') }}</td>
                                    <td>{{ ucfirst($hotel->type) }}</td>
                                    <td>
                                        @if($hotel->hasTranslation('name', 'vi'))
                                            <div class="language-indicator" title="Tiếng Việt">
                                                <img src="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/flags/4x3/vn.svg" class="language-flag" alt="Vietnamese">
                                            </div>
                                        @else
                                            <div class="add-language" title="Thêm Tiếng Việt" data-hotel-id="{{ $hotel->hotel_id }}" data-lang="vi">
                                                <i class="fas fa-plus"></i>
                                            </div>
                                        @endif
                                        @if($hotel->hasTranslation('name', 'en'))
                                            <div class="language-indicator" title="English">
                                                <img src="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/flags/4x3/us.svg" class="language-flag" alt="English">
                                            </div>
                                        @else
                                            <div class="add-language" title="Thêm Tiếng Anh" data-hotel-id="{{ $hotel->hotel_id }}" data-lang="en">
                                                <i class="fas fa-plus"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td><span class="status-badge status-{{ $hotel->status }}">{{ ucfirst($hotel->status) }}</span></td>
                                    <td>
                                        <div class="dropdown table-actions-dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ route('admin.hotels.edit', $hotel) }}"><i class="fas fa-edit me-2"></i> Sửa</a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.rooms.create', ['hotel_id' => $hotel->hotel_id]) }}"><i class="fas fa-plus me-2"></i> Thêm phòng</a></li>
                                                <li><a class="dropdown-item delete-btn" href="#" data-id="{{ $hotel->hotel_id }}" data-url="{{ route('admin.hotels.destroy', $hotel) }}"><i class="fas fa-trash me-2"></i> Xóa</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .language-indicator {
            display: inline-flex;
            margin: 0 2px;
        }
        .language-flag {
            width: 20px;
            height: 15px;
            object-fit: cover;
        }
        .add-language {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 15px;
            background-color: #f1f1f1;
            border: 1px dashed #aaa;
            color: #666;
            font-size: 10px;
            cursor: pointer;
            margin: 0 2px;
        }
        .add-language:hover {
            background-color: #e1e1e1;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#hotels-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/vi.json'
                },
                pageLength: 10,
                responsive: true
            });

            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                const url = $(this).data('url');
                Swal.fire({
                    title: 'Bạn có chắc không?',
                    text: "Hành động này không thể hoàn tác!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Vâng, xóa nó!',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function() {
                                location.reload();
                                Swal.fire('Đã xóa!', 'Khách sạn đã được xóa.', 'success');
                            },
                            error: function() {
                                Swal.fire('Lỗi!', 'Không thể xóa khách sạn.', 'error');
                            }
                        });
                    }
                });
            });

            // Handle add-language click (placeholder for future implementation)
            $(document).on('click', '.add-language', function() {
                const hotelId = $(this).data('hotel-id');
                const lang = $(this).data('lang');
                Swal.fire({
                    title: 'Thêm ngôn ngữ',
                    text: `Bạn muốn thêm ngôn ngữ ${lang === 'vi' ? 'Tiếng Việt' : 'Tiếng Anh'} cho khách sạn #${hotelId}?`,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Thêm',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to edit page or handle via AJAX
                        window.location.href = '{{ url('admin/hotels') }}/' + hotelId + '/edit';
                    }
                });
            });
        });
    </script>
@endsection