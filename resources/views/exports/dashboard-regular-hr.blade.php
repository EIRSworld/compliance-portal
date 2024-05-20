<table>
    <thead>
    <tr style="background-color: lightgreen;">
        <th style="font-weight: bold">Country</th>
        <th style="font-weight: bold">Entity</th>
        <th style="font-weight: bold">Event Name</th>


        <th style="font-weight: bold">Due Date</th>
        <th style="font-weight: bold">Status</th>
        <th style="font-weight: bold">Status Text</th>
    </tr>
    </thead>
    <tbody>
    @foreach($events_regulars_hrs as $events_regulars_hr)
        <tr>
            <td> {{  $events_regulars_hr->country->name }}</td>
            <td> {{  $events_regulars_hr->entity->entity_name }}</td>
            <td>{{ $events_regulars_hr->event_name }}</td>
            <td>{{ \Carbon\Carbon::parse($events_regulars_hr->due_date)->format('Y-m-d') }}</td>

            <td>{{ $events_regulars_hr->status }}</td>



            @if($events_regulars_hr->status == 'Red')
                <td>Very Critical</td>
            @elseif($events_regulars_hr->status == 'Amber')
                <td>Event needs attention</td>
            @elseif($events_regulars_hr->status == 'Green')
                <td>Solved - It is a risk, but it keeps happening</td>
            @elseif($events_regulars_hr->status == 'Blue')
                <td>Event happened but its not a risk anymore</td>
            @endif

        </tr>
    @endforeach
    </tbody>
</table>
