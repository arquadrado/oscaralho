<?php

namespace App\Services;

use Auth;
use App\Models\Category;
use App\Services\BudgetService;
use App\Support\Exports\AllBudgetsExport;
use Carbon\Carbon;
use Excel;

class ExportService
{
  private $budgetService;

  public function __construct(BudgetService $budgetService)
  {
    $this->budgetService = $budgetService;
  }

  public function exportAll() {
    return Excel::download(new AllBudgetsExport($this->budgetService), 'all-budgets.xlsx');
  }

  public function exportYear($year) {

  }

  public function exportMonth($year, $month) {

  }
}