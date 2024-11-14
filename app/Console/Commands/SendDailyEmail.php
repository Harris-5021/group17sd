<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewMemberNotify;

class SendDailyEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a daily email to a specific address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recipientEmail = 'harrisfiaz3@gmail.com'; // Replace with the email you want to send to
        $userName = 'harris fiaz'; // Optional name for personalization

        // Send the email
        Mail::to($recipientEmail)->send(new NewMemberNotify());

        $this->info('Daily email sent successfully!');
    }
}
