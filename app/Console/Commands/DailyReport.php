<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create daily report at midnight for new posts and new users and send by mail to admin';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data['users'] = User::where('created_at', '>=', Carbon::now()->subDay())->get();
        $data['posts'] = Post::where('created_at', '>=', Carbon::now()->subDay())->get();

        foreach (Admin::all() as $admin)
            Mail::send('mails.report', $data, function($message) use ($admin) {
                $subject = 'Daily Report';
                $message->to($admin->email, $admin->name)->subject($subject);
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });

        return Command::SUCCESS;
    }
}
