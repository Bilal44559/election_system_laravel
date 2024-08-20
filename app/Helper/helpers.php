<?php
use App\Models\{User,Election,Vote,Answer,Question};
function userCastVote($election_id)
{
    $vote = Vote::where('user_id', auth()->id())->where('election_id', $election_id)->first();
    return $vote->id ?? false;
}
