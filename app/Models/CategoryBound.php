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
    protected $fillable = ['budget_id', 'category_id', 'bound_in_cents'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
    protected $appends = ['year', 'month'];

    protected $with = ['category', 'expenses'];

    public function expenses() {
      return $this->hasMany(Expense::class, 'bound_id');
    }

    public function budget() {
      return $this->belongsTo(Budget::class);
    }

    public function category() {
      return $this->belongsTo(Category::class);
    }
    
    // mutators

    public function getYearAttribute() {
      return $this->budget->year;
    }

    public function getMonthAttribute() {
      return $this->budget->month;
    }

}
