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
use Illuminate\Support\Facades\DB;


class ApiSurveyController extends Controller
{
    public function question_answers_1(Request $request)
    {

        if($request->question_id) {

            $answer = Answer::create([
                'question_id' => $request->question_id,
                'comment' => $request->comment,
                'name' => $request->name,
                'phone' => $request->phone,
                'answer_option_id' => json_encode($request->answers),
                'status' => $request->status,
            ]);

        return response()->json($answer->answer_option_id);
        } else {
            return response()->json(400);
        }
    }

    public function question_answers_3(Request $request)
    {
        if($request->question_id) {
        $answer = Answer::create([
            'question_id' => $request->question_id,
            'status' => 3,
        ]);

        return response()->json($answer);
        } else {
            return response()->json(400);
        }
    }
}
