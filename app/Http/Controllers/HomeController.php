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
            $bound = CategoryBound::where('year', Carbon::now()->format('Y'))
                                ->where('month', Carbon::now()->format('m'))
                                ->where('category_id', $category->id)
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
          $bound = CategoryBound::where('year', '2019')
                              ->where('month', '01')
                              ->where('category_id', $category->id)
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

      foreach($categories as $category) {
        $bound = CategoryBound::where('year', '2019')
                            ->where('month', '04')
                            ->where('category_id', $category->id)
                            ->first();
        if (is_null($bound)) {
            $bound = CategoryBound::create([
                'category_id' => $category->id,
                'bound_in_cents' => 0,
                'year' => '2019',
                'month' => '04',
            ]);
        }
    }

    foreach($categories as $category) {
      $bound = CategoryBound::where('year', '2019')
                          ->where('month', '05')
                          ->where('category_id', $category->id)
                          ->first();
      if (is_null($bound)) {
          $bound = CategoryBound::create([
              'category_id' => $category->id,
              'bound_in_cents' => 0,
              'year' => '2019',
              'month' => '05',
          ]);
      }
  }

      foreach($categories as $category) {
        $bound = CategoryBound::where('year', '2018')
                            ->where('month', '12')
                            ->where('category_id', $category->id)
                            ->first();
        if (is_null($bound)) {
            $bound = CategoryBound::create([
                'category_id' => $category->id,
                'bound_in_cents' => 0,
                'year' => '2018',
                'month' => '12',
            ]);
        }
      }

      foreach($categories as $category) {
        $bound = CategoryBound::where('year', '2017')
                            ->where('month', '12')
                            ->where('category_id', $category->id)
                            ->first();
        if (is_null($bound)) {
            $bound = CategoryBound::create([
                'category_id' => $category->id,
                'bound_in_cents' => 0,
                'year' => '2017',
                'month' => '12',
            ]);
        }
      }

      foreach($categories as $category) {
        $bound = CategoryBound::where('year', '2014')
                            ->where('month', '12')
                            ->where('category_id', $category->id)
                            ->first();
        if (is_null($bound)) {
            $bound = CategoryBound::create([
                'category_id' => $category->id,
                'bound_in_cents' => 0,
                'year' => '2014',
                'month' => '12',
            ]);
        }
      }
      
      $bounds = CategoryBound::all();

      return view('home', [
          'user' => $user,
          'token' => $token,
          'bounds' => $bounds
      ]);
    }

    public function addExpense() {
        $expense = Expense::create([
            'bound_id' => request()->get('boundId'),
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
        $bound = CategoryBound::find(request()->get('categoryId'));

        if (is_null($bound)) {

            return response()->json(['error' => 'no bound to update'], 404);
        }

        $bound->bound_in_cents = request()->get('value');
        $bound->save();

        return response()->json(['bound' => $bound], 200);
        
    }
}
