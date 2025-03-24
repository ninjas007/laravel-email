<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactList;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home.index', [
            'page' => 'home',
            'totalList' => ContactList::count(),
            'totalKontak' => Contact::count(),
            'totalBroadcast' => 0,
            'totalTemplate' => 0,
        ]);
    }
}
