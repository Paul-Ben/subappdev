<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display the user's transaction history
     */
    public function index()
    {
        $user = Auth::user();
        $transactions = Payment::where('user_id', $user->id)
            ->with('subscription.subscriptionPlan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show receipt preview
     */
    public function showReceipt($paymentId)
    {
        $user = Auth::user();
        $payment = Payment::where('user_id', $user->id)
            ->with('subscription.subscriptionPlan')
            ->findOrFail($paymentId);

        return view('transactions.receipt', compact('payment', 'user'));
    }

    /**
     * Generate and download receipt as image
     */
    public function downloadReceipt($paymentId)
    {
        $user = Auth::user();
        $payment = Payment::where('user_id', $user->id)
            ->with('subscription.subscriptionPlan')
            ->findOrFail($paymentId);

        // For now, redirect to receipt view - we'll implement image generation later
        return $this->showReceipt($paymentId);
    }
}