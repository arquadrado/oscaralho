<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Category;
use App\Models\Expense;
use App\Models\CategoryBound;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if (is_null($user)) {
            return view('error');
        }
        
        $token = csrf_token();

        $categories = Category::all();

        foreach($categories as $category) {
            $bound = $category->bounds()
                                ->where('period', Carbon::now()->format('Y-m'))
                                ->first();
            if (is_null($bound)) {
                $bound = CategoryBound::create([
                    'category_id' => $category->id,
                    'bound_in_cents' => 0,
                    'period' => Carbon::now()->format('Y-m'),
                ]);
            }
        }

        return view('home', [
            'user' => $user,
            'token' => $token,
            'categories' => $categories
        ]);
    }

    public function addExpense() {
        $expense = Expense::create([
            'category_id' => request()->get('categoryId'),
            'value'       => request()->get('value'),
        ]);
        
        return response()->json(['expense' => $expense], 200);

    }
}
