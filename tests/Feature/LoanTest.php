<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{Repayment, User, Loan};

class LoanTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test create loan with empty data
     *
     * @return void
     */
    public function testErrorWhenRequestNotIncludeToken()
    {
        $this->json(
            'POST',
            route('loan.store'),
            [
                'amount' => $this->faker->numberBetween(4000, 10000),
                'term' => $this->faker->numberBetween(10, 100),
            ],
            ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertSimilarJson([
                'status' => false,
                'message' => 'Unauthenticated.'
            ]);
    }

    /**
     * Test create loan with empty data
     *
     * @return void
     */
    public function testErrorWhenInputEmptyData()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => "Bearer $token"
            ])
            ->json('POST', route('loan.store'))
            ->assertStatus(422)
            ->assertJson(['status' => false])
            ->assertJsonStructure(['errors' => ['amount', 'term']]);
    }

    /**
     * Test create loan when have loan not yet paid off
     *
     * @return void
     */
    public function testErrorHaveLoanNotPaidOffWhenApplyOtherLoan()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $loan = Loan::factory()->create(['user_id' => $user->id]);

        $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => "Bearer $token"
            ])
            ->json('POST', route('loan.store'),['amount' => $loan->amount, 'term' => $loan->term])
            ->assertStatus(422)
            ->assertJson([
                'status' => false,
                'errors' => [
                    "user_id" => [__('loan.user_had_unpaid_loan')]
                ]
            ]);
    }

    /**
     * Test create loan success
     *
     * @return void
     */
    public function testCreateLoanSuccess()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $loanData = [
            'amount' => $this->faker->numberBetween(4000, 10000),
            'term' => $this->faker->numberBetween(10, 100),
        ];

        $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => "Bearer $token"
            ])
            ->json('POST', route('loan.store'), $loanData)
            ->assertStatus(200)
            ->assertJson(['status' => true])
            ->assertJson([
                'data' => [
                    "amount" => $loanData['amount'],
                    "term" => $loanData['term'],
                    "frequency" => Loan::FREQUENCY['weekly'],
                    "process_status" => Loan::PROCESS_STATUS['approved'],
                    "repayment_completed" => Loan::REPAYMENT_COMPLETED['not_yet'],
                ]
            ]);
    }

    public function testGetAllLoan()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $loan = Loan::factory()->create(['user_id' => $user->id]);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer $token"
            ])
            ->json('GET',route('loan.index'),[])
            ->assertStatus(200)
            ->assertJson(['status' => true])
            ->assertJsonStructure([
                'data' => ['*' => ['id','amount','term','frequency','process_status','repayment_completed','requested_at']]
            ]);
    }

    public function testErrorShowLoanDetailNotOwner()
    {
        $user1 = User::factory()->create();
        $user1Token = $user1->createToken($user1->email)->plainTextToken;

        $user2 = User::factory()->create();
        $user2Loan = Loan::factory()->create(['user_id' => $user2->id]);

        $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$user1Token}"
            ])
            ->json('GET', route('loan.show', ['id' => $user2Loan->id]), [])
            ->assertStatus(422)
            ->assertJson([
                'status' => false,
                'errors' => [ 'loan_id' => [__('loan.not_owner')]]
            ]);
    }

    public function testShowLoanDetail()
    {
        $user = User::factory()->create();
        $userToken = $user->createToken($user->email)->plainTextToken;
        $userLoan = Loan::factory()->create(['user_id' => $user->id]);

        $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$userToken}"
            ])
            ->json('GET', route('loan.show', ['id' => $userLoan->id]), [])
            ->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    "amount" => $userLoan['amount'],
                    "term" => $userLoan['term'],
                    "frequency" => Loan::FREQUENCY['weekly'],
                    "process_status" => Loan::PROCESS_STATUS['approved'],
                    "repayment_completed" => Loan::REPAYMENT_COMPLETED['not_yet'],
                ]
            ]);
    }

    public function testErrorShowRepaymentsLoanNotOwner()
    {
        $user1 = User::factory()->create();
        $user1Token = $user1->createToken($user1->email)->plainTextToken;

        $user2 = User::factory()->create();
        $user2Loan = Loan::factory()->create(['user_id' => $user2->id]);
        $repayment = Repayment::factory()->create(['loan_id' => $user2Loan->id]);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$user1Token}"
        ])
            ->json('GET', route('loan.repayments', ['id' => $repayment->id]), [])
            ->assertStatus(422)
            ->assertJson([
                'status' => false,
                'errors' => [ 'loan_id' => [__('loan.not_owner')]]
            ]);
    }

    public function testShowRepaymentsLoan()
    {
        $user = User::factory()->create();
        $userToken = $user->createToken($user->email)->plainTextToken;
        $userLoan = Loan::factory()->create(['user_id' => $user->id]);
        $repayment = Repayment::factory()->create(['loan_id' => $userLoan->id]);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$userToken}"
        ])
            ->json('GET', route('loan.repayments', ['id' => $repayment->id]), [])
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [ '*' => ['id', 'amount', 'paid_at']]
            ]);
    }

    public function testErrorPaidInputEmpty()
    {
        $user = User::factory()->create();
        $userToken = $user->createToken($user->email)->plainTextToken;
        $userLoan = Loan::factory()->create(['user_id' => $user->id]);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$userToken}"
        ])
            ->json('POST', route('loan.repaid', ['id' => $userLoan->id]), [])
            ->assertStatus(422)
            ->assertJson(['status' => false])
            ->assertJsonStructure([
                'errors' => [ 'amount' ]
            ]);
    }

    public function testErrorPaidLoanNotOwner()
    {
        $user1 = User::factory()->create();
        $user1Token = $user1->createToken($user1->email)->plainTextToken;

        $user2 = User::factory()->create();
        $user2Loan = Loan::factory()->create(['user_id' => $user2->id]);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$user1Token}"
        ])
            ->json('POST', route('loan.repaid', ['id' => $user2Loan->id]), [])
            ->assertStatus(422)
            ->assertJson([
                'status' => false,
                'errors' => [ 'loan_id' => [__('loan.not_owner')]]
            ]);
    }

    public function testErrorPaidForPaidOffLoan()
    {
        $user = User::factory()->create();
        $userToken = $user->createToken($user->email)->plainTextToken;
        $userLoan = Loan::factory()->create(['user_id' => $user->id, 'repayment_completed' => Loan::REPAYMENT_COMPLETED['yes']]);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$userToken}"
        ])
            ->json('POST', route('loan.repaid', ['id' => $userLoan->id]), [])
            ->assertStatus(422)
            ->assertJson([
                'status' => false,
                'errors' => [ 'loan_id' => [__('loan.repayment_completed')]]
            ]);
    }

    public function testSuccessRepaidForLoan()
    {
        $user = User::factory()->create();
        $userToken = $user->createToken($user->email)->plainTextToken;
        $userLoan = Loan::factory()->create(['user_id' => $user->id]);
        $paidMount = 999999;
        $totalPaidMount = 10000;

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$userToken}"
        ])
            ->json('POST', route('loan.repaid', ['id' => $userLoan->id]), ['amount' => $paidMount])
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'amount', 'paid_at', ]
            ]);
    }
}
