<?php

namespace App\Models;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CategoryBound extends Model
{

    protected $rules = [];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category_id', 'bound_in_cents', 'year', 'month'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    protected $with = ['category', 'expenses'];

    public function expenses() {
      return $this->hasMany(Expense::class, 'bound_id');
    }

    public function category() {
      return $this->belongsTo(Category::class);
    }

}
