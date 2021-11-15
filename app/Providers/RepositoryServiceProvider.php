<?php

namespace App\Providers;

use App\Repositories\User\UserRepository;
use App\Repositories\User\UserInterface;
use App\Repositories\Loan\LoanInterface;
use App\Repositories\Loan\LoanRepository;
use App\Repositories\LoanRepayment\RepaymentInterface;
use App\Repositories\LoanRepayment\RepaymentRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        App::bind(UserInterface::class, UserRepository::class);
        App::bind(LoanInterface::class, LoanRepository::class);
        App::bind(RepaymentInterface::class, RepaymentRepository::class);
    }
}
