<table>
    <thead>
    <tr>
        <th style="font-weight: bold;background-color: lightgreen;">Country</th>
        <th style="font-weight: bold;background-color: lightgreen;">Subject</th>
        <th style="font-weight: bold;background-color: lightgreen;">Assign a Owner</th>
        <th style="font-weight: bold;background-color: lightgreen;">Upload By</th>
    </tr>
    </thead>
    <tbody>
    @foreach($compliances as $data)
        <tr>
            <td> {{  $data->country->name }}</td>
            <td> {{  $data->subject }}</td>
            <td>{{ $data->user->name }}</td>
            <td>{{ $data->uploadBy->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
