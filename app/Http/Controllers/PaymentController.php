<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Initialize payment with Credo
     */
    public function initializePayment(Request $request)
    {
        $user = Auth::user();
        $planId = $request->input('plan') ?? session('selected_plan_id');
        
        if (!$planId) {
            return redirect()->route('subscription.plans')->with('error', 'Please select a plan first.');
        }
        
        $plan = SubscriptionPlan::findOrFail($planId);
        
        // Generate unique transaction reference
        $reference = 'SUB_' . time() . '_' . $user->id;
        
        // Store transaction details in session
        session([
            'payment_reference' => $reference,
            'plan_id' => $plan->id,
            'amount' => $plan->price
        ]);
        
        // Prepare Credo payment data
        $paymentData = [
            'amount' => $plan->price / 100, // Convert from kobo to naira
            'currency' => 'NGN',
            'reference' => $reference,
            'email' => $user->email,
            'name' => $user->name,
            'callback_url' => route('payment.callback'),
            'metadata' => [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'plan_name' => $plan->name
            ]
        ];
        
        try {
            // Log the payment data for debugging
            Log::info('Credo payment data', $paymentData);
            
            // Initialize payment with Credo API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.credo.secret_key'),
                'Content-Type' => 'application/json',
            ])->post(config('services.credo.base_url') . '/transaction/initialize', $paymentData);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] == 200 && $data['message'] === 'Successfully processed') {
                    // Redirect to Credo payment page
                    return redirect($data['data']['authorizationUrl']);
                } else {
                    Log::error('Credo payment initialization failed', $data);
                    return redirect()->route('subscription.plans')->with('error', 'Payment initialization failed. Please try again.');
                }
            } else {
                Log::error('Credo API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return redirect()->route('subscription.plans')->with('error', 'Payment service unavailable. Please try again later.');
            }
        } catch (\Exception $e) {
            Log::error('Payment initialization exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('subscription.plans')->with('error', 'An error occurred. Please try again.');
        }
    }
    
    /**
     * Handle payment callback from Credo
     */
    public function handleCallback(Request $request)
    {
        $reference = $request->input('reference');
        
        if (!$reference) {
            return redirect()->route('user.dashboard')->with('error', 'Invalid payment reference.');
        }
        
        try {
            // Verify payment with Credo API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.credo.secret_key'),
            ])->get(config('services.credo.base_url') . '/transaction/verify/' . $reference);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'success' && $data['data']['status'] === 'success') {
                    // Payment successful, upgrade user subscription
                    $this->upgradeUserSubscription($data['data']);
                    
                    // Clear session data
                    session()->forget(['payment_reference', 'plan_id', 'amount', 'selected_plan_id']);
                    
                    return redirect()->route('user.dashboard')->with('success', 'Payment successful! Your subscription has been upgraded.');
                } else {
                    Log::warning('Payment verification failed', $data);
                    return redirect()->route('user.dashboard')->with('error', 'Payment verification failed.');
                }
            } else {
                Log::error('Payment verification API failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return redirect()->route('user.dashboard')->with('error', 'Payment verification failed.');
            }
        } catch (\Exception $e) {
            Log::error('Payment callback exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('user.dashboard')->with('error', 'An error occurred during payment verification.');
        }
    }
    
    /**
     * Upgrade user subscription after successful payment
     */
    private function upgradeUserSubscription($paymentData)
    {
        $userId = $paymentData['metadata']['user_id'];
        $planId = $paymentData['metadata']['plan_id'];
        $amount = $paymentData['amount'] * 100; // Convert back to kobo
        
        $user = User::findOrFail($userId);
        $plan = SubscriptionPlan::findOrFail($planId);
        
        // Cancel current active subscription
        $currentSubscription = $user->activeSubscription();
        if ($currentSubscription) {
            $currentSubscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now()
            ]);
        }
        
        // Create new subscription
        $startsAt = now();
        $endsAt = $plan->billing_cycle === 'monthly' 
            ? $startsAt->copy()->addMonth()
            : $startsAt->copy()->addYear();
            
        $subscription = Subscription::create([
            'user_id' => $userId,
            'subscription_plan_id' => $planId,
            'status' => 'active',
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'amount_paid' => $amount,
            'currency' => 'NGN',
            'metadata' => [
                'payment_reference' => $paymentData['reference'],
                'payment_method' => 'credo',
                'upgraded_from' => $currentSubscription ? $currentSubscription->subscriptionPlan->name : 'Free Plan'
            ]
        ]);
        
        // Log payment in payments table
        Payment::create([
            'user_id' => $userId,
            'subscription_id' => $subscription->id,
            'payment_reference' => $paymentData['reference'],
            'gateway' => 'credo',
            'amount' => $amount,
            'status' => 'completed',
            'type' => 'subscription',
            'gateway_response' => $paymentData,
            'processed_at' => now()
        ]);
        
        Log::info('Subscription upgraded and payment logged successfully', [
            'user_id' => $userId,
            'plan_id' => $planId,
            'amount' => $amount,
            'reference' => $paymentData['reference']
        ]);
    }
}
