<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function index()
    {
        $data = [
            [
                'id' => 1,
                'nama' => 'John Doe',
                'email' => 'tilistiadi03@gmail.com',
            ]
        ];

        foreach ($data as $d) {
            $this->send($d);
        }

        echo 'berhasil kirim';
    }

    private function send($user)
    {
        Mail::to($user['email'])->send(new \App\Mail\SendEmail($user));
    }
}
