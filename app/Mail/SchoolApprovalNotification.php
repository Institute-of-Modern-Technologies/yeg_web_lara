<?php

namespace App\Mail;

use App\Models\School;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SchoolApprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The school data.
     *
     * @var \App\Models\School
     */
    public $school;

    /**
     * The user data.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * The default password.
     *
     * @var string
     */
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct(School $school, User $user, string $password)
    {
        $this->school = $school;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'School Registration Approved - YEG Education Portal',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.school-approval',
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
