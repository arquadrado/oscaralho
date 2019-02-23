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

        // $categories = Category::limit(3)->get();
        $categories = Category::all();

        foreach($categories as $category) {
            $bound = $category->bounds()
                                ->where('year', Carbon::now()->format('Y'))
                                ->where('month', Carbon::now()->format('m'))
                                ->first();
            if (is_null($bound)) {
                $bound = CategoryBound::create([
                    'category_id' => $category->id,
                    'bound_in_cents' => 0,
                    'year' => Carbon::now()->format('Y'),
                    'month' => Carbon::now()->format('m'),
                ]);
            }
        }
        
        foreach($categories as $category) {
          $bound = $category->bounds()
                              ->where('year', '2019')
                              ->where('month', '01')
                              ->first();
          if (is_null($bound)) {
              $bound = CategoryBound::create([
                  'category_id' => $category->id,
                  'bound_in_cents' => 0,
                  'year' => '2019',
                  'month' => '01',
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

    public function deleteExpense() {
        $expense = Expense::find(request()->get('id'));
        $expense->delete();
        
        return response()->json(['message' => 'Expense was deleted'], 200);
    }

    public function updateBound() {
        
        $category = Category::find(request()->get('categoryId'));

        if (is_null($category)) {

            return response()->json(['error' => 'no category'], 404);
        }

        $bound = $category->bounds()->find(request()->get('boundId'));
        $bound->bound_in_cents = request()->get('value');
        $bound->save();

        return response()->json(['bound' => $bound], 200);
        
    }
}
