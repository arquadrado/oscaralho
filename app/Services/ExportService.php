<?php

namespace App\Services;

use Auth;
use App\Models\Category;
use App\Support\Exports\AllBudgetsExport;
use Carbon\Carbon;
use Excel;

class ExportService
{
  public function exportAll() {
    return Excel::download(new AllBudgetsExport, 'all-budgets.xlsx');
  }

  public function exportYear($year) {

  }

  public function exportMonth($year, $month) {

  }
}