<?php

namespace App\Support\Exports;

use App\Models\Budget;
use App\Models\CategoryBound;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AllBudgetsExport implements FromView, ShouldAutoSize {

	public function view(): View
    {
        $table = [
          'header' => [],
          'body' => []
        ];

        $bounds = CategoryBound::all();
        $categories = Category::all();

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
