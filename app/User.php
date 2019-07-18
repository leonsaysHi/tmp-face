<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const ROLES = ['tester'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    protected static $logAttributes = ['name', 'email', 'updated_at'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', "api_token"
    ];

    public function getApiTokenAttribute($value)
    {
        if (!$value) {
            $value = Str::random(60);
            $this->api_token = $value;
            $this->save();
        }
        return $value;
    }

    public function isSuperAdmin()
    {
        return $this->is_super_admin;
    }

    /**
     * A user can have many preferences
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function preferences()
    {
        return $this->belongsToMany(Preference::class);
    }

    /**
     * A user can have many search records
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function searches()
    {
        return $this->hasMany(Search::class);
    }
}
