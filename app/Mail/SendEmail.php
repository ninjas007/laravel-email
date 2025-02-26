<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->data = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->data->template_email == 1) {
            return $this
                ->subject('Kirim Mail')
                ->view('email1', [
                    'data' => $this->data
                ]);
        }

        if ($this->data->template_email == 2) {
            return $this
                ->subject('Kirim Mail')
                ->view('email2', [
                    'data' => $this->data
                ]);
        }

        if ($this->data->template_email == 3) {
            return $this
                ->subject('Kirim Mail')
                ->view('email3', [
                    'data' => $this->data
                ]);
        }

        if ($this->data->template_email == 4) {
            return $this
                ->subject('Kirim Mail')
                ->view('email4', [
                    'data' => $this->data
                ]);
        }

        return $this
            ->subject('3 Ebook Lengkap Microsoft Excel Untuk Mahir Excel')
            ->view('email', [
                'data' => $this->data
            ]);
    }
}
