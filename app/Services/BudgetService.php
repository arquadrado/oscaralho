<?php

namespace App\Services;

use Auth;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Expense;
use App\Models\CategoryBound;
use Carbon\Carbon;

class BudgetService
{
  private $categoryService;

  public function __construct(CategoryService $categoryService)
  { 
      $this->categoryService = $categoryService;
  }

  public function initializeCurrentMonthBudget() {
    $user = Auth::user();
    $categories = $this->categoryService->getUserCategories();
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
    return $budget;
  }

  public function getUserBudgets() {
    $user = Auth::user();
    return Budget::where('user_id', $user->id)->get();
  }

  public function getUserBudgetsBounds() {
    $budgets = $this->getUserBudgets();
    $bounds = collect();

    foreach($budgets as $budget) {
      $bs = CategoryBound::where('budget_id', $budget->id)->get();
      
      $bounds = $bounds->merge($bs);
    }

    return $bounds;
  }

  public function save($data) {
    $user = Auth::user();
    $requestData = [
      'month' => $data['month'],
      'year' => $data['year']
    ];

    $categoriesIds = $data['categories'];
    
    if (!isset($data['id'])) {
      
      $budget = Budget::where('year', $requestData['year'])
        ->where('month', $requestData['month'])
        ->where('user_id', $user->id)
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
        return $budget;
      }

      abort(400, 'Something went wrong');
    }

    $budget = Budget::find($data['id']);   
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

    return $budget;
  }
  
  public function delete($data) {
    $budget = Budget::find($data['id']);
    $budget->delete();
  }

  public function addExpense($data) {
    if (isset($data['id'])) {
      $expense = Expense::find($data['id']);

      if (is_null($expense)) {
        abort(404, 'Could not find expense');
      }

      $expense->update($data);
      return $expense;
    }

    $expense = Expense::create([
      'bound_id' => $data['bound_id'],
      'value'       => $data['value'],
    ]);
    
    return $expense;
  }

  public function deleteExpense($data) {
    $expense = Expense::find($data['id']);
    $expense->delete();
  }

  public function updateBound($data) {
    $bound = CategoryBound::find($data['boundId']);

    if (is_null($bound)) {
      abort(404, 'No bound to update');
    }

    $bound->bound_in_cents = $data['value'];
    $bound->save();

    return $bound;    
  }

}