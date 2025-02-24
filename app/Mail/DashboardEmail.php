<?php

namespace App\Mail;

use App\Models\CalendarYear;
use App\Models\Country;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DashboardEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
//    public function __construct(public $countryId, public $userNames)
//    {
//        //
//    }

    /**
     * Get the message envelope.
     */
//    public function envelope(): Envelope
//    {
//        $countryModel = Country::find($this->countryId);
//        $currentMonthYear = Carbon::now()->format('M-y');
//        return new Envelope(
//            subject: 'Compliance Report - ' . $countryModel->name . ' - YTD ' . $currentMonthYear,
//        );
//    }

    /**
     * Get the message content definition.
     */
//    public function content(): Content
//    {
//        $now = Carbon::now();
//        $currentYear = $now->year;
//        if ($now->format('Y-m-d') <= $currentYear . "-03-31") {
//            $previousYear = $currentYear - 1;
//            $financeYear = $previousYear . '-' . $currentYear;
//        } else {
//            $nextYear = $currentYear + 1;
//            $financeYear = $currentYear . '-' . $nextYear;
//        }
//        $calendarYear = CalendarYear::whereName($financeYear)->first();
//        $calendar_year_id = $calendarYear->id;
//
//        return new Content(
//            view: 'emails.dashboard-email',
//            with: [
//                'userNames' => implode(', ', $this->userNames),
//                'countryID' => $this->countryId,
//                'calendar_year_id' => $calendar_year_id,
//                'red' => null
//            ]
////            view: 'view.name',
//        );
//    }
//
//    /**
//     * Get the attachments for the message.
//     *
//     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
//     */
//    public function attachments(): array
//    {
//        return [];
//    }


    public $countryId;
    public $countryName;
    public $userNames;
    public $calendar_year_id;

    public function __construct($countryId, $userName)
    {
//        dd($countryId, $countryName);
        $this->countryId = $countryId;
        $countryModel = Country::find($this->countryId);
        $this->countryName = $countryModel->name;
        $this->userNames = $userName;
        $now = Carbon::now();
        $currentYear = $now->year;
        if ($now->format('Y-m-d') <= $currentYear . "-03-31") {
            $previousYear = $currentYear - 1;
            $financeYear = $previousYear . '-' . $currentYear;
        } else {
            $nextYear = $currentYear + 1;
            $financeYear = $currentYear . '-' . $nextYear;
        }
        $calendarYear = CalendarYear::whereName($financeYear)->first();
        $this->calendar_year_id = $calendarYear->id;
    }

    public function build()
    {
        // Generate PDF from view
        $pdf = Pdf::loadView('emails.dashboard-email-new', [
            'countryName' => $this->countryName,
            'userNames' => implode(', ', $this->userNames),
            'countryID' => $this->countryId,
            'calendar_year_id' => $this->calendar_year_id,
            'red' => null
        ])->setPaper('a4', 'portrait')
            ->setOptions([
                'margin-top' => 20,
                'margin-bottom' => 20,
                'margin-left' => 20,
                'margin-right' => 30,
            ]);

        return $this->subject("Dashboard Report for {$this->countryName}")
            ->view('emails.dashboard_pdf') // Email content view (optional)
            ->with([
                'countryName' => $this->countryName,
                'userNames' => implode(', ', $this->userNames),
                'countryID' => $this->countryId,
                'calendar_year_id' => $this->calendar_year_id,
                'red' => null
            ])
            ->attachData($pdf->output(), "Dashboard Report {$this->countryName}.pdf", [
                'mime' => 'application/pdf',
            ]);
    }
}
