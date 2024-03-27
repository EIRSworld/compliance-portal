<x-filament-widgets::widget>

{{--    <h1 style="font-weight: bold;">Dashboard Summary</h1>--}}
    <style>
        .filament-tables-header-cell button {
            justify-content: right;
        }

        .filament-tables-row td {
            text-align: right;
        }

        .custom-heading th {
            /*text-align: center;*/
        }

        th {
            text-align: right;
        }

        td, th {
            padding: 5px !important;
            border: 1px solid #ccc !important;
        }

        .border-new {
            border: 1px solid #fff !important;
        }

        .custom-heading th {
            /*text-align: left;*/
        }

        .filament-tables-row td {
            text-align: left;
        }

        .amount {
            text-align: right;
        }

        .w-28 {
            width: 7rem;
        }
    </style>
    {{ $this->form }}
    <div class="filament-tables-component" style="margin: 0; margin-top: 10px;">
        <div class="border border-gray-300 shadow-sm bg-white rounded-xl filament-tables-container">

            <div class="overflow-y-auto relative rounded-t-xl">
                <table class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                       style="zoom: 90%">
                    <thead>
                    <tr style="background-color: lightskyblue">
                        <th class="text-center font-bold" colspan="16"><b>Dashboard Summary</b>
                            <a href="{{ route('report.dashboard-summary', [$this->calendar_year_id]) }}"
                               style="padding: 5px;" target="_blank">
                                <x-filament::button>
                                    Download
                                </x-filament::button>
                            </a>
                        </th>
                    </tr>

                    <tr class="custom-heading bg-gray-100 text-center" style="background-color: #ffffff;">
                        <th class="text-center">ENTITY</th>
                        <th class="text-center" colspan="4">PI AND FIDELITY</th>
                        <th class="text-center" colspan="2">AUDITED FINANCIALS</th>
                        <th class="text-center" colspan="3">COMPANY REGISTRATION</th>
                        <th class="text-center" colspan="3">LICENCE</th>
                        <th class="text-center" colspan="2">BROKER QTR SUBMISSIONS</th>
                        <th class="text-center">ANNUAL RETURN</th>
                    </tr>

                    </thead>
                    <tbody class="divide-y whitespace-nowrap">

                    <tr style="font-size: 12px;font-weight: bold">
                        <td class='summary' style="padding: 12px;"></td>
                        <td class='summary'>PI</td>
                        <td class='summary'>DUE DATE</td>
                        <td class='summary'>FIDELITY</td>
                        <td class='summary'>DUE DATE</td>
                        <td class='summary'>UPLOAD</td>
                        <td class='summary'>DUE DATE</td>
                        <td class='summary'>UPLOAD</td>
                        <td class='summary'>DUE DATE</td>
                        <td class='summary'>Comments</td>
                        <td class='summary'>UPLOAD</td>
                        <td class='summary'>DUE DATE</td>
                        <td class='summary'>Comments</td>
                        <td class='summary'>UPLOAD</td>
                        <td class='summary'>DUE DATE</td>
                        <td class='summary'>DUE DATE</td>
                    </tr>
                    <?php
                    $document = \App\Models\Document::where('calendar_year_id', $this->calendar_year_id)->get();
                    $user = \Illuminate\Support\Facades\Auth::user();

                    if ($user->hasAnyRole(['Country Head', 'Cluster Head', 'Compliance Finance Manager', 'Compliance Principle Manager', 'Compliance Finance Officer', 'Compliance Principle Officer'])) {
                        $countryId = $user->country_id;
                        if ($countryId !== null) {
                            $documents = \App\Models\Document::whereIn('country_id', $countryId)->where('calendar_year_id', $this->calendar_year_id)->get();
                        } else {
                            $documents = [];
                        }
                    } else {
                        $documents = $document;
                    }
                    ?>
                    @foreach($documents as $country)
                            <?php
                            $uploadDocumentsPi = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id', $this->calendar_year_id)->whereName('PI Cover')->first();
                            $uploadDocumentsFidelity = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id', $this->calendar_year_id)->whereName('Fidelity')->first();
                            $uploadDocumentsAudit = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id', $this->calendar_year_id)->whereName('Audited Financials')->first();
                            $uploadDocumentsRegistration = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id', $this->calendar_year_id)->whereName('Registration Documents')->first();
                            $uploadDocumentsLicenceProof = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id', $this->calendar_year_id)->whereName('Proof of Payment')->first();
                            $uploadDocumentsLicenceCertificate = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id', $this->calendar_year_id)->whereName('Certificate')->first();
                            $uploadDocumentsBroker = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id', $this->calendar_year_id)->whereName('1st Qtr2')->first();
                            $uploadDocumentsAnnual = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id', $this->calendar_year_id)->whereName('Annual Return')->first();

                            $currentDate = \Carbon\Carbon::now();
                            if ($uploadDocumentsPi) {
                                $pidateCheck = $uploadDocumentsPi->expired_date > \Carbon\Carbon::now();
                            }
                            if ($uploadDocumentsFidelity) {

                                $fidelitydateCheck = $uploadDocumentsFidelity->expired_date > \Carbon\Carbon::now();
                            }

                            if ($uploadDocumentsAudit) {

                                $auditdateCheck = $uploadDocumentsAudit->expired_date > \Carbon\Carbon::now();
                            }
                            if ($uploadDocumentsRegistration) {

                                $registrationdateCheck = $uploadDocumentsRegistration->expired_date > \Carbon\Carbon::now();
                            }
