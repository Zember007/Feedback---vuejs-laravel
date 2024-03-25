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

class SurveyController extends Controller
{
    public function index(Request $request): Response
    {
        $surveys = DB::table('surveys')->where('user_id', $request->user()->id)->get();
        return Inertia::render('Survey/Index', [
            'surveys' => $surveys,
        ]);
    }
    public function create(Request $request): Response
    {
        $survey = Survey::create([
            'user_id' => $request->user()->id,
            'name' => $request->survey_name,
            'description' => $request->survey_description,
            'link' => $request->survey_link,
        ]);
        $question = Question::create([
            'survey_id' => $survey->id,
            'text' => $request->question,
        ]);
        foreach($request->answer_options as $answer_option) {
            $answer_option = Answer_option::create([
                'question_id' => $question->id,
                'text' => $answer_option,
            ]);
        }
        $surveys = DB::table('surveys')->where('user_id', $request->user()->id)->get();
        return Inertia::render('Survey/Index', [
            'surveys' => $surveys,
        ]);
    }

    public function delete(Request $request): Response
    {

        DB::delete('DELETE FROM surveys WHERE user_id = ?', [$request->user()->id]); 
        return Inertia::render('Survey/Index', [
            'surveys' => [],
        ]);

    }
    
    public function show(Request $request, string $id): Response
    {
        $survey = DB::table('surveys')->where('id', $id)->first();
        $questions = Question::where('survey_id', $survey->id)->with('answer_options')->get();
        return Inertia::render('Survey/Show', [
            'survey' => $survey,
            'questions' => $questions,
        ]);
    }
    public function add_question(Request $request): Response
    {

        $question = Question::create([
            'survey_id' => $request->survey,
            'text' => $request->question,
        ]);
        foreach($request->answer_options as $answer_option) {
            $answer_option = Answer_option::create([
                'question_id' => $question->id,
                'text' => $answer_option,
            ]);
        }
        $survey = Survey::where('id', $question->survey_id)->first();
        $questions = Question::where('survey_id', $survey->id)->with('answer_options')->get();
        return Inertia::render('Survey/Show', [
            'survey' => $survey,
            'questions' => $questions,
        ]);
    }
    public function survey_answers(Request $request, string $id): Response
    {
        $survey = Survey::where('id', $id)->first();
        $questions = Question::where('survey_id', $survey->id)->with('answer_options')->get();
        return Inertia::render('Survey/Survey_answer', [
            'survey' => $survey,
            'questions' => $questions,
        ]);
    }
    public function question_answers(Request $request): Response
    {
        $answer = Answer::create([
            'question_id' => $request->question_id,
            'status' => true,
        ]);

        return $answer;
    }
}
