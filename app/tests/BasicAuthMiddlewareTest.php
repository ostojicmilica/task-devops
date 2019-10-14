<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BasicAuthMiddlewareTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicAuth()
    {
        $this->call('GET', 'api/v1/auth', [], [], [],
            ["WWW-Authenticate" => "Basic " . base64_encode("guest:guest"), 'PHP_AUTH_USER' => 'guest', 'PHP_AUTH_PW' => 'guest']);
        $this->assertResponseStatus(200);
    }
}
