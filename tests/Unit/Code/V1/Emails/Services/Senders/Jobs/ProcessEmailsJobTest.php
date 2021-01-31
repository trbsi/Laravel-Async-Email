<?php

namespace Tests\Unit\Code\V1\Emails\Services\Senders\Jobs;

use App\Code\V1\Emails\Services\Senders\Jobs\ProcessEmailsJob;
use App\Code\V1\Emails\Services\Senders\Mails\SendEmail;
use App\Code\V1\Emails\Services\Senders\Services\LogEmail;
use App\Code\V1\Emails\Services\Senders\Services\PrepareEmailData;
use App\Code\V1\Emails\Services\Senders\Values\EmailData;
use Illuminate\Support\Facades\Mail;
use Mockery\MockInterface;
use Tests\TestCase;

class ProcessEmailsJobTest extends TestCase
{
    public function test_process_emails_job()
    {

        Mail::fake();

        $emailData = new EmailData(
            'x@x.com',
            'Subject X',
            'Body X',
            []
        );

        $prepareEmailDataMock = $this->mock(PrepareEmailData::class, function (MockInterface $mock) use ($emailData) {
            $mock
                ->shouldReceive('prepareData')
                ->once()
                ->with(
                    'x@x.com',
                    'Subject X',
                    'Body X',
                    []
                )
                ->andReturn($emailData);
        });

        $logEmail = $this->mock(LogEmail::class, function (MockInterface $mock) use ($emailData) {
            $mock
                ->shouldReceive('log')
                ->once()
                ->with($emailData);
        });


        $job = new ProcessEmailsJob(
            'x@x.com',
            'Subject X',
            'Body X',
            []
        );
        $job->handle($prepareEmailDataMock, $logEmail);

        Mail::assertQueued(SendEmail::class);

        $this->assertTrue(true);
    }
}
