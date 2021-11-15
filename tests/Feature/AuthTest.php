<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    /**
     * Test user login with not exist credentials
     *
     * @return void
     */
    public function testLoginFailWithUserNotExist()
    {
        $credentials = [
            'email'    => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(),
        ];

        $response = $this->json('post', route('login'), $credentials);
        $response->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'Unauthorized',
            ]);
    }

    /**
     * Test user login with empty email and password
     *
     * @return void
     */
    public function testErrorWhenInputEmptyCredentials()
    {
        $this->json('POST', route('login'),[],['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson(['status' => false])
            ->assertJsonStructure([
                'errors' => [
                    'email', 'password'
                ]
            ]);
    }

    /**
     * Test user login with wrong email or password
     *
     * @return void
     */
    public function testUserLoginErrorWrongCredentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('123123')
        ]);

        $this->json(
            'POST',
            route('login'),
            [
                'email' => $this->faker->unique()->email(),
                'password' => $user->password
            ],
            ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'Unauthorized'
            ]);

        $this->json(
            'POST',
            route('login'),
            [
                'email' => $user->email,
                'password' => $this->faker->password()
            ],
            ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'Unauthorized'
            ]);
    }

    /**
     * Test login success
     *
     * @return void
     */
    public function testLoginSuccess()
    {
        $password = '123123';
        $user = User::factory()->create([
            'password' => Hash::make($password)
        ]);

        $this->json(
            'POST',
            route('login'),
            [
                'email' => $user->email,
                'password' => $password
            ],
            [
                'Accept' => 'application/json'
            ])
            ->assertStatus(200)
            ->assertJson([
                'status' => true
            ])
            ->assertJsonStructure([
                'status',
                'data' => ['token']
            ])
        ;
    }
}
