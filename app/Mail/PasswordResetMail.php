<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    /**
     * Create a new message instance.
     *
     * @param string $token
     * @param string $email
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Generar el enlace de restablecimiento de contraseña
        $resetUrl = url('password/reset/'.$this->token.'?email='.$this->email);

        // Enviar el correo como texto plano, sin usar vistas.
        return $this->subject('Password Reset Link')
                    ->from('noreply@tudominio.com', 'No Reply')
                    ->text('emails.passwordreset_plain')
                    ->with(['resetUrl' => $resetUrl]); // Pasamos el enlace al mensaje
    }
}
