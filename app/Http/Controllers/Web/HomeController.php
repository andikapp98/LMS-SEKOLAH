<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Display the home/landing page.
     */
    public function index()
    {
        return Inertia::render('Home');
    }
}
