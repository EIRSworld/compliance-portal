<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ManagementExport implements FromView
{
    public function __construct($records)
    {
        $this->records = $records;
    }

    public function view(): View
    {
        return view('exports.management', [
            'managements' => $this->records
        ]);
    }
}
