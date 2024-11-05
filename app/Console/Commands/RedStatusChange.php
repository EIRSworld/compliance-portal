<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RedStatusChange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:red-status-change';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $compliancePrimarySubMenus = \App\Models\CompliancePrimarySubMenu::where('status','=','Amber')->where('is_uploaded','=',0)->get();
        foreach ($compliancePrimarySubMenus as $compliancePrimarySubMenu) {

            $current_date = \Carbon\Carbon::now()->format('Y-m-d');
            $due_date = \Carbon\Carbon::parse($compliancePrimarySubMenu->due_date)->format('Y-m-d');
            if ($current_date > $due_date) {
                $compliancePrimarySubMenu->update([
                    'status' => 'Red',
                ]);
            }
        }
    }
}
