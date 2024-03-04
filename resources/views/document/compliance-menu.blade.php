<div class="p-2 " >
    <?php
    $policy = $getRecord();
    $uploadDocument = \App\Models\UploadDocument::whereComplianceMenuId($policy->id)->first();
    if($uploadDocument){
    $documents = $uploadDocument->getMedia('upload_documents');
    }
    ?>
    @if($uploadDocument)

    @foreach($documents as $document)
        <a href="{{ $document->getUrl() ?? ''}}" target="_blank" style="float: left">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        </a>
    @endforeach
    @endif
</div>
