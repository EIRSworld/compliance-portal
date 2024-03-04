
<table >
    <thead>

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
            $uploadDocumentsPi = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$calendar_year_id)->whereName('PI Cover')->first();
            $uploadDocumentsFidelity = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$calendar_year_id)->whereName('Fidelity')->first();
            $uploadDocumentsAudit = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$calendar_year_id)->whereName('Audited Financials')->first();
            $uploadDocumentsRegistration = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$calendar_year_id)->whereName('Registration Documents')->first();
            $uploadDocumentsLicence = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$calendar_year_id)->whereName('Proof of Payment')->first();
            $uploadDocumentsBroker = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$calendar_year_id)->whereName('Broker Qtr Submissions')->first();
            $uploadDocumentsAnnual = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$calendar_year_id)->whereName('Annual Return')->first();
            ?>
        <tr>
            <td>{{$country->name}}</td>
            @if($uploadDocumentsPi && $uploadDocumentsPi->is_uploaded == 1)
                <td style="font-size: 12px;background-color: #77ad5f;">YES</td>
            @else
                <td style=" font-size: 12px;"></td>
            @endif
            @if($uploadDocumentsPi)
                <td style="font-size: 12px;background-color: #fce4d5;">{{\Carbon\Carbon::parse($uploadDocumentsPi->expired_date)->format('d-m-Y')}}</td>
            @else
                <td style="font-size: 12px;"></td>
            @endif
            @if($uploadDocumentsFidelity && $uploadDocumentsFidelity->is_uploaded == 1)
                <td style="font-size: 12px;background-color: #77ad5f;">YES</td>
            @else
                <td style="font-size: 12px;"></td>
            @endif
            @if($uploadDocumentsFidelity)
                <td style="font-size: 12px;background-color: #fce4d5;">{{\Carbon\Carbon::parse($uploadDocumentsFidelity->expired_date)->format('d-m-Y')}}</td>
            @else
                <td style="font-size: 12px;"></td>
            @endif
            @if($uploadDocumentsAudit && $uploadDocumentsAudit->is_uploaded == 1)
                <td style="font-size: 12px;background-color: #77ad5f;">YES</td>
            @else
                <td style="font-size: 12px;"></td>
            @endif
            @if($uploadDocumentsAudit)
                <td style="font-size: 12px;background-color: #fce4d5;">{{\Carbon\Carbon::parse($uploadDocumentsAudit->expired_date)->format('d-m-Y')}}</td>
            @else
                <td style="font-size: 12px;"></td>
            @endif

            @if($uploadDocumentsRegistration && $uploadDocumentsRegistration->is_uploaded == 1)
                <td style="font-size: 12px;background-color: #77ad5f;">YES</td>
            @else
                <td style="font-size: 12px;"></td>
            @endif
            @if($uploadDocumentsRegistration)
                <td style="font-size: 12px;background-color: #fce4d5;">{{\Carbon\Carbon::parse($uploadDocumentsRegistration->expired_date)->format('d-m-Y')}}</td>
            @else
                <td style="font-size: 12px;"></td>
            @endif
            <td style="font-size: 12px;">{!! $uploadDocumentsRegistration->upload_comment !!}</td>

            @if($uploadDocumentsLicence && $uploadDocumentsLicence->is_uploaded == 1)
                <td style="font-size: 12px;background-color: #77ad5f;">YES</td>
            @else
                <td style="font-size: 12px;"></td>
            @endif
            @if($uploadDocumentsLicence)
                <td style="font-size: 12px;background-color: #fce4d5;">{{\Carbon\Carbon::parse($uploadDocumentsLicence->expired_date)->format('d-m-Y')}}</td>
            @else
                <td style="font-size: 12px;"></td>
            @endif
            <td style="font-size: 12px;">{!! $uploadDocumentsLicence->upload_comment !!}</td>

            @if($uploadDocumentsBroker && $uploadDocumentsBroker->is_uploaded == 1)
                <td style="font-size: 12px;background-color: #77ad5f;">YES</td>
            @else
                <td style="font-size: 12px;"></td>
            @endif
            @if($uploadDocumentsBroker)
                <td style="font-size: 12px;background-color: #fce4d5;">{{\Carbon\Carbon::parse($uploadDocumentsBroker->expired_date)->format('d-m-Y')}}</td>
            @else
                <td style="font-size: 12px;"></td>
            @endif
            @if($uploadDocumentsAnnual)
                <td style="font-size: 12px;background-color: #fce4d5;">{{\Carbon\Carbon::parse($uploadDocumentsAnnual->expired_date)->format('d-m-Y')}}</td>
            @else
                <td style="font-size: 12px;"></td>
            @endif
        </tr>
    @endforeach



    </tbody>
</table>
