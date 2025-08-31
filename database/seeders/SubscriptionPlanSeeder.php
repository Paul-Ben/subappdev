<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free Plan',
                'slug' => 'free',
                'description' => 'Basic video conferencing with limited features',
                'price' => 0.00,
                'billing_cycle' => 'free',
                'meeting_duration_limit' => 40, // 40 minutes
                'max_participants' => 20,
                'storage_limit' => 1, // 1 GB
                'has_recording' => false,
                'has_breakout_rooms' => false,
                'has_admin_tools' => false,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Monthly Plan',
                'slug' => 'monthly',
                'description' => 'Full-featured video conferencing with monthly billing',
                'price' => 5000.00, // ₦5,000
                'billing_cycle' => 'monthly',
                'meeting_duration_limit' => null, // Unlimited
                'max_participants' => 100,
                'storage_limit' => 10, // 10 GB
                'has_recording' => true,
                'has_breakout_rooms' => true,
                'has_admin_tools' => true,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Yearly Plan',
                'slug' => 'yearly',
                'description' => 'Full-featured video conferencing with yearly billing (2 months free)',
                'price' => 50000.00, // ₦50,000 (equivalent to 10 months)
                'billing_cycle' => 'yearly',
                'meeting_duration_limit' => null, // Unlimited
                'max_participants' => 100,
                'storage_limit' => 50, // 50 GB
                'has_recording' => true,
                'has_breakout_rooms' => true,
                'has_admin_tools' => true,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('subscription_plans')->insert($plans);
    }
}
