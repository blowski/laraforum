<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\User;
use Illuminate\Notifications\Notification;

class UserNotificationsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return auth()->user()->unreadNotifications;
    }

    public function destroy(User $user, $notificationId)
    {
        auth()->user()->notifications()->findOrFail($notificationId)->markAsRead();
    }
}
