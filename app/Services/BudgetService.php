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
  public function save($data) {
    
    $requestData = [
      'month' => $data['month'],
      'year' => $data['year']
    ];

    $categoriesIds = $data['categories'];
    
    if (!isset($data['id'])) {
      
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
    $expense = Expense::create([
      'bound_id' => $data['boundId'],
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