<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class LiveTest extends TestCase
{
    /**
     * A basic test for site live.
     *
     * @return void
     */
    public function testServiceLive()
    {
        $this->get('/');

        $this->assertEquals(
            'Hello Travian', $this->response->getContent()
        );
    }
}
