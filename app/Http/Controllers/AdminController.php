<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // User Statistics
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $newUsersLastMonth = User::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();
        $userGrowthPercentage = $newUsersLastMonth > 0 
            ? round((($newUsersThisMonth - $newUsersLastMonth) / $newUsersLastMonth) * 100, 2)
            : 0;

        // Subscription Statistics
        $totalSubscriptions = Subscription::count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $subscriptionsThisMonth = Subscription::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $subscriptionsLastMonth = Subscription::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();
        $subscriptionGrowthPercentage = $subscriptionsLastMonth > 0 
            ? round((($subscriptionsThisMonth - $subscriptionsLastMonth) / $subscriptionsLastMonth) * 100, 2)
            : 0;

        // Payment/Revenue Statistics
        $totalRevenue = Payment::where('status', 'completed')->sum('amount'); // Keep in kobo for calculations
        $thisMonthRevenue = Payment::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');
        $lastMonthRevenue = Payment::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('amount');
        $revenueGrowthPercentage = $lastMonthRevenue > 0 
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 2)
            : 0;

        // Monthly data for charts (last 6 months)
        $monthlyRevenue = [];
        $monthLabels = [];
        $userGrowthData = [];
        $subscriptionGrowthData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
            $monthLabels[] = $date->format('M Y');
            
            $userGrowthData[] = User::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
                
            $monthlyRevenue[] = Payment::where('status', 'completed')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount'); // Keep in kobo for chart conversion
                
            $subscriptionGrowthData[] = Subscription::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }

        // Payment History (recent 10 payments)
        $recentPayments = Payment::with(['user', 'subscription.subscriptionPlan'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'newUsersThisMonth', 'userGrowthPercentage',
            'totalSubscriptions', 'activeSubscriptions', 'subscriptionGrowthPercentage',
            'totalRevenue', 'thisMonthRevenue', 'lastMonthRevenue', 'revenueGrowthPercentage',
            'monthlyRevenue', 'monthLabels', 'userGrowthData', 'subscriptionGrowthData',
            'recentPayments'
        ));
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

    public function payments()
    {
        $payments = Payment::with(['user', 'subscription.subscriptionPlan'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.payments', compact('payments'));
    }
}
