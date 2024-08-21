<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User,Election,Vote,Answer,Question};
use App\Mail\VoteConfirmation;
use Illuminate\Support\Facades\Mail;

class QuestionController extends Controller
{
    public function elections()
    {
        $elections = Election::OrderBy('id','DESC')->where('is_active','1')->get();
        return view('user.elections.index')->with(compact('elections'));
    }

    public function vote_form($id)
    {
        $election = Election::findOrFail($id);
        $questions = $election->questions;

        $formattedQuestions = $questions->map(function ($question) {
            return [
                'id' => $question->id,
                'text' => $question->question,
                'type' => $question->type,
                'options' => $question->options->pluck('option')->toArray(),
                'range_min' => $question->range_min,
                'range_max' => $question->range_max
            ];
        });

        return view('user.elections.vote_form', [
            'election' => $election,
            'questions' => $formattedQuestions
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'election_id' => 'required|exists:elections,id',
            'answers' => 'required|array',
            'answers.*' => 'required'
        ]);

        $vote = Vote::create([
            'user_id' => auth()->id(),
            'election_id' => $validatedData['election_id'],
        ]);

        foreach ($validatedData['answers'] as $questionId => $answer) {
            $question = Question::find($questionId);

            if ($question) {
                if (is_array($answer)) {
                    foreach ($answer as $option) {
                        Answer::create([
                            'vote_id' => $vote->id,
                            'question_id' => $questionId,
                            'answer' => $option
                        ]);
                    }
                } else {
                    Answer::create([
                        'vote_id' => $vote->id,
                        'question_id' => $questionId,
                        'answer' => $answer
                    ]);
                }
            }
        }
        Mail::to(auth()->user()->email)->send(new VoteConfirmation($vote));
        return to_route('user.elections')->with('success', 'Your vote has been submitted successfully!');
    }

}
