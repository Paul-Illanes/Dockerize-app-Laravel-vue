<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mail;
use App\Models\User;

class Pin extends Model
{
    //
    const EXPIRATION_TIME = 15;
    protected $table = 'tokens';
    protected $fillable = [
        'code',
        'user_id',
        'used',
        'sent',
        'sent_at',
    ];


    public function __construct(array $attributes = [])
    {
        if (!isset($attributes['code'])) {
            $attributes['code'] = $this->generateCode();
        }

        parent::__construct($attributes);
    }

    /**
     * Generate a six digits code
     * @param int $codeLength
     * @return string
     */
    public function generateCode($codeLength = 4)
    {
        $min = pow(10, $codeLength);
        $max = $min * 10 - 1;
        $code = mt_rand($min, $max);

        return $code;
    }

    public function sendCode()
    {

        if (!$this->user) {
            throw new \Exception("Usuario sin PIN asignado.");
        }

        if (!$this->code) {
            $this->code = $this->generateCode();
        }
        //dd($this->user->email);
        //$this->sendEmail($this->user->email,$this->code) ;

        return true;
    }

    private function sendEmail($to_email, $code)
    {
        //$lang = CmsLang::where('iso', $iso)->first();
        Mail::send(
            'emails.pin',
            ['code' => $code], //['lang' => $lang],
            function ($m) use ($to_email) {
                $m->to($to_email);
                $m->subject('DRH_ESSALUD codigo de verificacion');
            }
        );
    }

    /**
     * User token relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isValid()
    {
        return !$this->isUsed();
        //&& !$this->isExpired();
    }

    public function isUsed()
    {
        return $this->used;
    }

    public function isExpired()
    {
        return $this->created_at->diffInMinutes(Carbon::now()) > static::EXPIRATION_TIME;
    }
}
