<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionPlanController extends Controller
{
    public function __construct()
    {
        // Middleware will be applied via routes
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = SubscriptionPlan::ordered()->get();
        return view('admin.subscription-plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.subscription-plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:free,monthly,yearly',
            'meeting_duration_limit' => 'nullable|integer|min:1',
            'max_participants' => 'required|integer|min:1',
            'storage_limit' => 'nullable|integer|min:1',
            'has_recording' => 'boolean',
            'has_breakout_rooms' => 'boolean',
            'has_admin_tools' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);
        
        // Ensure slug is unique
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (SubscriptionPlan::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        SubscriptionPlan::create($validated);

        return redirect()->route('subscription-plans.index')
            ->with('success', 'Subscription plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubscriptionPlan $subscriptionPlan)
    {
        return view('admin.subscription-plans.show', compact('subscriptionPlan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return view('admin.subscription-plans.edit', compact('subscriptionPlan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:free,monthly,yearly',
            'meeting_duration_limit' => 'nullable|integer|min:1',
            'max_participants' => 'required|integer|min:1',
            'storage_limit' => 'nullable|integer|min:1',
            'has_recording' => 'boolean',
            'has_breakout_rooms' => 'boolean',
            'has_admin_tools' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        // Generate slug from name if name changed
        if ($validated['name'] !== $subscriptionPlan->name) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Ensure slug is unique (excluding current plan)
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (SubscriptionPlan::where('slug', $validated['slug'])
                    ->where('id', '!=', $subscriptionPlan->id)
                    ->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $subscriptionPlan->update($validated);

        return redirect()->route('subscription-plans.index')
            ->with('success', 'Subscription plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        // Check if plan has active subscriptions
        if ($subscriptionPlan->activeSubscriptions()->count() > 0) {
            return redirect()->route('subscription-plans.index')
                ->with('error', 'Cannot delete plan with active subscriptions.');
        }

        $subscriptionPlan->delete();

        return redirect()->route('subscription-plans.index')
            ->with('success', 'Subscription plan deleted successfully.');
    }
}
