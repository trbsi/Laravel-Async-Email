<?php

namespace Tests\Unit\Code\V1\Emails\Services\Senders\Mails;

use App\Code\V1\Emails\Services\Senders\Mails\SendEmail;
use App\Code\V1\Emails\Services\Senders\Values\EmailData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SendEmailTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_send_email_mailable()
    {
        $emailData = new EmailData(
            'x@ax.com',
            'subject X',
            'body X'
        );

        $mailable = new SendEmail($emailData);

        $mailable->assertSeeInHtml('<b>body X</b>');
        $this->assertTrue(true);
    }
}
