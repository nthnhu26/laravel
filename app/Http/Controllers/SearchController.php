<?php
// File: app/Http/Controllers/SearchController.php

namespace App\Http\Controllers;

use App\Models\SearchHistory;
use App\Models\SearchResult;
use App\Models\Hotel;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use PDOException;

class SearchController extends Controller
{
    /**
     * Xử lý yêu cầu tìm kiếm.
     * Tìm kiếm qua các thực thể: hotel, restaurant, v.v.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'keyword' => 'required|string|max:255',
            'filters' => 'nullable|array',
        ]);

        // Lấy từ khóa và bộ lọc
        $keyword = $request->input('keyword');
        $filters = $request->input('filters', []);
        $user = Auth::user();
        $results = [];

        try {
            // Ghi log dữ liệu đầu vào và thông tin database
            Log::info('Attempting to create SearchHistory', [
                'user_id' => $user?->id,
                'keyword' => $keyword,
                'filters' => $filters,
                'is_guest' => !$user,
                'schema_columns' => Schema::getColumnListing('search_histories'),
                'table_exists' => DB::getSchemaBuilder()->hasTable('search_histories'),
                'database' => DB::getDatabaseName(),
            ]);

            // Kiểm tra bảng có tồn tại không
            if (!DB::getSchemaBuilder()->hasTable('search_histories')) {
                Log::error('Table search_histories does not exist');
                return response()->json(['error' => 'Bảng search_histories không tồn tại'], 500);
            }

            // Kiểm tra schema columns
            $columns = Schema::getColumnListing('search_histories');
            if (empty($columns)) {
                Log::warning('No columns found for search_histories table', [
                    'database' => DB::getDatabaseName(),
                ]);
                return response()->json(['error' => 'Không tìm thấy cột trong bảng search_histories'], 500);
            }

            // Kiểm tra filters có thể mã hóa JSON
            $filtersJson = json_encode($filters);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to encode filters to JSON', [
                    'filters' => $filters,
                    'json_error' => json_last_error_msg(),
                ]);
                return response()->json(['error' => 'Bộ lọc không hợp lệ'], 400);
            }

            // Chuẩn bị dữ liệu cho SearchHistory
            $searchHistoryData = [
                'user_id' => $user?->id,
                'keyword' => $keyword,
                'filters' => $filtersJson,
            ];

            // Chỉ thêm is_guest nếu cột tồn tại
            if (in_array('is_guest', $columns)) {
                $searchHistoryData['is_guest'] = !$user;
            } else {
                Log::warning('Column is_guest does not exist in search_histories');
            }

            // Tạo SearchHistory
            $searchHistory = SearchHistory::create($searchHistoryData);

            // Debug trạng thái model
            Log::debug('SearchHistory model state after creation', [
                'id' => $searchHistory->search_id,
                'exists' => $searchHistory->exists,
                'attributes' => $searchHistory->getAttributes(),
            ]);

            // Kiểm tra xem SearchHistory có được tạo thành công không
            if (!$searchHistory->search_id) {
                Log::error('SearchHistory creation failed: No ID generated', [
                    'user_id' => $user?->id,
                    'keyword' => $keyword,
                    'filters' => $filters,
                    'search_history' => $searchHistory->toArray(),
                ]);
                return response()->json(['error' => 'Không thể lưu lịch sử tìm kiếm'], 500);
            }

            // Ghi log thành công
            Log::info('SearchHistory created successfully', [
                'search_id' => $searchHistory->search_id,
                'data' => $searchHistory->toArray(),
                'exists' => $searchHistory->exists,
            ]);

            // Tìm kiếm khách sạn
            if (empty($filters['type']) || $filters['type'] === 'hotel') {
                $hotels = Hotel::where('name->vi', 'LIKE', "%$keyword%")
                    ->orWhere('name->en', 'LIKE', "%$keyword%")
                    ->when(isset($filters['price_range']), function ($query) use ($filters) {
                        $query->where('price_range', $filters['price_range']);
                    })
                    ->where('status', 'active')
                    ->get();

                $results['hotels'] = $hotels;
                if ($hotels->isNotEmpty()) {
                    $this->saveSearchResults($searchHistory->search_id, 'hotel', $hotels);
                }
            }

            // Tìm kiếm nhà hàng
            if (empty($filters['type']) || $filters['type'] === 'restaurant') {
                $restaurants = Restaurant::where('name->vi', 'LIKE', "%$keyword%")
                    ->orWhere('name->en', 'LIKE', "%$keyword%")
                    ->when(isset($filters['price_category']), function ($query) use ($filters) {
                        $query->where('price_category', $filters['price_category']);
                    })
                    ->where('status', 'active')
                    ->get();

                $results['restaurants'] = $restaurants;
                if ($restaurants->isNotEmpty()) {
                    $this->saveSearchResults($searchHistory->search_id, 'restaurant', $restaurants);
                }
            }

            return response()->json(['results' => $results], 200);
        } catch (PDOException $e) {
            // Ghi log lỗi cơ sở dữ liệu
            Log::error('Database error in SearchController: ' . $e->getMessage(), [
                'user_id' => $user?->id,
                'keyword' => $keyword,
                'filters' => $filters,
                'data' => $searchHistoryData,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Ghi log lỗi khác
            Log::error('Unexpected error in SearchController: ' . $e->getMessage(), [
                'user_id' => $user?->id,
                'keyword' => $keyword,
                'filters' => $filters,
                'data' => $searchHistoryData,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Lưu kết quả tìm kiếm vào bảng search_results.
     *
     * @param int $searchId ID của lịch sử tìm kiếm
     * @param string $entityType Loại thực thể (hotel, restaurant,...)
     * @param \Illuminate\Database\Eloquent\Collection $entities Danh sách thực thể
     * @throws \Exception
     */
    private function saveSearchResults($searchId, $entityType, $entities)
    {
        if (!$searchId) {
            Log::error('Invalid search_id in saveSearchResults', [
                'search_id' => $searchId,
                'entity_type' => $entityType,
            ]);
            throw new \Exception('Invalid search_id');
        }

        foreach ($entities as $entity) {
            // Sử dụng khóa chính phù hợp với entityType
            $entityId = match ($entityType) {
                'hotel' => $entity->hotel_id,
                'restaurant' => $entity->restaurant_id,
                default => $entity->id,
            };

            if (!isset($entityId)) {
                Log::warning('Entity missing ID for type ' . $entityType, [
                    'entity_type' => $entityType,
                    'entity' => $entity->toArray(),
                ]);
                continue;
            }

            try {
                SearchResult::create([
                    'search_id' => $searchId,
                    'entity_type' => $entityType,
                    'entity_id' => $entityId,
                ]);
            } catch (PDOException $e) {
                Log::error('Failed to save SearchResult: ' . $e->getMessage(), [
                    'search_id' => $searchId,
                    'entity_type' => $entityType,
                    'entity_id' => $entityId,
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
    }
}