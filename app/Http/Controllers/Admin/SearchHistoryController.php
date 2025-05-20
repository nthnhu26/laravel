<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\DB;
use App\Exports\SearchHistoryExport;
use Maatwebsite\Excel\Facades\Excel;

class SearchHistoryController extends Controller
{
    public function index(Request $request)
    {
        $searchHistories = SearchHistory::query()
            ->join('users', 'search_histories.user_id', '=', 'users.user_id')
            ->select(
                'search_history.*',
                'users.full_name as user_name',
                'users.email as user_email'
            );

        if ($request->has('query')) {
            $searchHistories->where('search_query', 'like', '%' . $request->query('query') . '%');
        }

        if ($request->has('category')) {
            $searchHistories->where('search_category', $request->query('category'));
        }

        if ($request->has('date_from')) {
            $searchHistories->whereDate('search_date', '>=', $request->query('date_from'));
        }

        if ($request->has('date_to')) {
            $searchHistories->whereDate('search_date', '<=', $request->query('date_to'));
        }

        $searchHistories = $searchHistories->latest('search_date')->paginate(15);
        
        // Lấy danh sách category để filter
        $categories = SearchHistory::select('search_category')
            ->distinct()
            ->whereNotNull('search_category')
            ->pluck('search_category');

        return view('admin.search-history.index', compact('searchHistories', 'categories'));
    }

    public function destroy($id)
    {
        $searchHistory = SearchHistory::findOrFail($id);
        $searchHistory->delete();

        return redirect()->route('admin.search-history.index')
            ->with('success', 'Đã xóa lịch sử tìm kiếm thành công');
    }

    public function export() 
    {
        return Excel::download(new SearchHistoryExport, 'search-history-' . date('Y-m-d') . '.xlsx');
    }
}