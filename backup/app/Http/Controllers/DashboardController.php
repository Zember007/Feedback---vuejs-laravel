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

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {


        $survey = DB::table('surveys')->where('user_id', $request->user()->id)->first();
        if($survey) {
            $question = Question::where('survey_id', $survey->id)->first();
            $answers = Answer::where('question_id', $question->id)->get();
            $answer_options = Answer_option::where('question_id', $question->id)->get();
            return Inertia::render('Dashboard', [
                'answers' => $answers,
                'answer_options' => $answer_options,
            ]);
        } else {
            $surveys = DB::table('surveys')->where('user_id', $request->user()->id)->get();
            return Inertia::render('Survey/Index', [
                'surveys' => $surveys,
            ]);
        }
        
    }
}