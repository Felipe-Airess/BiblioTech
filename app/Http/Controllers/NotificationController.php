<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifiable = Auth::guard('membro')->check() ? Auth::guard('membro')->user() : Auth::user();
        if (!$notifiable) return response()->json(['data' => []]);

        $notifications = DB::table('notifications')
            ->where('notifiable_type', $notifiable::class)
            ->where('notifiable_id', $notifiable->getAuthIdentifier())
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->map(fn($n) => [
                'id' => $n->id,
                'data' => json_decode($n->data, true),
                'read_at' => $n->read_at,
                'created_at' => $n->created_at,
            ]);

        return response()->json(['data' => $notifications]);
    }

    public function markAllRead(Request $request)
    {
        $notifiable = Auth::guard('membro')->check() ? Auth::guard('membro')->user() : Auth::user();
        if ($notifiable) {
            DB::table('notifications')
                ->where('notifiable_type', $notifiable::class)
                ->where('notifiable_id', $notifiable->getAuthIdentifier())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }
        return response()->json(['ok' => true]);
    }
}
