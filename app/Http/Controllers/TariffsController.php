<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Question;
use App\Models\Answer_option;
use App\Models\Answer;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class TariffsController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Tariffs');
    }

    public function payment(Request $request): Response
    {
        return Inertia::render('Payment');
    }
}
