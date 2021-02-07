<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Whtht\PerfectlyCache\Traits\PerfectlyCachable;

class Article extends Model
{
    use HasApiTokens, Notifiable, PerfectlyCachable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'view_count', 'user_id', 'publish'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function rates()
    {
        return $this->belongsToMany(Vote::class);
    }
}
