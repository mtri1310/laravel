<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class NewRandomPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $newPassword;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param string $newPassword
     * @param User $user
     */
    public function __construct($newPassword, User $user)
    {
        $this->newPassword = $newPassword;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mật Khẩu Mới của Bạn',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'auth.emails.new_random_password',
            with: [
                'password' => $this->newPassword,
                'user' => $this->user,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
