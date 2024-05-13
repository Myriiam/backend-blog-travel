<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

      /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'categories';

    /**
     * Get the article(s) of this/these category(ies) - relationship 
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class);
        //OR return $this->belongsToMany('App\Models\Article');
    }
}
