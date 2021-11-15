<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\Repayment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class RepaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Repayment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'loan_id' => Loan::factory(),
            'amount' => $this->faker->numberBetween(10,500),
        ];
    }
}
