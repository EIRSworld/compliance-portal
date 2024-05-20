


<div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md">
            <h4 class="text-md font-semibold text-gray-900 dark:text-white">Created By</h4>
            <p class="text-gray-500 dark:text-gray-400">{{ optional($compliancePrimarySubmenu->user)->name }}</p>
        </div>

        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md">
            <h4 class="text-md font-semibold text-gray-900 dark:text-white">Upload By</h4>
            <p class="text-gray-500 dark:text-gray-400">{{ optional($compliancePrimarySubmenu->uploadBy)->name }}</p>
        </div>

        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md">
            <h4 class="text-md font-semibold text-gray-900 dark:text-white">Approve By</h4>
            <p class="text-gray-500 dark:text-gray-400">{{ optional($compliancePrimarySubmenu->approveBy)->name }}</p>
        </div>

    </div>

    <!-- Status Tracking Line -->

    <div class="mt-6 mb-6 flex justify-center">
        <div class="flex items-center space-x-4">
            <!-- Icon for Open -->
            <div class="flex items-center">
                <div class="h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11H9v4h2V7zm0 6H9v2h2v-2z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="ml-2 text-gray-500 dark:text-gray-400">
                   Open
                </span>
            </div>

            <!-- Icon for Uploaded -->
            <div class="flex items-center">
                <div class="h-8 w-8 bg-orange-500 rounded-full flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11H9v4h2V7zm0 6H9v2h2v-2z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="ml-2 text-gray-500 dark:text-gray-400">Upload Status</span>

            </div>

            <!-- Icon for Approved -->
            <div class="flex items-center">
                <div class="h-8 w-8 bg-green-500 rounded-full flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11H9v4h2V7zm0 6H9v2h2v-2z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="ml-2 text-gray-500 dark:text-gray-400">
                            Approval Status

                    </span>
            </div>
        </div>
        </div>
        <div class="mt-6 mb-6 flex justify-center">
            <div class="flex items-center space-x-4">
                <!-- Icon for Open -->
                <div class="flex items-center">
                    <div class="h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11H9v4h2V7zm0 6H9v2h2v-2z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="ml-2 text-gray-500 dark:text-gray-400">
                        <svg style="color: green;height: 45px;width: 40px" class="h-5 w-5 text-green-500"
                             fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M16.707 5.707a1 1 0 00-1.414-1.414L9 10.586 5.707 7.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </span>
                </div>

                <!-- Icon for Uploaded -->
                <div class="flex items-center">
                    <div class="h-8 w-8 bg-orange-500 rounded-full flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11H9v4h2V7zm0 6H9v2h2v-2z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                    @if($compliancePrimarySubmenu->is_uploaded == 1)
                        <span class="ml-2 text-gray-500 dark:text-gray-400">
                             <svg style="color: green;height: 45px;width: 40px" class="h-5 w-5 text-green-500"
                                  fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M16.707 5.707a1 1 0 00-1.414-1.414L9 10.586 5.707 7.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8z"
                                  clip-rule="evenodd"/>
                        </svg>
                        </span>
                    @else
                        <span class="ml-2 text-gray-500 dark:text-gray-400">
                            <svg style="color: red;height: 45px;width: 40px" class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                 <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-11.707a1 1 0 00-1.414-1.414L10 8.586 7.707 6.293a1 1 0 00-1.414 1.414L8.586 10l-2.293 2.293a1 1 0 001.414 1.414L10 11.414l2.293 2.293a1 1 0 001.414-1.414L11.414 10l2.293-2.293z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </span>
                    @endif
                </div>

                <!-- Icon for Approved -->
                <div class="flex items-center">
                    <div class="h-8 w-8 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11H9v4h2V7zm0 6H9v2h2v-2z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                    @if($compliancePrimarySubmenu->approve_status == 0)
                        <span class="ml-2 text-gray-500 dark:text-gray-400">
                            <svg style="color: yellow;height: 45px;width: 40px" class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                 <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-3-8a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    @else
                        <span class="ml-2 text-gray-500 dark:text-gray-400">
                            @if($compliancePrimarySubmenu->approve_status == 2)
                                <svg style="color: red;height: 45px;width: 40px" class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                 <path fill-rule="evenodd"
                                       d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-11.707a1 1 0 00-1.414-1.414L10 8.586 7.707 6.293a1 1 0 00-1.414 1.414L8.586 10l-2.293 2.293a1 1 0 001.414 1.414L10 11.414l2.293 2.293a1 1 0 001.414-1.414L11.414 10l2.293-2.293z"
                                       clip-rule="evenodd"/>
                            </svg>
                            @elseif($compliancePrimarySubmenu->is_uploaded == 1 && $compliancePrimarySubmenu->approve_status == 1)
                                <svg style="color: green;height: 45px;width: 40px" class="h-5 w-5 text-green-500"
                                     fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M16.707 5.707a1 1 0 00-1.414-1.414L9 10.586 5.707 7.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8z"
                                  clip-rule="evenodd"/>
                        </svg>
                            @endif
                        </span>
                    @endif
                </div>
            </div>
        </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md">
            <h4 class="text-md font-semibold text-gray-900 dark:text-white">Created At</h4>
            <p class="text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($compliancePrimarySubmenu->created_at)->format('d-M-Y') ?? '-' }}</p>
        </div>
        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md">
            <h4 class="text-md font-semibold text-gray-900 dark:text-white">Upload Date</h4>
            <p class="text-gray-500 dark:text-gray-400">
                @if($compliancePrimarySubmenu->upload_date != null)
                    {{ \Carbon\Carbon::parse($compliancePrimarySubmenu->upload_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </p>
        </div>
        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md">
            <h4 class="text-md font-semibold text-gray-900 dark:text-white">Approved At</h4>
            <p class="text-gray-500 dark:text-gray-400">
                @if($compliancePrimarySubmenu->approve_date != null)
                    {{ \Carbon\Carbon::parse($compliancePrimarySubmenu->approve_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </p>
        </div>
    </div>
</div>
