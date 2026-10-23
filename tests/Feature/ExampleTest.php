<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_redirects_visitors_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_log_in_with_default_password(): void
    {
        $this->seed();

        $this->post('/login', ['login' => 'admin', 'senha' => '123'])
            ->assertRedirect('/');

        $this->assertTrue(session('usuario_logado'));
        $this->get('/')->assertOk();
    }

    public function test_public_registration_is_unavailable(): void
    {
        $this->get('/login')->assertDontSee('Criar uma conta');
        $this->get('/registro')->assertNotFound();
        $this->post('/registro')->assertNotFound();
    }
}
