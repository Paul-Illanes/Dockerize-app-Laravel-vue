<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;

class SendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email manual';

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
        //return 0;
        $this->send_email('camilo.reynaga@gmail.com');
    }

    private function send_email($to_email){
        //$lang = CmsLang::where('iso', $iso)->first();
        Mail::send(
            'emails.pin',
            ['code'=>'231'],//['lang' => $lang],
            function($m) use($to_email){
                $m->to($to_email);
                $m->subject('DRH_ESSALUD codigo de verificacion');
            }
        );
    }
}
