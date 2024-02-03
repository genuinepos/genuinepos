<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\SAAS\Entities\Tenant;

class TenantRegisterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tenant;

    /**
     * Create a new job instance.
     */
    public function __construct(Tenant $tenant)
    {
        $this->$tenant = $tenant;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->tenant->run(function() {
            User::create([
                'name' => $this->tenant->name,
                'email' => $this->tenant->email,
                'phone' => $this->tenant->phone,
                'password' => $this->tenant->password,
                'plan_id' => $this->tenant->plan_id,
                'shop_count' => $this->tenant->shop_count,
                'expire_at' => $this->tenant->expire_at,
            ]);
        });
    }
}
