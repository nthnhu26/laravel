@extends('admin.layouts.app')

@section('title', 'Quản lý sự kiện')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý sự kiện</h1>
        <div>
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Thêm sự kiện mới
            </a>
            <div class="btn-group ms-2">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-cog me-2"></i> Thao tác
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.events.update-all-statuses') }}">
                            <i class="fas fa-sync me-2"></i> Cập nhật tất cả trạng thái
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.events.export') }}">
                            <i class="fas fa-file-export me-2"></i> Xuất dữ liệu (CSV)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.events.statistics') }}">
                            <i class="fas fa-chart-bar me-2"></i> Thống kê sự kiện
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tìm kiếm và lọc</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.events.index') }}" method="GET" id="filterForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Tiêu đề, mô tả, địa điểm...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Sắp diễn ra</option>
                            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Đang diễn ra</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã kết thúc</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="place_id" class="form-label">Địa điểm liên quan</label>
                        <select class="form-select" id="place_id" name="place_id">
                            <option value="">Tất cả địa điểm</option>
                            @foreach($places as $place)
                                <option value="{{ $place->place_id }}" {{ request('place_id') == $place->place_id ? 'selected' : '' }}>
                                    {{ $place->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="date_range" class="form-label">Khoảng thời gian</label>
                        <input type="text" class="form-control" id="date_range" name="date_range" value="{{ request('date_range') }}" placeholder="DD/MM/YYYY - DD/MM/YYYY">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="sort_by" class="form-label">Sắp xếp theo</label>
                        <select class="form-select" id="sort_by" name="sort_by">
                            <option value="start_date" {{ request('sort_by', 'start_date') == 'start_date' ? 'selected' : '' }}>Thời gian bắt đầu</option>
                            <option value="end_date" {{ request('sort_by') == 'end_date' ? 'selected' : '' }}>Thời gian kết thúc</option>
                            <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Tiêu đề</option>
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Ngày tạo</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="sort_direction" class="form-label">Thứ tự</label>
                        <select class="form-select" id="sort_direction" name="sort_direction">
                            <option value="desc" {{ request('sort_direction', 'desc') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                            <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end mb-3">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-2"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo me-2"></i> Đặt lại
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách sự kiện</h6>
            <div>
                <span class="badge bg-primary">{{ $events->total() }} sự kiện</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tiêu đề</th>
                            <th>Địa điểm</th>
                            <th>Thời gian bắt đầu</th>
                            <th>Thời gian kết thúc</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                            <tr>
                                <td>{{ $event->event_id }}</td>
                                <td>{{ $event->title }}</td>
                                <td>
                                    @if($event->place)
                                        <a href="{{ route('admin.places.edit', $event->place->place_id) }}">
                                            {{ $event->place->name }}
                                        </a>
                                    @else
                                        {{ $event->location ?: 'Chưa cập nhật' }}
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge 
                                        @if($event->status == 'ongoing') bg-success 
                                        @elseif($event->status == 'upcoming') bg-primary 
                                        @else bg-secondary @endif">
                                        @if($event->status == 'ongoing') Đang diễn ra
                                        @elseif($event->status == 'upcoming') Sắp diễn ra
                                        @else Đã kết thúc @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.events.show', $event->event_id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.events.edit', $event->event_id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $event->event_id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Modal xóa -->
                                    <div class="modal fade" id="deleteModal{{ $event->event_id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $event->event_id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $event->event_id }}">Xác nhận xóa</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Bạn có chắc chắn muốn xóa sự kiện <strong>{{ $event->title }}</strong> không?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                    <form action="{{ route('admin.events.destroy', $event->event_id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Xóa</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Datepicker cho khoảng thời gian
        $('#date_range').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY',
                separator: ' - ',
                applyLabel: 'Áp dụng',
                cancelLabel: 'Hủy',
                fromLabel: 'Từ',
                toLabel: 'Đến',
                customRangeLabel: 'Tùy chỉnh',
                weekLabel: 'T',
                daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                firstDay: 1
            },
            autoUpdateInput: false,
            ranges: {
                'Hôm nay': [moment(), moment()],
                'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 ngày qua': [moment().subtract(6, 'days'), moment()],
                '30 ngày qua': [moment().subtract(29, 'days'), moment()],
                'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });

        $('#date_range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });

        $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
</script>
@endpush
