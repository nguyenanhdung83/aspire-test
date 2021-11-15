<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\Repayment;
use App\Models\User;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        foreach ($users as $user) {
            Loan::create([
                'amount' => 10000,
                'user_id' => $user->id,
                'term' => 10,
                'frequency' => Loan::FREQUENCY['weekly'],
                'process_status' => Loan::PROCESS_STATUS['approved'],
                'repayment_completed' => Loan::REPAYMENT_COMPLETED['not_yet'],
            ]);
        }

    }
}
