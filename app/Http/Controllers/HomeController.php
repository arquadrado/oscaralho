<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Budget;
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
      
      $categories = Category::where('user_id', $user->id)->get();
      $budget = Budget::where('user_id', $user->id)
                      ->where('year', Carbon::now()->format('Y'))
                      ->where('month', Carbon::now()->format('m'))
                      ->first();

      if (is_null($budget) && !$categories->isEmpty()) {
        $budget = Budget::create([
          'user_id' => $user->id,
          'year' => Carbon::now()->format('Y'),
          'month' => Carbon::now()->format('m'),
        ]);

        $categoriesIds = $categories->map(function($category) {
          return $category->id;
        })->toArray();
        
        $budget->categories()->sync($categoriesIds);
        
        foreach($budget->categories as $category) {
            $bound = CategoryBound::where('budget_id', $budget->id)
                                ->where('category_id', $category->id)
                                ->first();
            if (is_null($bound)) {
                $bound = CategoryBound::create([
                    'category_id' => $category->id,
                    'budget_id' => $budget->id,
                    'bound_in_cents' => $category->default_bound_in_cents
                ]);
            }
        }
      }
      
      $budgets = Budget::where('user_id', $user->id)->get();
      $bounds = collect();

      foreach($budgets as $budget) {
        $bs = CategoryBound::where('budget_id', $budget->id)->get();
        
        $bounds = $bounds->merge($bs);
      }
      

      return view('home', [
          'user' => $user,
          'token' => $token,
          'budgets' => $budgets,
          'bounds' => $bounds,
          'categories' => $categories
      ]);
    }

  public function saveCategory() {
    $category = Category::find(request()->get('id'));
    $requestData = request()->all();
    $requestData['default_bound_in_cents'] = (int)$requestData['default_bound'] * 100;
    
    if (is_null($category)) {
      $category = Category::create(array_merge(['user_id' => Auth::user()->id], $requestData));
      return response()->json(['category' => $category], 200);
    }

    $category->update($requestData);

    return response()->json(['category' => $category], 200);
  }
    
  public function deleteCategory() {
    $category = Category::find(request()->get('id'));
    $category->delete();
    
    return response()->json(['message' => 'Category was deleted'], 200);
  }
}
