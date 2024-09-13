<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{

    use RefreshDatabase;
 
    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');
 
        $response->assertStatus(200);
    }
    

}
