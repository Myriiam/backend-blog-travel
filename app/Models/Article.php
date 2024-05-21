<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'continent',
        'country',
        //'main_picture',
        'image_url',
        'image_public_id',
    ];

      /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'articles';

    /**
     * Get the category/categories of the article - relationship 
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
        //OR return $this->belongsToMany('App\Models\Category');
    }

     /**
     * Get the user of the article(s) - relationship 
     */
    public function user()
    {
        return $this->belongsTo(User::class);
        //OR return $this->belongsTo('App\Models\User');
    }

     /**
     * Get the images of the article - relationship 
     */
    public function images()
    {
        return $this->hasMany(Image::class);
        //OR return $this->hasMany('App\Models\Image');
    } 

    /**
     * Get the comment(s) of the article - relationship 
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
        //OR return $this->hasMany('App\Models\Comment');
    }

    /**
     * Get the like(s) of the article - relationship 
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
        //OR return $this->hasMany('App\Models\Favorite');
    }

}
