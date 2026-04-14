<?php

namespace App\Mail;

use App\Enums\Queue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;

class RegisteredUserAlert extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @param  array{
     *     registered_user_name: string,
     *     registered_user_email: string,
     *     registered_at: string
     * }  $payload
     */
    public function __construct(public array $payload)
    {
        $this->onQueue(Queue::MAIL->value);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: sprintf('[%s] New user registered', config('app.name')),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.users.registered-alert',
            with: [
                ...$this->payload,
                'registered_at_human' => Date::parse($this->payload['registered_at'])
                    ->format('F j, Y \a\t g:i A'),
            ],
        );
    }
}
