<?php

namespace App\Support\Exports;

use App\Models\Budget;
use App\Models\CategoryBound;
use App\Models\Category;
use App\Services\BudgetService;
use Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AllBudgetsExport implements FromView, ShouldAutoSize {

  private $budgetService;

  public function __construct(BudgetService $budgetService)
  {
    $this->budgetService = $budgetService;
  }

	public function view(): View
  {
    $user = Auth::user();
    $table = [
      'header' => [],
      'body' => []
    ];

    $bounds = $this->budgetService->getUserBudgetsBounds();
    $categories = Category::where('user_id', $user->id)->get();

    foreach ($bounds as $bound) {
      if (!array_key_exists($bound->year, $table['header'])) {
        $table['header'][$bound->year] = [];
      }

      if (!in_array($bound->month, $table['header'][$bound->year])) {
        array_push($table['header'][$bound->year], $bound->month);
      }
    }

    foreach ($categories as $category) {
      
      if (!array_key_exists($category->name, $table['body'])) {
        $table['body'][$category->name] = [];
      }
      
      $categoryBounds = CategoryBound::where('category_id', $category->id)->get();
      $categoryBounds->sortBy('year');

      foreach ($categoryBounds as $bound) {
        if (!array_key_exists($bound->year, $table['body'][$category->name])) {
          $table['body'][$category->name][$bound->year] = collect();
        }
        ($table['body'][$category->name][$bound->year])->push($bound);
        ($table['body'][$category->name][$bound->year])->sortBy('month');
      }
    }

    return view('exports.budgets', [
        'table' => $table
    ]);
  }
}
