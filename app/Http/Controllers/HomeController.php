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
      
      $categories = Category::all();
      $budget = Budget::where('year', Carbon::now()->format('Y'))
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
      
      $budgets = Budget::all();
      $bounds = CategoryBound::all();

      return view('home', [
          'user' => $user,
          'token' => $token,
          'budgets' => $budgets,
          'bounds' => $bounds,
          'categories' => $categories
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

  public function saveBudget() {
    $budget = Budget::find(request()->get('id'));
    $requestData = [
      'month' => request()->get('month'),
      'year' => request()->get('year')
    ];

    $categoriesIds = request()->get('categories');
    
    if (is_null($budget)) {
      
      $budget = Budget::where('year', $requestData['year'])
        ->where('month', $requestData['month'])
        ->first();
                      
      if (is_null($budget)) {

        $budget = Budget::create(array_merge(['user_id' => Auth::user()->id], $requestData));
  
        $budget->categories()->sync($categoriesIds);
        $budget->bounds->each(function($bound) use ($categoriesIds) {
          if (!in_array($bound->category_id, $categoriesIds)) {
            $bound->delete();
          }
        });
        
        foreach($categoriesIds as $categoryId) {
          $bound = $budget->bounds()->where('category_id', $categoryId)->first();
          if (is_null($bound)) {
            $category = Category::find($categoryId);
            $bound = CategoryBound::create([
              'category_id' => $category->id,
              'budget_id' => $budget->id,
              'bound_in_cents' => $category->default_bound_in_cents
              ]);
            }
          }
          
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
        $budget->load('bounds');
        return response()->json(['budget' => $budget, 'bounds' => $budget->bounds], 200);
      }

      return response()->json(['error' => 'Budget for the same period already exist'], 400);
    }
        
    $budget->update($requestData);
    $budget->categories()->sync($categoriesIds);
    
    $budget->bounds->each(function($bound) use ($categoriesIds) {
      if (!in_array($bound->category_id, $categoriesIds)) {
        $bound->delete();
      }
    });
    
    foreach($categoriesIds as $categoryId) {
      $bound = $budget->bounds()->where('category_id', $categoryId)->first();
      if (is_null($bound)) {
        $category = Category::find($categoryId);
        $bound = CategoryBound::create([
          'category_id' => $category->id,
          'budget_id' => $budget->id,
          'bound_in_cents' => $category->default_bound_in_cents
          ]);
          
      }
    }

    $budget->load('bounds');

    return response()->json(['budget' => $budget, 'bounds' => $budget->bounds], 200);
  }
    
  public function deleteBudget() {
    $budget = Budget::find(request()->get('id'));
    $budget->delete();
    
    return response()->json(['message' => 'Budget was deleted'], 200);
  }
}
