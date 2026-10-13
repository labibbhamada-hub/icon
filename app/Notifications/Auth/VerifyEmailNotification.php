<?php

namespace App\Notifications\Auth;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        $viewData = [
            'user' => $notifiable,
            'verificationUrl' => $verificationUrl,
            'logoUrl' => rtrim((string) config('app.url'), '/') . '/images/bhamada-icon-logo.png',
        ];

        return (new MailMessage)
            ->subject('Verify Your Email Address | BHAMADA ICON 2026')
            ->view('emails.auth.verify-email', $viewData)
            ->text('emails.auth.verify-email-text', $viewData);
    }
}
