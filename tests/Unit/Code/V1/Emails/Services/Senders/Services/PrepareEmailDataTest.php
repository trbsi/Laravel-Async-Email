<?php

namespace Tests\Unit\Code\V1\Emails\Services\Senders\Services;

use App\Code\V1\Emails\Services\Senders\Values\Attachment;
use App\Code\V1\Emails\Services\Senders\Values\EmailData;
use Mockery\MockInterface;
use App\Code\V1\Emails\Services\Senders\Services\PrepareEmailData;
use App\Code\V1\Emails\Services\Senders\Services\ProcessAttachments;
use Tests\TestCase;

class PrepareEmailDataTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_that_email_data_is_returned_without_attachments()
    {
        $mock = $this->mock(ProcessAttachments::class, function (MockInterface $mock) {
            $mock->shouldReceive('process')->once();
        });

        $service = $this->getService($mock);
        $data = $service->prepareData('x@x.com', 'subject X', 'body X');
        $expected = new EmailData('x@x.com', 'subject X', 'body X');
        $this->assertEquals($expected, $data);
    }

    public function test_that_email_data_is_returned_with_attachments()
    {
        $attachment = ['value' => 'some-base64', 'name' => 'My PDF file'];
        $attachmentObjects = [
            new Attachment(
                'something/storage',
                'something/storage/file.png',
                'My PDF file'
            )
        ];

        $mock = $this->mock(ProcessAttachments::class, function (MockInterface $mock) use ($attachment, $attachmentObjects) {
            $mock->shouldReceive('process')
                ->once()
                ->with($attachment)
                ->andReturn($attachmentObjects);
        });

        $service = $this->getService($mock);
        $data = $service->prepareData('x@x.com', 'subject X', 'body X', $attachment);
        $expected = new EmailData('x@x.com', 'subject X', 'body X', $attachmentObjects);
        $this->assertEquals($expected, $data);
    }

    private function getService(MockInterface $processAttachments): PrepareEmailData
    {
        return new PrepareEmailData($processAttachments);
    }
}
