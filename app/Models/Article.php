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
        'main_picture',
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
}
