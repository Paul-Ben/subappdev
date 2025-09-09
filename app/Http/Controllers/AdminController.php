<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        $users = User::with('roles')->get();
        return view('admin.users', compact('users'));
    }

    public function subscriptions()
    {
        $subscriptions = Subscription::with(['user', 'subscriptionPlan'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.subscriptions', compact('subscriptions'));
    }
}
