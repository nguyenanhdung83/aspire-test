<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Loan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $amount = rand(1, 10) * 10000;
        return [
            'user_id' => User::factory(),
            'amount' => $amount,
            'term' => rand(10, 100),
            'frequency' => Loan::FREQUENCY['weekly'],
            'process_status' => Loan::PROCESS_STATUS['approved'],
            'repayment_completed' => Loan::REPAYMENT_COMPLETED['not_yet'],
        ];
    }
}
