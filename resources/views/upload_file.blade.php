
<div class="p-2" style="margin-top: 1%">
    <?php
    $compliantMenuDetailId = $getRecord()->id;

    $compliantMenuDetail = \App\Models\ComplianceSubMenu::find($compliantMenuDetailId);
    $documents = $compliantMenuDetail->getMedia('compliant_attachments');
    ?>
    @forelse($documents as $document)
        <div style="margin-top: 7%;">
            <a href="{{ $document->getUrl() ?? '-' }}" target="_blank" style="color: blue;">
{{--                ORDER ACKNOWLEDGEMENT--}}
                {{ $document->name ?? 'Unknown Document' }}
            </a>
        </div>
    @empty
        <p>No documents found.</p>
    @endforelse
</div>
