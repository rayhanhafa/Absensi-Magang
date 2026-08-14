<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Attendance;
use App\Policies\AttendancePolicy;
use Illuminate\Support\Facades\Gate;
use App\Models\Intern;
use App\Models\LeaveRequest;
use App\Policies\InternPolicy;
use App\Policies\LeaveRequestPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Attendance::class, AttendancePolicy::class);
    Gate::policy(Intern::class, InternPolicy::class);
    Gate::policy(LeaveRequest::class, LeaveRequestPolicy::class);
    }
}
