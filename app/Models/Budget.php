<?php

namespace App\Models;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{

    protected $rules = [];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'year', 'month'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    protected $with = [];

    public function categories() {
      return $this->belongsToMany(Category::class);
    }

    public function bounds() {
        return $this->hasMany(CategoryBound::class);
    }

}
