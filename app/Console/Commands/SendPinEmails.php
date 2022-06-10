<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pin;
use Mail;
use Carbon\Carbon;

class SendPinEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send-pins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sent PIN code by Emaild to each user register when sent status is false (0)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tokens = Pin::where('sent', 0)
            ->where('used', 0)
            ->get();


        foreach ($tokens as $token) {

            $email = $token->user->email;

            // $email = $token->email;
            $code = $token->code;
            $user_id = $token->user_id;

            $user = $token->user;




            $this->sendEmail($email, $code, $user);

            // TokenDB::update('update users set votes = 100 where name = ?', ['John']);
            Pin::where('user_id', $user_id)
                ->where('sent', 0)
                ->update([
                    'sent' => 1,
                    'sent_at' => Carbon::now()
                ]);
            //send email after 3 seconds
            sleep(3);
        }
    }

    private function sendEmail($to_email, $code, $user)
    {
        //$lang = CmsLang::where('iso', $iso)->first();
        Mail::send(
            'emails.pin',
            [
                'code' => $code,
                'user' => $user,
            ], //['lang' => $lang],
            function ($m) use ($to_email) {
                $m->to($to_email);
                $m->subject('DRH-ESSALUD código de verificación');
            }
        );
    }
}
