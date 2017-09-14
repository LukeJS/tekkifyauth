<?php

namespace App;

use App\Scopes\ExpiredScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
     public $incrementing = false;
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'code';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'user_id',

    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // code config is in minutes, ExpiredScope takes seconds
        $codeExpiryTime = 60 * config('tekkifyauth.code_expiry');

        static::addGlobalScope(new ExpiredScope($codeExpiryTime));
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
