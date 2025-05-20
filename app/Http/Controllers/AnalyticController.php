<?php
// File: app/Http/Controllers/AnalyticsController.php

namespace App\Http\Controllers;

use App\Models\Analytic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    /**
     * Ghi nhận hành vi người dùng (view/click).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function track(Request $request)
    {
        $user = Auth::user();

        Analytic::create([
            'entity_type' => $request->input('entity_type'),
            'entity_id' => $request->input('entity_id'),
            'user_id' => $user?->id,
            'action_type' => $request->input('action_type', 'view'),
            'ip_address' => $request->ip(),
            'device_type' => $this->getDeviceType($request->userAgent()),
            'country_code' => geoip($request->ip())->iso_code ?? null,
            'city' => geoip($request->ip())->city ?? null,
            'page_url' => $request->input('page_url', url()->current()),
            'session_id' => $request->session()->getId(),
        ]);

        return response()->json(['status' => 'tracked'], 200);
    }

    /**
     * Xác định loại thiết bị từ User-Agent.
     *
     * @param string $userAgent
     * @return string
     */
    private function getDeviceType($userAgent)
    {
        if (preg_match('/mobile/i', $userAgent)) return 'mobile';
        if (preg_match('/tablet/i', $userAgent)) return 'tablet';
        return 'desktop';
    }
}