<x-filament-widgets::widget>

{{--    <h1 style="font-weight: bold;">Dashboard Summary</h1>--}}
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        thead th {
            background-color: #4CAF50;
            color: white;
        }
        tbody td {
            background-color: #f2f2f2;
        }
        tbody tr:nth-child(even) td {
            background-color: #fbfbfb;
        }
        .yes {
            background-color: #77ad5f;
            color: white;
        }
        .no {
            background-color: #ffadad;
            color: white;
        }
        .due-date {
            background-color: #fce4d5;
            color: #333;
        }
        tr:hover {
            background-color: #ddd;
        }
        .comment {
            font-style: italic;
            color: #555;
        }
    </style>
    {{ $this->form }}

    <table style="width: 1300px;">
        <thead>
        <tr style="background-color: #ffffff">
            <td class="text-center font-bold" colspan="13"><b>Dashboard Summary</b>
                @if($this->calendar_year_id)
                <a href="{{ route('report.dashboard-summary', [$this->calendar_year_id]) }} }}" style="padding: 5px;" target="_blank">
                    <x-filament::button >
                        Download
                    </x-filament::button>
                </a>
                @endif

            </td>
        </tr>
        <tr style="background-color: #cbcbcb;font-size: 13px;">
            <th style="padding: 12px;">ENTITY</th>
            <th colspan="4">PI AND FIDELITY</th>
            <th colspan="2">AUDITED FINANCIALS</th>
            <th colspan="3">COMPANY REGISTRATION</th>
            <th colspan="3">LICENCE</th>
            <th colspan="2">BROKER QTR SUBMISSIONS</th>
            <th>ANNUAL RETURN</th>
{{--            <th>PO DOCS</th>--}}
{{--            <th>EMPLOYEE DOCS</th>--}}
{{--            <th>BANK ACC'S</th>--}}
{{--            <th>POLICIES</th>--}}
{{--            <th>CLIENT DOCS</th>--}}
{{--            <th>AGENCIES</th>--}}
        </tr>

        </thead>
        <tbody>

        <tr style="font-size: 12px;font-weight: bold">
            <td style="padding: 12px;"></td>
            <td>PI</td>
            <td>RENEWAL</td>
            <td>FIDELITY</td>
            <td>RENEWAL</td>
            <td>UPLOAD</td>
            <td>DUE DATE</td>
            <td>UPLOAD</td>
            <td>DUE DATE</td>
            <td>Comments</td>
            <td>UPLOAD</td>
            <td>DUE DATE</td>
            <td>Comments</td>
            <td>UPLOAD</td>
            <td>DUE DATE</td>
            <td>DUE DATE</td>
{{--            <td>UPLOAD</td>--}}
{{--            <td>UPLOAD</td>--}}
{{--            <td>UPLOAD</td>--}}
{{--            <td>UPLOAD</td>--}}
{{--            <td>UPLOAD</td>--}}
{{--            <td>UPLOAD</td>--}}
        </tr>
        @foreach($countries as $country)
                <?php
                $uploadDocumentsPi = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$this->calendar_year_id)->whereName('PI Cover')->first();
                $uploadDocumentsFidelity = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$this->calendar_year_id)->whereName('Fidelity')->first();
                $uploadDocumentsAudit = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$this->calendar_year_id)->whereName('Audited Financials')->first();
                $uploadDocumentsRegistration = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$this->calendar_year_id)->whereName('Registration Documents')->first();
                $uploadDocumentsLicence = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$this->calendar_year_id)->whereName('Proof of Payment')->first();
                $uploadDocumentsBroker = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$this->calendar_year_id)->whereName('Broker Qtr Submissions')->first();
                $uploadDocumentsAnnual = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$this->calendar_year_id)->whereName('Annual Return')->first();
                ?>
            <tr>
                <td style="width: 9%">{{$country->name}}</td>
                @if($uploadDocumentsPi && $uploadDocumentsPi->is_uploaded == 1)
                    <td style="font-size: 12px;background-color: #77ad5f;width: 3%;">YES</td>
                @else
                    <td style="font-size: 12px;width: 3%;"></td>
                @endif
                @if($uploadDocumentsPi)
                    <td style="font-size: 12px;background-color: #fce4d5;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsPi->expired_date)->format('d-m-Y')}}</td>
                @else
                    <td style="font-size: 12px;width: 3%;"></td>
                @endif
                @if($uploadDocumentsFidelity && $uploadDocumentsFidelity->is_uploaded == 1)
                    <td style="font-size: 12px;background-color: #77ad5f;width: 3%;">YES</td>
                @else
                    <td style="font-size: 12px;width: 3%;"></td>
                @endif
                @if($uploadDocumentsFidelity)
                    <td style="font-size: 12px;background-color: #fce4d5;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsFidelity->expired_date)->format('d-m-Y')}}</td>
                @else
                    <td style="font-size: 12px;width: 3%;"></td>
                @endif
                @if($uploadDocumentsAudit && $uploadDocumentsAudit->is_uploaded == 1)
                    <td style="font-size: 12px;background-color: #77ad5f;width: 3%;">YES</td>
                @else
                    <td style="font-size: 12px;width: 3%;"></td>
                @endif
                @if($uploadDocumentsAudit)
                    <td style="font-size: 12px;background-color: #fce4d5;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsAudit->expired_date)->format('d-m-Y')}}</td>
                @else
                    <td style="font-size: 12px;width: 3%;"></td>
                @endif

                @if($uploadDocumentsRegistration && $uploadDocumentsRegistration->is_uploaded == 1)
                    <td style="font-size: 12px;background-color: #77ad5f;width: 3%;">YES</td>
                @else
                    <td style="font-size: 12px;width: 3%;"></td>
                @endif
                @if($uploadDocumentsRegistration)
                    <td style="font-size: 12px;background-color: #fce4d5;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsRegistration->expired_date)->format('d-m-Y')}}</td>
                @else
                    <td style="font-size: 12px;width: 3%;"></td>
                @endif
                <td style="width: 6%;font-size: 12px;">{!! $uploadDocumentsRegistration->upload_comment !!}</td>

                @if($uploadDocumentsLicence && $uploadDocumentsLicence->is_uploaded == 1)
                    <td style="font-size: 12px;background-color: #77ad5f;width: 3%;">YES</td>
                @else
                    <td style="font-size: 12px;width: 3%;"></td>
                @endif
                @if($uploadDocumentsLicence)
                    <td style="font-size: 12px;background-color: #fce4d5;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsLicence->expired_date)->format('d-m-Y')}}</td>
                @else
                    <td style="font-size: 12px;width: 3%;"></td>
                @endif
                <td style="width: 6%;font-size: 12px;">{!! $uploadDocumentsLicence->upload_comment !!}</td>

                @if($uploadDocumentsBroker && $uploadDocumentsBroker->is_uploaded == 1)
                    <td style="font-size: 12px;background-color: #77ad5f;width: 3%;">YES</td>
                @else
                    <td style="font-size: 12px;width: 3%;"></td>
                @endif
                @if($uploadDocumentsBroker)
                    <td style="font-size: 12px;background-color: #fce4d5;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsBroker->expired_date)->format('d-m-Y')}}</td>
                @else
                    <td style="font-size: 12px;width: 3%;"></td>
                @endif
                @if($uploadDocumentsAnnual)
                    <td style="font-size: 12px;background-color: #fce4d5;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsAnnual->expired_date)->format('d-m-Y')}}</td>
                @else
                    <td style="font-size: 12px;width: 3%;"></td>
                @endif
            </tr>
        @endforeach



        </tbody>
    </table>
</x-filament-widgets::widget>
