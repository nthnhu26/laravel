@extends('admin.layouts.app')

@section('title', 'Quản lý Tiện ích - Ba Động Tourism')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Quản lý Tiện ích</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="main-content">
        <div class="content-header">
            <h2>Quản lý Tiện ích</h2>
            <a href="{{ route('admin.amenities.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Thêm Tiện ích
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="amenities-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên</th>
                                <th>Icon</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($amenities as $amenity)
                                <tr>
                                    <td>{{ $amenity->amenity_id }}</td>
                                    <td>{{ $amenity->getTranslation('name', 'vi') }}</td>
                                    <td><i class="fas fa-{{ $amenity->icon ?? 'check-circle' }}"></i></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ route('admin.amenities.edit', $amenity) }}"><i class="fas fa-edit me-2"></i> Sửa</a></li>
                                                <li><a class="dropdown-item delete-btn" href="#" data-id="{{ $amenity->amenity_id }}" data-url="{{ route('admin.amenities.destroy', $amenity) }}"><i class="fas fa-trash me-2"></i> Xóa</a></li>
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

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#amenities-table').DataTable({
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
                                Swal.fire('Đã xóa!', 'Tiện ích đã được xóa.', 'success');
                            },
                            error: function() {
                                Swal.fire('Lỗi!', 'Không thể xóa tiện ích.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection