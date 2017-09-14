<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MinecraftAccount extends Model
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
    protected $primaryKey = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'last_username', 'user_id',

    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
