<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Election;

class IndexController extends Controller
{
    public function index()
    {
        $elections = Election::with(['questions.answers.vote'])
        ->where('end_date','<',date('Y-m-d H:i:s'))
        ->OrderBy('id','DESC')
        ->get();

        $chartData = $elections->map(function ($election) {
            return [
                'title' => $election->title,
                'questions' => $election->questions->map(function ($question) {
                    return [
                        'question' => $question->question,
                        'answers' => $question->answers->groupBy('answer')->map(function ($answers, $answer) {
                            return [
                                'answer' => $answer,
                                'count' => $answers->count()
                            ];
                        })->values()
                    ];
                })
            ];
        });

        return view('web.index', compact('chartData'));
    }

    public function election_page($slug)
    {
        $election = Election::with(['questions'])->where('slug',$slug)->first();
        return view('web.election_page', compact('election'));
    }
}
