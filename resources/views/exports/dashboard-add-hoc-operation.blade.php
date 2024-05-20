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
    @foreach($events_addhocs_operations as $events_addhocs_operation)
        <tr>
            <td> {{  $events_addhocs_operation->country->name }}</td>
            <td> {{  $events_addhocs_operation->entity->entity_name }}</td>
            <td>{{ $events_addhocs_operation->event_name }}</td>
            <td>{{ \Carbon\Carbon::parse($events_addhocs_operation->due_date)->format('Y-m-d') }}</td>

            <td>{{ $events_addhocs_operation->status }}</td>



            @if($events_addhocs_operation->status == 'Red')
                <td>Very Critical</td>
            @elseif($events_addhocs_operation->status == 'Amber')
                <td>Event needs attention</td>
            @elseif($events_addhocs_operation->status == 'Green')
                <td>Solved - It is a risk, but it keeps happening</td>
            @elseif($events_addhocs_operation->status == 'Blue')
                <td>Event happened but its not a risk anymore</td>
            @endif

        </tr>
    @endforeach
    </tbody>
</table>
