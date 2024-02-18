<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function index()
    {
        $data = User::where('is_sent', 0)->limit(5)->get();

        if ($data) {
            foreach ($data as $d) {
                $this->send($d);
            }
        }

    }

    private function send($user)
    {
        // Mail::to($user['email'])->send(new \App\Mail\SendEmail($user));

        $user->update([
            'is_sent' => 1
        ]);
    }
}
