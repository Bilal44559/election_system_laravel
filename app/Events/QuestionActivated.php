<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuestionActivated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $question;

    /**
     * Create a new event instance.
     */
    public function __construct($question)
    {
        // $this->question = $question;
        $this->question = [
            'id' => $question->id,
            'text' => $question->question,
            'type' => $question->type,
            'options' => $question->options->pluck('option')->toArray(),
            'range_min' => $question->range_min,
            'range_max' => $question->range_max,
            'is_active' => $question->is_active
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // return [
        //     new Channel('question-channel'),
        // ];
        return ['question-channel'];
    }
}
