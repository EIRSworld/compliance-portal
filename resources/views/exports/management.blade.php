<table>
    <thead>
    <tr style="background-color: lightgreen;">
        <th style="font-weight: bold">Country Name</th>
        <th style="font-weight: bold">Name</th>
        <th style="font-weight: bold">Due Date</th>
        <th style="font-weight: bold">Upload Status</th>
        <th style="font-weight: bold">Approve Status</th>
        <th style="font-weight: bold">Documents</th>
        <th style="font-weight: bold">Comment</th>
        <th style="font-weight: bold">Uploaded By</th>
        <th style="font-weight: bold">Approved By</th>
    </tr>
    </thead>
    <tbody>
    @foreach($managements as $management)
        <?php
//            dd($management->id);
            $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::whereModelId($management->id)->get();
            $mediaItems = \Spatie\MediaLibrary\MediaCollections\Models\Media::whereModelType(get_class($management))
                ->whereModelId($management->id)
                ->get();
//            dd($media);
            ?>
        <tr>
            <td> {{  $management->country->name }}</td>
            <td>{{ $management->name }}</td>
            <td>{{ \Carbon\Carbon::parse($management->expired_date)->format('d-m-Y') }}</td>

            @if($management->is_uploaded === 1)
                <td>Yes</td>
            @else
                <td>No</td>
            @endif
            @if($management->approve_status === 1)
                <td>Approved</td>
            @elseif($management->approve_status === 2)
                <td>Not Approved</td>
            @else
                <td>-</td>
            @endif
            <td>
                @if($mediaItems->isNotEmpty())
                    @foreach($mediaItems as $mediaItem)
                        <a href="{{ $mediaItem->getUrl() }}" target="_blank">View File {{ $loop->iteration }}</a><br>
                    @endforeach
                @else
                    No File
                @endif
            </td>
            <td>{!! $management->reject_comment !!}</td>
            <td>{{ $management->uploadBy->name ?? ''}}</td>
            <td>{{ $management->approveBy->name ?? '' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
