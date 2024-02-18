<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function index()
    {
        if (config('app.env') != 'local') {
            return redirect()->route('user.index');
        }

        $data = User::where('is_sent', 0)->limit(5)->get();

        if ($data) {
            foreach ($data as $d) {
                $this->send($d);
            }
        }

    }

    public function send($user)
    {
        try {
            // kirim email
            Mail::to($user['email'])->send(new \App\Mail\SendEmail($user));

            // update terkirim
            User::where('id', $user->id)->update(['is_sent' => 1]);
        } catch (\Throwable $th) {
            echo 'Error: ' . $th->getMessage();
        }

    }
}
