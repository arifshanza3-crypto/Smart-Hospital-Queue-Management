<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login');
            }
            
            // ✅ Get notifications based on user role
            $notifications = $this->getNotificationsByRole($user);
            
            $unreadCount = $notifications->whereNull('read_at')->count();

            // ✅ If request expects JSON (AJAX call)
            if (request()->wantsJson()) {
                $formatted = $notifications->map(function($notification) {
                    // ✅ Decode data properly
                    $data = $notification->data;
                    if (is_string($data)) {
                        $data = json_decode($data, true);
                    }
                    if (!is_array($data)) {
                        $data = [];
                    }
                    
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title ?? 'Notification',
                        'message' => $notification->message ?? '',
                        'type' => $notification->type ?? 'general',
                        'data' => $data,
                        'is_read' => $notification->read_at ? true : false,
                        'created_at' => $notification->created_at->toISOString(),
                        'token_number' => $data['token_number'] ?? null
                    ];
                });

                return response()->json([
                    'success' => true,
                    'notifications' => $formatted,
                    'unread_count' => $unreadCount,
                    'total_count' => $notifications->count()
                ]);
            }

            // ✅ For normal page view
            $userRole = $user->role ?? 'user';
            
            // ✅ Check if view exists
            if (!view()->exists('Notification')) {
                Log::error('Notification view not found!');
                return $this->fallbackView($notifications, $unreadCount);
            }
            
            return view('Notification', compact('notifications', 'unreadCount', 'userRole'));
            
        } catch (\Exception $e) {
            Log::error('Notification page error: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            // ✅ Return fallback view instead of error
            return $this->fallbackView(collect([]), 0);
        }
    }

    /**
     * Get notifications based on user role
     */
    private function getNotificationsByRole($user)
    {
        try {
            $role = $user->role ?? 'user';

            $query = Notification::query();

            switch ($role) {
                case 'admin':
                    $query->where('user_id', $user->id)
                        ->orWhereNull('user_id');
                    break;

                case 'staff':
                    $query->where('user_id', $user->id)
                        ->orWhereNull('user_id');
                    break;

                default:
                    $query->where('user_id', $user->id);
                    break;
            }

            return $query->orderBy('created_at', 'desc')->get();
            
        } catch (\Exception $e) {
            Log::error('Error fetching notifications: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * ✅ Fallback view if everything fails
     */
    private function fallbackView($notifications, $unreadCount)
    {
        $user = Auth::user();
        $userRole = $user->role ?? 'user';
        $isAdmin = ($userRole === 'admin');
        
        // ✅ Get latest 10 notifications directly
        try {
            $fallbackNotifications = Notification::where('user_id', $user->id)
                ->orWhereNull('user_id')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();
                
            if ($fallbackNotifications->count() > 0) {
                $notifications = $fallbackNotifications;
                $unreadCount = $notifications->whereNull('read_at')->count();
            }
        } catch (\Exception $e) {
            // Ignore
        }
        
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <title>Notifications - Smart Queue</title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="csrf-token" content="' . csrf_token() . '">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { 
                    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                    background: #0b2e33; 
                    color: #ffffff; 
                    padding: 30px 20px;
                    min-height: 100vh;
                }
                .container { max-width: 900px; margin: 0 auto; }
                .header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 30px;
                    padding-bottom: 20px;
                    border-bottom: 1px solid rgba(255,255,255,0.1);
                    flex-wrap: wrap;
                    gap: 15px;
                }
                .header-left {
                    display: flex;
                    align-items: center;
                    gap: 15px;
                    flex-wrap: wrap;
                }
                .header-left h1 { font-size: 28px; }
                .header-left h1 i { color: #00d4ff; margin-right: 10px; }
                .role-badge {
                    padding: 4px 16px;
                    border-radius: 20px;
                    font-size: 12px;
                    font-weight: 600;
                    background: rgba(0,212,255,0.1);
                    color: #00d4ff;
                    border: 1px solid rgba(0,212,255,0.2);
                }
                .badge {
                    background: #ef4444;
                    padding: 4px 14px;
                    border-radius: 20px;
                    font-size: 14px;
                    font-weight: 600;
                }
                .card {
                    background: rgba(255,255,255,0.05);
                    border-radius: 12px;
                    padding: 16px 20px;
                    margin-bottom: 10px;
                    border-left: 3px solid #00d4ff;
                    transition: all 0.3s ease;
                }
                .card:hover { background: rgba(255,255,255,0.08); }
                .card .title { font-weight: 600; font-size: 16px; color: #ffffff; }
                .card .message { color: rgba(255,255,255,0.7); font-size: 14px; margin-top: 4px; }
                .card .time { color: rgba(255,255,255,0.3); font-size: 12px; margin-top: 6px; }
                .empty {
                    text-align: center;
                    padding: 60px 20px;
                    color: rgba(255,255,255,0.3);
                }
                .empty .icon { font-size: 60px; margin-bottom: 16px; opacity: 0.4; }
                .empty h3 { color: rgba(255,255,255,0.5); font-size: 20px; }
                .actions {
                    display: flex;
                    gap: 10px;
                    margin-top: 20px;
                    flex-wrap: wrap;
                }
                .btn {
                    padding: 10px 24px;
                    border: none;
                    border-radius: 10px;
                    cursor: pointer;
                    font-weight: 600;
                    transition: all 0.3s ease;
                }
                .btn-primary {
                    background: #00d4ff;
                    color: #0b2e33;
                }
                .btn-primary:hover { background: #00b8e6; transform: translateY(-2px); }
                .btn-secondary {
                    background: rgba(255,255,255,0.1);
                    color: #fff;
                }
                .btn-secondary:hover { background: rgba(255,255,255,0.2); transform: translateY(-2px); }
                .back-link {
                    display: inline-block;
                    margin-top: 20px;
                    padding: 10px 24px;
                    background: rgba(0,212,255,0.1);
                    color: #00d4ff;
                    text-decoration: none;
                    border-radius: 10px;
                    transition: all 0.3s ease;
                }
                .back-link:hover { background: rgba(0,212,255,0.2); }
                .debug {
                    margin-top: 20px;
                    padding: 15px;
                    background: rgba(255,255,255,0.03);
                    border-radius: 8px;
                    font-size: 12px;
                    color: rgba(255,255,255,0.2);
                    overflow: auto;
                }
                @media (max-width: 768px) {
                    .header { flex-direction: column; align-items: flex-start; }
                    .header-left h1 { font-size: 22px; }
                    .actions { width: 100%; }
                    .btn { flex: 1; text-align: center; }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <div class="header-left">
                        <h1><i class="fas fa-bell"></i> Notifications</h1>
                        <span class="role-badge">
                            <i class="fas ' . ($isAdmin ? 'fa-user-shield' : 'fa-user') . '"></i>
                            ' . ucfirst($userRole) . '
                        </span>
                    </div>
                    <span class="badge">' . $unreadCount . ' unread</span>
                </div>';

                if ($notifications->count() > 0) {
                    foreach ($notifications as $notification) {
                        $data = $notification->data;
                        if (is_string($data)) {
                            $data = json_decode($data, true);
                        }
                        if (!is_array($data)) {
                            $data = [];
                        }
                        
                        $title = $data['title'] ?? $notification->title ?? 'Notification';
                        $message = $data['message'] ?? $notification->message ?? '';
                        $icon = $data['icon'] ?? 'fa-bell';
                        
                        $html .= '
                        <div class="card">
                            <div class="title"><i class="fas ' . $icon . '" style="color: #00d4ff; margin-right: 8px;"></i>' . $title . '</div>
                            <div class="message">' . $message . '</div>
                            <div class="time">' . $notification->created_at->diffForHumans() . '</div>
                        </div>';
                    }
                } else {
                    $html .= '
                    <div class="empty">
                        <div class="icon">🔕</div>
                        <h3>No Notifications</h3>
                        <p>You don\'t have any notifications yet.</p>
                    </div>';
                }

                $html .= '
                <div class="actions">
                    <button class="btn btn-primary" onclick="markAllRead()">
                        <i class="fas fa-check-double"></i> Mark all as read
                    </button>
                    <button class="btn btn-secondary" onclick="location.reload()">
                        <i class="fas fa-sync"></i> Refresh
                    </button>
                </div>
                
                <div style="text-align: center; margin-top: 20px;">
                    <a href="' . ($isAdmin ? '/admin/dashboard' : '/') . '" class="back-link">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
                
                <div class="debug">
                    <strong>Debug:</strong> User ID: ' . $user->id . ' | Role: ' . $userRole . ' | Notifications: ' . $notifications->count() . ' | Unread: ' . $unreadCount . '
                </div>
            </div>
            
            <script>
                function markAllRead() {
                    fetch("/notifications/read-all", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]")?.getAttribute("content") || ""
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => console.error("Error:", error));
                }
            </script>
        </body>
        </html>';
        
        return response($html);
    }

    public function markAsRead($id)
    {
        try {
            $user = Auth::user();
            
            $notification = Notification::where('id', $id)
                ->where(function($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->orWhereNull('user_id');
                })
                ->firstOrFail();
            
            $notification->update(['read_at' => now()]);
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            Log::error('Mark as read error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
            
            $notifications = Notification::where(function($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->orWhereNull('user_id');
                })
                ->whereNull('read_at')
                ->get();
            
            foreach ($notifications as $notification) {
                $notification->update(['read_at' => now()]);
            }
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            Log::error('Mark all read error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function unreadCount()
    {
        try {
            $user = Auth::user();
            
            $count = Notification::where(function($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->orWhereNull('user_id');
                })
                ->whereNull('read_at')
                ->count();
            
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
            
        } catch (\Exception $e) {
            Log::error('Unread count error: ' . $e->getMessage());
            return response()->json(['success' => false, 'count' => 0], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = Auth::user();
            
            $notification = Notification::where('id', $id)
                ->where(function($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->orWhereNull('user_id');
                })
                ->firstOrFail();
            
            $notification->delete();
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            Log::error('Delete notification error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}