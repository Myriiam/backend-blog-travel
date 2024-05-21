<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

//use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'description',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

      /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'users';

     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the artcile(s) of the user - relationship
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
        //OR return $this->hasMany('App\Models\Article');
    }

    /**
     * Get the article comment(s) of the user - relationship
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
        //OR return $this->hasMany('App\Models\Comment');
    }

     /**
     * Get the favorites(likes) article of the user - relationship
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
        //OR return $this->hasMany('App\Models\Favorite');
    }

}
