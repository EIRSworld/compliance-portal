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
    @foreach($events_regulars_finances as $events_regulars_finance)
        <tr>
            <td> {{  $events_regulars_finance->country->name }}</td>
            <td> {{  $events_regulars_finance->entity->entity_name }}</td>
            <td>{{ $events_regulars_finance->event_name }}</td>
            <td>{{ \Carbon\Carbon::parse($events_regulars_finance->due_date)->format('Y-m-d') }}</td>

            <td>{{ $events_regulars_finance->status }}</td>



            @if($events_regulars_finance->status == 'Red')
                <td>Very Critical</td>
            @elseif($events_regulars_finance->status == 'Amber')
                <td>Event needs attention</td>
            @elseif($events_regulars_finance->status == 'Green')
                <td>Solved - It is a risk, but it keeps happening</td>
            @elseif($events_regulars_finance->status == 'Blue')
                <td>Event happened but its not a risk anymore</td>
            @endif

        </tr>
    @endforeach
    </tbody>
</table>
