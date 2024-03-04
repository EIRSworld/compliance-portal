<table>
    <thead>
    <tr style="background-color: lightgreen;">
        <th style="font-weight: bold">Calendar Year</th>
        <th style="font-weight: bold">Country Name</th>
        <th style="font-weight: bold">Name</th>


        <th style="font-weight: bold">Description</th>
        <th style="font-weight: bold">Status</th>
        <th style="font-weight: bold">Status Type</th>
    </tr>
    </thead>
    <tbody>
    @foreach($events as $event)
        <tr>
            <td> {{  $event->calendarYear->name }}</td>
            <td> {{  $event->country->name }}</td>
            <td>{{ $event->name }}</td>

            <td>{!! $event->description !!} </td>
            <td>{{ $event->status }}</td>



            @if($event->status == 'Red')
                <td>Very Critical</td>
            @elseif($event->status == 'Amber')
                <td>Event needs attention</td>
            @elseif($event->status == 'Green')
                <td>Solved - It is a risk, but it keeps happening</td>
            @elseif($event->status == 'Blue')
                <td>Event happened but its not a risk anymore</td>
            @endif

        </tr>
    @endforeach
    </tbody>
</table>
