<?php

namespace App\Http\Controllers;

use App\Services\Contracts\TermServiceContract;
use Illuminate\Http\Request;

class TermController extends Controller
{
    private TermServiceContract $termService;

    /**
     * @param TermServiceContract $termService
     */
    public function __construct (TermServiceContract $termService)
    {
        $this->termService = $termService;
    }

    public function getTerms ()
    {

    }
}
