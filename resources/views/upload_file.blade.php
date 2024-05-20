
<div class="p-2" style="margin-top: 1%">
    <?php
    $compliancePrimarySubMenuId = $getRecord()->id;

    $compliancePrimarySubMenu = \App\Models\CompliancePrimarySubMenu::find($compliancePrimarySubMenuId);
    $documents = $compliancePrimarySubMenu->getMedia('compliance_primary_attachments');
//    dd($documents);
    ?>
    @forelse($documents as $document)
        <div style="margin-top: 7%; display: flex; justify-content: space-between; align-items: center;">
            <!-- Document Link Section -->
            <div>
                <a href="{{ $document->getUrl() ?? '-' }}" target="_blank" style="color: blue;">
                    {{ $document->name ?? 'Unknown Document' }}
                </a>
            </div>

            <!-- Delete Button Section -->
            <div>
                <a href="{{ route('document.delete', $document->id) }}" style="color: red;margin-left: 1173px;" onclick="return confirm('Are you sure you want to delete this document?')">Delete</a>
            </div>
        </div>
{{--        <div style="margin-top: 7%;">--}}
{{--            <a href="{{ $document->getUrl() ?? '-' }}" target="_blank" style="color: blue;">--}}
{{--                ORDER ACKNOWLEDGEMENT--}}
{{--                {{ $document->name ?? 'Unknown Document' }}--}}
{{--            </a>--}}

{{--            <a href="{{ route('document.delete', $document->id) }}" style="color: red;margin-left: 379%;" onclick="return confirm('Are you sure you want to delete this document?')">Delete</a>--}}
{{--            <a href="{{ route('document.delete', $document->id) }}" style="color: red;margin-left: 379%;" onclick="return ">Delete</a>--}}
{{--        </div>--}}
    @empty
        <p>No documents found.</p>
    @endforelse
</div>
