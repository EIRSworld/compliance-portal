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
                'name' => ['Snehal Sheth', 'Sumantra'],
                'to' => ['snehal.sheth@eirsworld.com','sumantra.banerjee@etgworld.com'],
                'cc' => ['bhagyashri.solanki@eirsworld.com','joveil.canete@etgworld.com','pranita.jain@etgworld.com','abhishek.jain@etgworld.com','nathan.govender@etgworld.com','gyana.sanbad@etgworld.com']
            ],
            'IVC' => [
                'name' => ['Alka Verma','Sumantra','Nolwenn','Kouakou N Goran Herve'],
                'to' => ['alka.verma@eirsworld.com','sumantra.banerjee@etgworld.com','nolwenn.allano@etgworld.com','herve.kouakou@eirsworld.com'],
                'cc' => ['deepak.goyal@etgworld.com','joveil.canete@etgworld.com','pranita.jain@etgworld.com','abhishek.jain@etgworld.com','nathan.govender@etgworld.com','gyana.sanbad@etgworld.com','mahesh.verulkar@etgworld.com']
            ],

            'Kenya' => [
                'name' => ['Christine Gikunda','Gerald Yiminyi','Nigel Pillay','Nolwenn'],
                'to' => ['christine.gikunda@etgworld.com','gerald.yiminyi@etgworld.com','nigel.pillay@etgworld.com','nolwenn.allano@etgworld.com'],
                'cc' => ['etglke.accounts@etgworld.com','sunil.pulavarthi@etgworld.com','joveil.canete@etgworld.com','pranita.jain@etgworld.com','abhishek.jain@etgworld.com','nathan.govender@etgworld.com','gyana.sanbad@etgworld.com']
            ],

            'South Africa' => [
                'name' => ['Nigel Pillay','Clinton Brown'],
                'to' => ['nigel.pillay@etgworld.com','clinton.brown@etgworld.com'],
                'cc' => ['preethi.s@etgworld.com','rakhee.bhowan@etgworld.com','joveil.canete@etgworld.com','pranita.jain@etgworld.com','abhishek.jain@etgworld.com','nathan.govender@etgworld.com','gyana.sanbad@etgworld.com']
            ],
            'Zambia' => [
                'name' => ['Issac','Snehal Sheth','Margaret Banda'],
                'to' => ['isaac.mkandawire@eirsworld.com','snehal.sheth@eirsworld.com','margaret.banda@eirsworld.com'],
                'cc' => ['faiz.ahmad@etgworld.com','joveil.canete@etgworld.com','pranita.jain@etgworld.com','abhishek.jain@etgworld.com','nathan.govender@etgworld.com','gyana.sanbad@etgworld.com']
            ],
            'Mozambique' => [
                'name' => ['Rakhee Bhowan'],
                'to' => ['rakhee.bhowan@etgworld.com'],
                'cc' => ['preethi.s@etgworld.com','joveil.canete@etgworld.com','pranita.jain@etgworld.com','abhishek.jain@etgworld.com','nathan.govender@etgworld.com','gyana.sanbad@etgworld.com']
            ],
            'Mauritius' => [
                'name' => ['Sumantra'],
                'to' => ['sumantra.banerjee@etgworld.com'],
                'cc' => ['vindhyashree.kn@etgworld.com','gyana.sanbad@etgworld.com','joveil.canete@etgworld.com','pranita.jain@etgworld.com','abhishek.jain@etgworld.com','nathan.govender@etgworld.com']
            ],
            'Malawi' => [
                'name' => ['Sumantra','Margaret Banda'],
                'to' => ['sumantra.banerjee@etgworld.com','margaret.banda@eirsworld.com'],
                'cc' => ['gyana.sanbad@etgworld.com','joveil.canete@etgworld.com','pranita.jain@etgworld.com','abhishek.jain@etgworld.com','nathan.govender@etgworld.com']
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
