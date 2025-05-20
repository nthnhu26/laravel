@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quản lý lịch sử tìm kiếm</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form action="{{ route('admin.search-history.index') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Từ khóa</label>
                                    <input type="text" name="query" class="form-control" value="{{ request('query') }}" placeholder="Tìm từ khóa">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Danh mục</label>
                                    <select name="category" class="form-select">
                                        <option value="">Tất cả</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Từ ngày</label>
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Đến ngày</label>
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="btn-group w-100">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Lọc
                                        </button>
                                        <a href="{{ route('admin.search-history.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-redo"></i> Đặt lại
                                        </a>
                                        <a href="{{ route('admin.search-history.export') }}" class="btn btn-success">
                                            <i class="fas fa-file-excel"></i> Xuất Excel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table id="searchHistoryTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">STT</th>
                                    <th>Người dùng</th>
                                    <th>Từ khóa tìm kiếm</th>
                                    <th>Danh mục</th>
                                    <th>Thời gian</th>
                                    <th>Số kết quả</th>
                                    <th>Kết quả chọn</th>
                                    <th width="10%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($searchHistories as $key => $history)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        {{ $history->user_name ?? 'Không xác định' }}<br>
                                        <small class="text-muted">{{ $history->user_email ?? '' }}</small>
                                    </td>
                                    <td>{{ $history->search_query }}</td>
                                    <td>{{ $history->search_category ?? 'Chung' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($history->search_date)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $history->results_count ?? 0 }}</td>
                                    <td>
                                        @if($history->selected_result_id)
                                            {{ $history->selected_result_type }} #{{ $history->selected_result_id }}
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.search-history.destroy', $history->search_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $searchHistories->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    initDataTable('searchHistoryTable');
</script>
@endsection