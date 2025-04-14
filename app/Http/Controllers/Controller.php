<?php

namespace App\Http\Controllers;

abstract class Controller
{
    // Introduction - bemutatkozás
    public function introduction()
    {
        return view('introduction');
    }

    // Contact - elérhetőségek
    public function contact()
    {
        return view('contact');
    }

    // Dashboard - dashboard
    public function dashboard()
    {
        return view('dashboard');
    }
}
