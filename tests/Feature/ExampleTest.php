<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use \MailTracking;

    public function testBasicTest()
    {
        $response = $this->get('/');
            $this->seeEmailWasSent();

        $response->assertStatus(200);
    }

}
