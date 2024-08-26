<?php
use App\Models\{User,Election,Vote,Answer,Question,QuestionOption};
function userCastVote($election_id)
{
    $vote = Vote::where('user_id', auth()->id())->where('election_id', $election_id)->first();
    return $vote->id ?? false;
}

function showAllElections()
{
    $elections = Election::OrderBy('id','DESC')->where('is_active','1')->get();
    return $elections;
}

function calTotalQuestionVotes($question_id)
{
    $question = Question::findOrFail($question_id);
    $totalVotes = count($question->answers);
    return $totalVotes;
}

function calTotalOptionVoteForQues($option_id)
{
    $option = QuestionOption::findOrFail($option_id);
    $answer = Answer::where('question_id', $option->question_id)->where('answer', $option->option)->count();
    return $answer;
}
