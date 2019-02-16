<?php

namespace App\Models;

use Auth;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $rules = [];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'name', 'description', 'expense'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    protected $with = ['expenses', 'bounds'];

    public function expenses() {
        return $this->hasMany(Expense::class);
    }
    
    public function bounds() {
        return $this->hasMany(CategoryBound::class);
    }

}
