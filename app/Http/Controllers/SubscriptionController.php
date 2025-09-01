<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * Display available subscription plans for upgrade
     */
    public function showPlans()
    {
        /** @var User $user */
        $user = Auth::user();
        $currentSubscription = $user->activeSubscription();
        
        // Get all plans except the user's current plan
        $availablePlans = SubscriptionPlan::where('name', '!=', 'Free Plan');
        
        if ($currentSubscription) {
            $availablePlans = $availablePlans->where('id', '!=', $currentSubscription->subscription_plan_id);
        }
        
        $availablePlans = $availablePlans->get();
        
        return view('user.upgrade-plans', [
            'availablePlans' => $availablePlans,
            'currentSubscription' => $currentSubscription
        ]);
    }
    
    /**
     * Initiate payment for selected plan
     */
    public function initiatePlanUpgrade(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id'
        ]);
        
        $user = Auth::user();
        $selectedPlan = SubscriptionPlan::findOrFail($request->plan_id);
        
        // Store the selected plan in session for payment processing
        session(['selected_plan_id' => $selectedPlan->id]);
        
        return redirect()->route('payment.initialize', ['plan' => $selectedPlan->id]);
    }
    
    /**
     * Pass subscription data to dashboard
     */
    public function passDataToDashboard()
    {
        /** @var User $user */
        $user = Auth::user();
        $currentSubscription = $user->activeSubscription();
        
        return [
            'currentSubscription' => $currentSubscription,
            'subscriptionPlan' => $currentSubscription ? $currentSubscription->subscriptionPlan : null
        ];
    }
}
