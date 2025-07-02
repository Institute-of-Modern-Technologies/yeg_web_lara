<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Student;

class StudentApprovalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $student;
    protected $username;
    protected $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Student $student, string $username, string $password)
    {
        $this->student = $student;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Young Experts Group - Registration Approved')
            ->greeting('Hello ' . $this->student->full_name . '!')
            ->line('Congratulations! Your registration with Young Experts Group has been approved.')
            ->line('Your registration number is: ' . $this->student->registration_number)
            ->line('You can now log in to your account using the following credentials:')
            ->line('Username: ' . $this->username)
            ->line('Password: ' . $this->password)
            ->line('For security reasons, we recommend changing your password after your first login.')
            ->line('If you have any questions, please contact our support team.')
            ->action('Login Now', url('/login'))
            ->line('Thank you for choosing Young Experts Group!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'student_id' => $this->student->id,
            'registration_number' => $this->student->registration_number,
        ];
    }
}
