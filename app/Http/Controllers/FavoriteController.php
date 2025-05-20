<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'entity_type' => 'required|in:attraction,event',
            'entity_id' => 'required|integer',
        ]);

        $user = Auth::user();
        $entityType = $request->entity_type;
        $entityId = $request->entity_id;

        $favorite = Favorite::where('user_id', $user->id)
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
            ]);
            return response()->json(['status' => 'added']);
        }
    }
}