<?php

namespace App\Exports;

use App\Models\ComplianceEmail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ComplianceEmailExport implements FromView,ShouldAutoSize
{
    public function __construct($records)
    {
        $this->records = $records;
    }


    public function view(): View
    {
        return view('exports.compliance-email-export', [
            'compliances' => $this->records,
        ]);
    }
}
