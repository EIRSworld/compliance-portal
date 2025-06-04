<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Report</title>
{{--    <style>--}}
{{--        table {--}}
{{--            width: 100%;--}}
{{--            border-collapse: collapse;--}}
{{--        }--}}

{{--        th, td {--}}
{{--            border: 1px solid black;--}}
{{--            padding: 10px;--}}
{{--            text-align: center;--}}
{{--        }--}}

{{--        th {--}}
{{--            background-color: #DDD9C4;--}}
{{--        }--}}
{{--    </style>--}}

    <style>
        table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 11px;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 4px; /* reduced padding */
            text-align: center;
            word-wrap: break-word;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .red { background-color: #F2DCDB; }
        .amber { background-color: #FFFFCC; }
        .green { background-color: #EBF1DE; }
        .blue { background-color: #DCE6F1; }

        th[rowspan], td[rowspan] {
            background-color: #4A90E2;
            color: white;
            font-size: 12px;
            text-transform: uppercase;
            text-align: left;
            padding-left: 4px;
        }

        /* Optional: Reduce margin/padding around the table container */
        .table-container {
            margin: 0;
            padding: 0;
        }
    </style>

</head>
<body>
{{--<h1>Dashboard Report for {{ $countryName }}</h1>--}}
{{--<p>Country ID: {{ $countryName }}</p>--}}
@php
    $country = \App\Models\Country::find($countryID);
    $entities = \App\Models\Entity::where('country_id', $country->id)->get();

    $complianceTypes = ['Operations', 'Finance', 'HR'];
    $allSummaries = [];

    foreach ($complianceTypes as $type) {
        $redSummary = [];

        foreach ($entities as $entity) {
            $menu = \App\Models\ComplianceSubMenu::where([
                ['calendar_year_id', $calendar_year_id],
                ['country_id', $country->id],
                ['entity_id', $entity->id],
                ['sub_menu_name', $type],
            ])->first();

            if (!$menu) continue;

            $redCount = \App\Models\CompliancePrimarySubMenu::where([
                ['calendar_year_id', $calendar_year_id],
                ['country_id', $country->id],
                ['entity_id', $entity->id],
                ['compliance_sub_menu_id', $menu->id],
                ['event_type', 'Regular'],
                ['status', 'Red'],
            ])->count();

            if ($redCount > 0) {
             $redSummary[] = "<span style='color: red; font-weight: bold;'>{$redCount} critical compliance events</span> for <span style='color: green;'> {$entity->entity_name} </span>";
            }

        }

        if (count($redSummary)) {
            $allSummaries[$type] = $redSummary;
        }
    }
@endphp

<p>Dear All,</p>

@foreach($allSummaries as $type => $summary)
    <p>
        Compliance Type: <strong>{{ $type }}</strong><br>
        Kindly note there are {!! implode(' & ', $summary) !!} not being attended requiring your urgent attention.
    </p>
@endforeach
<?php
$country = \App\Models\Country::find($countryID);
$entities = \App\Models\Entity::where('country_id', $country->id)->get();
?>

<h5>Regular Dashboard Summary</h5>

@foreach($entities as $entity)
        <?php
        $regular_yearly = \App\Models\ComplianceMenu::whereCalendarYearId($calendar_year_id)->whereCountryId($country->id)->whereEntityId($entity->id)->get();
//dd($regular_yearly);
        ?>
        <div class="table-container">
    <table>
        <thead>
        <tr>
            <th colspan="7">{{ $entity->entity_name ?? ''}}</th>
        </tr>
        <tr>
            <th colspan="1">Compliance Type</th>

            <th colspan="1">Frequency</th>

            <th colspan="1">No of Events</th>
            <th colspan="1" style="background-color: #fd928f">Very Critical</th>
            <th colspan="1" style="background-color: #fcfc85">Event needs attention</th>
            <th colspan="1" style="background-color: #abff81">Uploaded</th>
            <th colspan="1" style="background-color: #9ec8ff">Approved</th>
        </tr>

        </thead>


        <tbody>
            <?php
            $operation_regular_yearly = \App\Models\ComplianceSubMenu::whereCalendarYearId($calendar_year_id)->whereCountryId($country->id)->whereEntityId($entity->id)->where('sub_menu_name', 'Operations')->first();
            $finance_regular_yearly = \App\Models\ComplianceSubMenu::whereCalendarYearId($calendar_year_id)->whereCountryId($country->id)->whereEntityId($entity->id)->where('sub_menu_name', 'Finance')->first();
            $hr_regular_yearly = \App\Models\ComplianceSubMenu::whereCalendarYearId($calendar_year_id)->whereCountryId($country->id)->whereEntityId($entity->id)->where('sub_menu_name', 'HR')->first();

            $event_name_yearly_regular_operation_annual = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Regular')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_operation_annual_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_operation_annual_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_operation_annual_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_operation_annual_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_operation_quarterly = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Regular')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_operation_quarterly_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_operation_quarterly_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_operation_quarterly_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_operation_quarterly_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_operation_monthly = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Regular')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_operation_monthly_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_operation_monthly_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_operation_monthly_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_operation_monthly_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();


            $event_name_yearly_regular_finance_annual = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Regular')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_finance_annual_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_finance_annual_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_finance_annual_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_finance_annual_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_finance_quarterly = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Regular')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_quarterly_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_quarterly_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_quarterly_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_quarterly_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_monthly = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Regular')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_monthly_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_monthly_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_monthly_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_monthly_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();




            $event_name_yearly_regular_hr_annual = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Regular')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_hr_annual_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_hr_annual_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_hr_annual_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_hr_annual_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_quarterly = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Regular')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_quarterly_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_quarterly_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_quarterly_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_quarterly_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_monthly = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Regular')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_monthly_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_monthly_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_monthly_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_monthly_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Regular')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();


            ?>
        <tr>
            <th rowspan="4">Operations</th>
        </tr>
        <tr>
            <td>Annual</td>
            <td>{{ count($event_name_yearly_regular_operation_annual) }}</td>
            <td class="red">{{ count($event_name_yearly_regular_operation_annual_red) }}</td>
            <td class="amber">{{ count($event_name_yearly_regular_operation_annual_amber) }}</td>
            <td class="green">{{ count($event_name_yearly_regular_operation_annual_green) }}</td>
            <td class="blue">{{ count($event_name_yearly_regular_operation_annual_blue) }}</td>
        </tr>
        <tr>
            <td>Quarterly</td>
            <td>{{ count($event_name_yearly_regular_operation_quarterly) }}</td>
            <td class="red">{{ count($event_name_yearly_regular_operation_quarterly_red) }}</td>
            <td class="amber">{{ count($event_name_yearly_regular_operation_quarterly_amber) }}</td>
            <td class="green">{{ count($event_name_yearly_regular_operation_quarterly_green) }}</td>
            <td class="blue">{{ count($event_name_yearly_regular_operation_quarterly_blue) }}</td>
        </tr>
        <tr>
            <td>Monthly</td>
            <td>{{ count($event_name_yearly_regular_operation_monthly) }}</td>
            <td class="red">{{ count($event_name_yearly_regular_operation_monthly_red) }}</td>
            <td class="amber">{{ count($event_name_yearly_regular_operation_monthly_amber) }}</td>
            <td class="green">{{ count($event_name_yearly_regular_operation_monthly_green) }}</td>
            <td class="blue">{{ count($event_name_yearly_regular_operation_monthly_blue) }}</td>
        </tr>

        <tr>
            <th rowspan="4">Finance</th>
        </tr>
        <tr>
            <td>Annual</td>
            <td>{{ count($event_name_yearly_regular_finance_annual) }}</td>
            <td class="red">{{ count($event_name_yearly_regular_finance_annual_red) }}</td>
            <td class="amber">{{ count($event_name_yearly_regular_finance_annual_amber) }}</td>
            <td class="green">{{ count($event_name_yearly_regular_finance_annual_green) }}</td>
            <td class="blue">{{ count($event_name_yearly_regular_finance_annual_blue) }}</td>
        </tr>
        <tr>
            <td>Quarterly</td>
            <td>{{ count($event_name_yearly_regular_finance_quarterly) }}</td>
            <td class="red">{{ count($event_name_yearly_regular_finance_quarterly_red) }}</td>
            <td class="amber">{{ count($event_name_yearly_regular_finance_quarterly_amber) }}</td>
            <td class="green">{{ count($event_name_yearly_regular_finance_quarterly_green) }}</td>
            <td class="blue">{{ count($event_name_yearly_regular_finance_quarterly_blue) }}</td>
        </tr>
        <tr>
            <td>Monthly</td>
            <td>{{ count($event_name_yearly_regular_finance_monthly) }}</td>
            <td class="red">{{ count($event_name_yearly_regular_finance_monthly_red) }}</td>
            <td class="amber">{{ count($event_name_yearly_regular_finance_monthly_amber) }}</td>
            <td class="green">{{ count($event_name_yearly_regular_finance_monthly_green) }}</td>
            <td class="blue">{{ count($event_name_yearly_regular_finance_monthly_blue) }}</td>
        </tr>

        <tr>
            <th rowspan="4">HR</th>
        </tr>
        <tr>
            <td>Annual</td>
            <td>{{ count($event_name_yearly_regular_hr_annual) }}</td>
            <td class="red">{{ count($event_name_yearly_regular_hr_annual_red) }}</td>
            <td class="amber">{{ count($event_name_yearly_regular_hr_annual_amber) }}</td>
            <td class="green">{{ count($event_name_yearly_regular_hr_annual_green) }}</td>
            <td class="blue">{{ count($event_name_yearly_regular_hr_annual_blue) }}</td>
        </tr>
        <tr>
            <td>Quarterly</td>
            <td>{{ count($event_name_yearly_regular_hr_quarterly) }}</td>
            <td class="red">{{ count($event_name_yearly_regular_hr_quarterly_red) }}</td>
            <td class="amber">{{ count($event_name_yearly_regular_hr_quarterly_amber) }}</td>
            <td class="green">{{ count($event_name_yearly_regular_hr_quarterly_green) }}</td>
            <td class="blue">{{ count($event_name_yearly_regular_hr_quarterly_blue) }}</td>
        </tr>
        <tr>
            <td>Monthly</td>
            <td>{{ count($event_name_yearly_regular_hr_monthly) }}</td>
            <td class="red">{{ count($event_name_yearly_regular_hr_monthly_red) }}</td>
            <td class="amber">{{ count($event_name_yearly_regular_hr_monthly_amber) }}</td>
            <td class="green">{{ count($event_name_yearly_regular_hr_monthly_green) }}</td>
            <td class="blue">{{ count($event_name_yearly_regular_hr_monthly_blue) }}</td>
        </tr>
        </tbody>
    </table>
        </div>
@endforeach

<br>

<h5>Add-Hoc Dashboard Summary</h5>
@foreach($entities as $entity)
        <?php
        $regular_yearly = \App\Models\ComplianceMenu::whereCalendarYearId($calendar_year_id)->whereCountryId($country->id)->whereEntityId($entity->id)->get();
//dd($regular_yearly);
        ?>
    <table>
        <thead>
        <tr>
            <th colspan="7">{{ $entity->entity_name ?? ''}}</th>
        </tr>
        <tr>
            <th colspan="1">Compliance Type</th>

            <th colspan="1">Frequency</th>

            <th colspan="1">No of Events</th>
            <th colspan="1" style="background-color: #fd928f">Very Critical</th>
            <th colspan="1" style="background-color: #fcfc85">Event needs attention</th>
            <th colspan="1" style="background-color: #abff81">Uploaded</th>
            <th colspan="1" style="background-color: #9ec8ff">Approved</th>
        </tr>

        </thead>


        <tbody>
            <?php
            $operation_regular_yearly = \App\Models\ComplianceSubMenu::whereCalendarYearId($calendar_year_id)->whereCountryId($country->id)->whereEntityId($entity->id)->where('sub_menu_name', 'Operations')->first();
            $finance_regular_yearly = \App\Models\ComplianceSubMenu::whereCalendarYearId($calendar_year_id)->whereCountryId($country->id)->whereEntityId($entity->id)->where('sub_menu_name', 'Finance')->first();
            $hr_regular_yearly = \App\Models\ComplianceSubMenu::whereCalendarYearId($calendar_year_id)->whereCountryId($country->id)->whereEntityId($entity->id)->where('sub_menu_name', 'HR')->first();

            $event_name_yearly_regular_operation_annual = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_operation_annual_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_operation_annual_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_operation_annual_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_operation_annual_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_operation_quarterly = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Add-Hoc')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_operation_quarterly_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_operation_quarterly_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_operation_quarterly_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_operation_quarterly_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_operation_monthly = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Add-Hoc')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_operation_monthly_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_operation_monthly_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_operation_monthly_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_operation_monthly_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($operation_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();


            $event_name_yearly_regular_finance_annual = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_finance_annual_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_finance_annual_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_finance_annual_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_finance_annual_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_finance_quarterly = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Add-Hoc')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_quarterly_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_quarterly_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_quarterly_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_quarterly_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_monthly = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Add-Hoc')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_monthly_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_monthly_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_monthly_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_finance_monthly_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($finance_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();




            $event_name_yearly_regular_hr_annual = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_hr_annual_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_hr_annual_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_hr_annual_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();

            $event_name_yearly_regular_hr_annual_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_quarterly = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Add-Hoc')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_quarterly_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_quarterly_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_quarterly_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_quarterly_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_monthly = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Add-Hoc')
                ->when($red, function ($query, $red) {
                    if ($red == true) {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_monthly_red = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Red')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_monthly_amber = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Amber')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_monthly_green = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Green')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();
            $event_name_yearly_regular_hr_monthly_blue = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                ->whereCountryId($country->id)
                ->whereEntityId($entity->id)
                ->whereComplianceSubMenuId($hr_regular_yearly->id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Add-Hoc')
                ->where('status', '=', 'Blue')
                ->orderBy('event_name', 'asc')
                ->orderBy('due_date', 'asc')
                ->get();


            ?>
        <tr>
            <th rowspan="4">Operations</th>
        </tr>
        <tr>
            <td>Annual</td>
            <td>{{count($event_name_yearly_regular_operation_annual)}}</td>
            <td style="background-color: #F2DCDB">{{ count($event_name_yearly_regular_operation_annual_red) }}</td>
            <td style="background-color: #FFFFCC">{{ count($event_name_yearly_regular_operation_annual_amber) }}</td>
            <td style="background-color: #EBF1DE">{{ count($event_name_yearly_regular_operation_annual_green) }}</td>
            <td style="background-color: #DCE6F1">{{ count($event_name_yearly_regular_operation_annual_blue) }}</td>
        </tr>
        <tr>
            <td>Quarterly</td>
            <td>{{ count($event_name_yearly_regular_operation_quarterly) }}</td>
            <td style="background-color: #F2DCDB">{{ count($event_name_yearly_regular_operation_quarterly_red) }}</td>
            <td style="background-color: #FFFFCC">{{ count($event_name_yearly_regular_operation_quarterly_amber) }}</td>
            <td style="background-color: #EBF1DE">{{ count($event_name_yearly_regular_operation_quarterly_green) }}</td>
            <td style="background-color: #DCE6F1">{{ count($event_name_yearly_regular_operation_quarterly_blue) }}</td>
        </tr>
        <tr>
            <td>Monthly</td>
            <td>{{ count($event_name_yearly_regular_operation_monthly) }}</td>
            <td style="background-color: #F2DCDB">{{ count($event_name_yearly_regular_operation_monthly_red) }}</td>
            <td style="background-color: #FFFFCC">{{ count($event_name_yearly_regular_operation_monthly_amber) }}</td>
            <td style="background-color: #EBF1DE">{{ count($event_name_yearly_regular_operation_monthly_green) }}</td>
            <td style="background-color: #DCE6F1">{{ count($event_name_yearly_regular_operation_monthly_blue) }}</td>
        </tr>

        <tr>
            <th rowspan="4">Finance</th>
        </tr>
        <tr>
            <td>Annual</td>
            <td>{{ count($event_name_yearly_regular_finance_annual) }}</td>
            <td style="background-color: #F2DCDB">{{ count($event_name_yearly_regular_finance_annual_red) }}</td>
            <td style="background-color: #FFFFCC">{{ count($event_name_yearly_regular_finance_annual_amber) }}</td>
            <td style="background-color: #EBF1DE">{{ count($event_name_yearly_regular_finance_annual_green) }}</td>
            <td style="background-color: #DCE6F1">{{ count($event_name_yearly_regular_finance_annual_blue) }}</td>
        </tr>
        <tr>
            <td>Quarterly</td>
            <td>{{ count($event_name_yearly_regular_finance_quarterly) }}</td>
            <td style="background-color: #F2DCDB">{{ count($event_name_yearly_regular_finance_quarterly_red) }}</td>
            <td style="background-color: #FFFFCC">{{ count($event_name_yearly_regular_finance_quarterly_amber) }}</td>
            <td style="background-color: #EBF1DE">{{ count($event_name_yearly_regular_finance_quarterly_green) }}</td>
            <td style="background-color: #DCE6F1">{{ count($event_name_yearly_regular_finance_quarterly_blue) }}</td>
        </tr>
        <tr>
            <td>Monthly</td>
            <td>{{ count($event_name_yearly_regular_finance_monthly) }}</td>
            <td style="background-color: #F2DCDB">{{ count($event_name_yearly_regular_finance_monthly_red) }}</td>
            <td style="background-color: #FFFFCC">{{ count($event_name_yearly_regular_finance_monthly_amber) }}</td>
            <td style="background-color: #EBF1DE">{{ count($event_name_yearly_regular_finance_monthly_green) }}</td>
            <td style="background-color: #DCE6F1">{{ count($event_name_yearly_regular_finance_monthly_blue) }}</td>
        </tr>

        <tr>
            <th rowspan="4">HR</th>
        </tr>
        <tr>
            <td>Annual</td>
            <td>{{ count($event_name_yearly_regular_hr_annual) }}</td>
            <td style="background-color: #F2DCDB">{{ count($event_name_yearly_regular_hr_annual_red) }}</td>
            <td style="background-color: #FFFFCC">{{ count($event_name_yearly_regular_hr_annual_amber) }}</td>
            <td style="background-color: #EBF1DE">{{ count($event_name_yearly_regular_hr_annual_green) }}</td>
            <td style="background-color: #DCE6F1">{{ count($event_name_yearly_regular_hr_annual_blue) }}</td>
        </tr>
        <tr>
            <td>Quarterly</td>
            <td>{{ count($event_name_yearly_regular_hr_quarterly) }}</td>
            <td style="background-color: #F2DCDB">{{ count($event_name_yearly_regular_hr_quarterly_red) }}</td>
            <td style="background-color: #FFFFCC">{{ count($event_name_yearly_regular_hr_quarterly_amber) }}</td>
            <td style="background-color: #EBF1DE">{{ count($event_name_yearly_regular_hr_quarterly_green) }}</td>
            <td style="background-color: #DCE6F1">{{ count($event_name_yearly_regular_hr_quarterly_blue) }}</td>
        </tr>
        <tr>
            <td>Monthly</td>
            <td>{{ count($event_name_yearly_regular_hr_monthly) }}</td>
            <td style="background-color: #F2DCDB">{{ count($event_name_yearly_regular_hr_monthly_red) }}</td>
            <td style="background-color: #FFFFCC">{{ count($event_name_yearly_regular_hr_monthly_amber) }}</td>
            <td style="background-color: #EBF1DE">{{ count($event_name_yearly_regular_hr_monthly_green) }}</td>
            <td style="background-color: #DCE6F1">{{ count($event_name_yearly_regular_hr_monthly_blue) }}</td>
        </tr>

        </tbody>
    </table>
    <br>

@endforeach


<p>
    Enclosed detailed report for your reference.
</p>
<p>
    Regards,<br>
    Compliance Team -EIRS

</p>
</body>
</html>