//                $brokerdateCheck = $uploadDocumentsBroker->expired_date  > \Carbon\Carbon::now();
//                dd($uploadDocumentsRegistration,$registrationdateCheck);
                            if ($uploadDocumentsLicenceProof && $uploadDocumentsLicenceCertificate) {
                                $proofdateCheck = \Carbon\Carbon::parse($uploadDocumentsLicenceProof->expired_date);
                                $certificatedateCheck = \Carbon\Carbon::parse($uploadDocumentsLicenceCertificate->expired_date);

                                $leastDate = $proofdateCheck->min($certificatedateCheck);

                                $leastdateCheck = $leastDate > \Carbon\Carbon::now();

                            }
                            ?>

                        <tr>
                            <td class='summary' style="width: 9%">{{$country->name}}</td>
                            @if($uploadDocumentsPi && $uploadDocumentsPi->is_uploaded == 1)
                                <td class='summary' style="font-size: 12px;background-color: #c2ffad;width: 3%;">YES
                                </td>
                                {{--                @elseif($pidateCheck)--}}
                                {{--                    <td class='summary' style="font-size: 12px;width: 3%;background-color: #ffb5b5;">No</td>--}}
                            @else
                                <td class='summary' style="font-size: 12px;width: 3%;background-color: #e7e4e4;">N/A
                                </td>
                            @endif
                            @if($uploadDocumentsPi)
                                <td class='summary'
                                    style="font-size: 12px;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsPi->expired_date)->format('d-m-Y')}}</td>
                            @else
                                <td class='summary' style="font-size: 12px;width: 3%;"></td>
                            @endif

                            @if($uploadDocumentsFidelity && $uploadDocumentsFidelity->is_uploaded == 1)
                                <td class='summary' style="font-size: 12px;background-color: #c2ffad;width: 3%;">YES
                                </td>
                                {{--                @elseif($fidelitydateCheck)--}}
                                {{--                    <td class='summary' style="font-size: 12px;width: 3%;background-color: #ffb5b5;">No</td>--}}
                            @else
                                <td class='summary' style="font-size: 12px;width: 3%;background-color: #e7e4e4;">N/A
                                </td>
                            @endif
                            @if($uploadDocumentsFidelity)
                                <td class='summary'
                                    style="font-size: 12px;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsFidelity->expired_date)->format('d-m-Y')}}</td>
                            @else
                                <td class='summary' style="font-size: 12px;width: 3%;"></td>
                            @endif

                            @if($uploadDocumentsAudit && $uploadDocumentsAudit->is_uploaded == 1)
                                <td class='summary' style="font-size: 12px;background-color: #c2ffad;width: 3%;">YES
                                </td>
                                {{--                @elseif($auditdateCheck)--}}
                                {{--                    <td class='summary' style="font-size: 12px;width: 3%;background-color: #ffb5b5;">No</td>--}}
                            @else
                                <td class='summary' style="font-size: 12px;width: 3%;background-color: #e7e4e4;">N/A
                                </td>
                            @endif
                            @if($uploadDocumentsAudit)
                                <td class='summary'
                                    style="font-size: 12px;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsAudit->expired_date)->format('d-m-Y')}}</td>
                            @else
                                <td class='summary' style="font-size: 12px;width: 3%;"></td>
                            @endif


                            @if($uploadDocumentsRegistration && $uploadDocumentsRegistration->is_uploaded == 1)
                                <td class='summary' style="font-size: 12px;background-color: #c2ffad;width: 3%;">YES
                                </td>
                                {{--                @elseif($registrationdateCheck)--}}
                                {{--                    <td class='summary' style="font-size: 12px;width: 3%;background-color: #ffb5b5;">No</td>--}}
                            @else
                                <td class='summary' style="font-size: 12px;width: 3%;background-color: #e7e4e4;">N/A
                                </td>
                            @endif
                            @if($uploadDocumentsRegistration)
                                <td class='summary'
                                    style="font-size: 12px;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsRegistration->expired_date)->format('d-m-Y')}}</td>

                                <td class='summary'
                                    style="width: 6%;font-size: 12px;">{!! $uploadDocumentsRegistration->upload_comment ?? '' !!}</td>
                            @else
                                <td class='summary' style="font-size: 12px;width: 3%;"></td>
                                <td class='summary' style="font-size: 12px;width: 3%;"></td>
                            @endif


                            @if($uploadDocumentsLicenceProof && $uploadDocumentsLicenceProof->is_uploaded == 1 && $uploadDocumentsLicenceCertificate && $uploadDocumentsLicenceCertificate->is_uploaded == 1)
                                <td class='summary' style="font-size: 12px;background-color: #c2ffad;width: 3%;">YES
                                </td>
                                {{--                    @elseif($leastdateCheck)--}}
                                {{--                        <td class='summary' style="font-size: 12px;width: 3%;background-color: #ffb5b5;">No</td>--}}
                            @else
                                <td class='summary' style="font-size: 12px;width: 3%;background-color: #e7e4e4;">N/A
                                </td>
                            @endif
                            @if($uploadDocumentsLicenceProof && $uploadDocumentsLicenceCertificate)
                                <td class='summary'
                                    style="font-size: 12px;width: 6%;">{{\Carbon\Carbon::parse($leastDate)->format('d-m-Y')}}</td>

                                <td class='summary'
                                    style="width: 6%;font-size: 12px;">{!! $uploadDocumentsLicenceProof->upload_comment ?? '' !!}</td>
                            @else
                                <td class='summary' style="font-size: 12px;width: 3%;"></td>
                                <td class='summary' style="font-size: 12px;width: 3%;"></td>
                            @endif

                            @if($uploadDocumentsBroker && $uploadDocumentsBroker->is_uploaded == 1)
                                <td class='summary' style="font-size: 12px;background-color: #c2ffad;width: 3%;">YES
                                </td>
                                {{--                @elseif($brokerdateCheck)--}}
                                {{--                    <td class='summary' style="font-size: 12px;width: 3%;background-color: #ffb5b5;">No</td>--}}
                            @else
                                <td class='summary' style="font-size: 12px;width: 3%;background-color: #e7e4e4;">N/A
                                </td>
                            @endif
                            @if($uploadDocumentsBroker)
                                <td class='summary'
                                    style="font-size: 12px;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsBroker->expired_date)->format('d-m-Y')}}</td>
                            @else
                                <td class='summary' style="font-size: 12px;width: 3%;"></td>
                            @endif

                            @if($uploadDocumentsAnnual)
                                <td class='summary'
                                    style="font-size: 12px;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsAnnual->expired_date)->format('d-m-Y')}}</td>
                            @else
                                <td class='summary' style="font-size: 12px;width: 3%;"></td>
                            @endif
                        </tr>
                    @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="filament-tables-component" style="margin: 0; margin-top: 10px;">
        <div class="border border-gray-300 shadow-sm bg-white rounded-xl filament-tables-container">

            <div class="overflow-y-auto relative rounded-t-xl">
                <table class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                       style="zoom: 90%">
                    <thead>
                    <tr style="background-color: lightskyblue">
                        <th class="text-center font-bold" colspan="16"><b>Dashboard Event Summary</b>
                            <a href="{{ route('report.dashboard-event-summary', [$this->calendar_year_id]) }}"
                               style="padding: 5px;" target="_blank">
                                <x-filament::button>
                                    Download
                                </x-filament::button>
                            </a>
                        </th>
                    </tr>

                    <tr class="custom-heading bg-gray-100 text-center" style="background-color: #ffffff;">
                        <th class="text-center">Country</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Status</th>
                    </tr>

                    </thead>
                    <tbody class="divide-y whitespace-nowrap">

                    @foreach($compliance_events->where('calendar_year_id', $this->calendar_year_id) as $compliance_event)
                        @if($compliance_event->status == 'Red')
                            <tr style="background-color: #f39393;color:black;">
                                <td class="text-center">{{$compliance_event->country->name ?? ''}}</td>
                                <td class="text-center">{{$compliance_event->name ?? ''}}</td>
                                <td class="text-center">{{$compliance_event->description ?? ''}}</td>
                                <td class="text-center">{{$compliance_event->status ?? ''}}</td>
                            </tr>
                        @elseif($compliance_event->status == 'Amber')
                            <tr style="background-color: #efefaa;color:black;">
                                <td class="text-center">{{$compliance_event->country->name ?? ''}}</td>
                                <td class="text-center">{{$compliance_event->name ?? ''}}</td>
                                <td class="text-center">{{$compliance_event->description ?? ''}}</td>
                                <td class="text-center">{{$compliance_event->status ?? ''}}</td>
                            </tr>
                        @elseif($compliance_event->status == 'Green')
                            <tr style="background-color: #bdfcbd;color:black;">
                                <td class="text-center">{{$compliance_event->country->name ?? ''}}</td>
                                <td class="text-center">{{$compliance_event->name ?? ''}}</td>
                                <td class="text-center">{{$compliance_event->description ?? ''}}</td>
                                <td class="text-center">{{$compliance_event->status ?? ''}}</td>
                            </tr>
                        @elseif($compliance_event->status == 'Blue')
                            <tr style="background-color: #1f9ab2;color:black;">
                                <td class="text-center">{{$compliance_event->country->name ?? ''}}</td>
                                <td class="text-center">{{$compliance_event->name ?? ''}}</td>
                                <td class="text-center">{{$compliance_event->description ?? ''}}</td>
                                <td class="text-center">{{$compliance_event->status ?? ''}}</td>
                            </tr>

                        @endif

                    @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
