<?php

namespace App\Livewire;

use App\Models\Payroll;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class BankReport extends Component
{
    public $select_month, $select_year;
    public $month, $year;

    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
    }

    public function render()
    {

        $this->select_year = Payroll::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();

        $this->select_month = Payroll::select(DB::raw('MONTH(date) as month'))->whereYear('date', $this->year)
            ->distinct()
            ->pluck('month')
            ->toArray();

        return view('livewire.bank-report', [
            'select_month' => $this->select_month,
            'select_year' => $this->select_year
        ]);
    }
}
