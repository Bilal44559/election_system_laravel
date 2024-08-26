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
        $election = Election::with(['questions.options'])->where('slug', $slug)->first();
        $questions = $election->questions->where('is_active', '1');

        $formattedQuestions = $questions->map(function ($question) {
            $totalVotes = calTotalQuestionVotes($question->id);

            if ($question->type === 'text') {
                $topAnswers = $this->getTopAnswers($question->id, 5);
                $options = $topAnswers->map(function ($answer) use ($totalVotes) {
                    $percent = $totalVotes > 0 ? ($answer->votes / $totalVotes) * 100 : 0;
                    return [
                        'text' => $answer->answer,
                        'votes' => $answer->votes,
                        'percent' => round($percent, 2),
                    ];
                });
            } elseif ($question->type === 'range') {
                $rangeData = $this->getRangeData($question->id);
                $options = $rangeData->map(function ($data) use ($totalVotes) {
                    $percent = $totalVotes > 0 ? ($data->votes / $totalVotes) * 100 : 0;
                    return [
                        'text' => $data->answer,
                        'votes' => $data->votes,
                        'percent' => round($percent, 2),
                    ];
                });
            } else {
                $options = $question->options->map(function ($option) use ($totalVotes) {
                    $vote_option = calTotalOptionVoteForQues($option->id);
                    $percent = $totalVotes > 0 ? ($vote_option / $totalVotes) * 100 : 0;
                    return [
                        'text' => $option->option,
                        'votes' => $vote_option,
                        'percent' => round($percent, 2),
                    ];
                });
            }

            return [
                'id' => $question->id,
                'text' => $question->question,
                'type' => $question->type,
                'options' => $options,
                'range_min' => $question->range_min,
                'range_max' => $question->range_max,
                'is_active' => $question->is_active
            ];
        });

        $formattedQuestionsNew = array_values($formattedQuestions->toArray());

        return view('web.election_page', [
            'election' => $election,
            'questions' => $formattedQuestionsNew
        ]);
    }

    private function getTopAnswers($questionId, $limit)
    {
        return \DB::table('answers')
            ->select('answer', \DB::raw('COUNT(*) as votes'))
            ->where('question_id', $questionId)
            ->groupBy('answer')
            ->orderBy('votes', 'desc')
            ->limit($limit)
            ->get();
    }

    private function getRangeData($questionId)
    {
        // Fetch range data with vote counts
        return \DB::table('answers')
            ->select('answer', \DB::raw('COUNT(*) as votes'))
            ->where('question_id', $questionId)
            ->groupBy('answer')
            ->get();
    }


}
