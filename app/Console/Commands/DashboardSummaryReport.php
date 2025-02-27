<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DashboardSummaryReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dashboard-summary-report';

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
        $countries = [
            'Tanzania' => [
                'name' => ['Joveil'],
                'to' => [ 'harrish.gunasekaran@eirsworld.com'],
                'cc' => ['harrish.gunasekaran@eirsworld.com']
            ],
            'IVC' => [
                'name' => ['Joveil'],
                'to' => ['harrish.gunasekaran@eirsworld.com'],
                'cc' => ['harrish.gunasekaran@eirsworld.com']
            ],

            'Kenya' => [
                'name' => ['Joveil'],
                'to' => ['harrish.gunasekaran@eirsworld.com'],
                'cc' => ['harrish.gunasekaran@eirsworld.com']
            ],

            'South Africa' => [
                'name' => ['Joveil'],
                'to' => ['harrish.gunasekaran@eirsworld.com'],
                'cc' => ['harrish.gunasekaran@eirsworld.com']
            ],
            'Zambia' => [
                'name' => ['Joveil'],
                'to' => ['harrish.gunasekaran@eirsworld.com'],
                'cc' => ['harrish.gunasekaran@eirsworld.com']
            ],
            'Mozambique' => [
                'name' => ['Joveil'],
                'to' => ['harrish.gunasekaran@eirsworld.com'],
                'cc' => ['harrish.gunasekaran@eirsworld.com']
            ],
            'Mauritius' => [
                'name' => ['Joveil'],
                'to' => ['harrish.gunasekaran@eirsworld.com'],
                'cc' => ['harrish.gunasekaran@eirsworld.com']
            ],
            'Malawi' => [
                'name' => ['Joveil'],
                'to' => ['harrish.gunasekaran@eirsworld.com'],
                'cc' => ['harrish.gunasekaran@eirsworld.com']
            ],
        ];

        foreach ($countries as $key => $country) {
            $countryModel = Country::whereName($key)->first();

            if ($countryModel) {
                Mail::to($country['to'])->cc($country['cc'])->send(new \App\Mail\DashboardEmail($countryModel->id, $country['name']));

                \Illuminate\Support\Facades\Log::info('Email sent successfully.', [
                    'to' => $country['to'],
                    'cc' => $country['cc'] ?? 'No CC',
                    'country' => $key,
                ]);
            }
        }
    }
}
