<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\ElectionReminderMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Election;
use App\Models\User;

class SendElectionReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send-election-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $elections = Election::where('start_date', '<=', now()->addDays(1))->get();

        foreach ($elections as $election) {
            $users = User::where('type', 'user')->where('is_active','1')->get();
            foreach ($users as $user) {
                $voting_link = route('user.vote_form', $election->id);

                Mail::to($user->email)->send(new ElectionReminderMail($election, $user, $voting_link));
            }
        }
    }
}
