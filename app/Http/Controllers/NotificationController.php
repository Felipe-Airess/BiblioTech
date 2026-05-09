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
        if (!$notifiable) {
            return $request->expectsJson()
                ? response()->json(['data' => []])
                : redirect()->route('login');
        }

        $notifications = $this->notificationsFor($notifiable, 80);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $notifications->map(fn ($notification) => [
                    'id' => $notification->id,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at,
                ]),
            ]);
        }

        $unreadCount = $notifications->whereNull('read_at')->count();
        $readCount = $notifications->whereNotNull('read_at')->count();
        $typeCounts = $notifications
            ->groupBy(fn ($notification) => $notification->meta['grupo'])
            ->map->count();

        return view('notifications.index', compact('notifications', 'unreadCount', 'readCount', 'typeCounts'));
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

    private function notificationsFor($notifiable, int $limit)
    {
        return DB::table('notifications')
            ->where('notifiable_type', $notifiable::class)
            ->where('notifiable_id', $notifiable->getAuthIdentifier())
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(function ($notification) {
                $notification->data = json_decode($notification->data, true) ?: [];
                $notification->meta = $this->metaForType($notification->data['type'] ?? 'mensagem');

                return $notification;
            });
    }

    private function metaForType(string $type): array
    {
        if (str_contains($type, 'rejeitado')) {
            return ['grupo' => 'alertas', 'label' => 'Alerta', 'icon' => 'ph-warning-circle', 'classes' => 'border-red-200 bg-red-50 text-red-700 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-300'];
        }

        if (str_contains($type, 'devolucao')) {
            return ['grupo' => 'devolucoes', 'label' => 'Devolução', 'icon' => 'ph-arrow-u-up-left', 'classes' => 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300'];
        }

        if (str_contains($type, 'emprestimo')) {
            return ['grupo' => 'emprestimos', 'label' => 'Empréstimo', 'icon' => 'ph-handshake', 'classes' => 'border-blue-200 bg-blue-50 text-blue-800 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300'];
        }

        return ['grupo' => 'mensagens', 'label' => 'Mensagem', 'icon' => 'ph-chat-circle-text', 'classes' => 'border-slate-200 bg-slate-50 text-slate-700 dark:border-white/10 dark:bg-white/5 dark:text-slate-300'];
    }
}
