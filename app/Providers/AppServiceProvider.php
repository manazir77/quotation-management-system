<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Quotation;
use App\Models\TermsCondition;
use App\Policies\CategoryPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\ItemPolicy;
use App\Policies\QuotationPolicy;
use App\Policies\TermsConditionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Item::class, ItemPolicy::class);
        Gate::policy(TermsCondition::class, TermsConditionPolicy::class);
        Gate::policy(Quotation::class, QuotationPolicy::class);
    }
}
