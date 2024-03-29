<?php

namespace App\Mail;

use App\Models\PasswordResetToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetAccountPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $email;
    private $token;
    private $initiatedDateTime;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $email, string $token, Carbon $createdAt)
    {
        $this->email = $email;
        $this->token = $token;
        $this->initiatedDateTime = $createdAt;

        $this->subject("Forgot Password - " . config("app.name"));
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.auth.forgot-password', [
            "email" => $this->email,
            "resetUrl" => route('auth.forgot-password.step.2', ['token' => $this->token, "email" => $this->email]),
            "date" => $this->initiatedDateTime->format("Y-m-d h:i A e")
        ]);
    }
}
