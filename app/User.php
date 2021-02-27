<?php

namespace App;


use App\Notifications\ResetPasswordNotification;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'wallet',
        'transaction_pin',
        'accessibility',
        'email_confirmed',
        'phone_verified',
        'unique_id',
        'active',
        'bonus_wallet',
        'password',
        'username',
        'referrer_id',
        'monnify_account_number',
        'monnify_bank_name',
        'monnify_bank_code',
        'monnify_collection_channel',
        'monnify_reservation_channel'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function data_transactions(){
        return $this->hasMany(DataTransaction::class);
    }

    public function cable_transactions(){
        return $this->hasMany(CableTransaction::class);
    }

    public function airtime_transactions(){
        return $this->hasMany(AirtimeTransaction::class);
    }
    public function electricity_transactions(){
        return $this->hasMany(ElectricityTransaction::class);
    }

    public function wallet_transactions(){
        return $this->hasMany(WalletTransaction::class);
    }


    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id', 'id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referrer_id', 'id');
    }



    public static function boot() {
        parent::boot();
        self::deleting(function($user) {
            $user->data_transactions()->each(function($data){
                $data->delete();
            });
            $user->cable_transactions()->each(function($cable) {
                $cable->delete();
            });
            $user->airtime_transactions()->each(function($airtime) {
                $airtime->delete();
            });
            $user->electricity_transactions()->each(function($electricity) {
                $electricity->delete();
            });
            $user->wallet_transactions()->each(function($transaction) {
                $transaction->delete();
            });
        });
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
