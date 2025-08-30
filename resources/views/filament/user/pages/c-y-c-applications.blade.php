<x-filament::page>
    <div class="p-6 bg-white rounded-lg shadow-md">
    {{-- Commonwealth Youth Council Application Details --}}
        @if(isset($cyc_application) && $cyc_application->exists)
            <h2 class="text-2xl text-blue-600 font-bold mb-4 text-center">
                {{ Auth::user()->name }}'s Commonwealth Youth Council Application Details
            </h2>
            <div class="grid grid-cols-2 gap-x-4">
                <p class="text-blue-600 mt-4">
                    <span class="font-bold">Top 1 - 3 SDGs You Are Passionate About:</span><br>
                    {!! nl2br(e($cyc_application->sdg_response)) !!}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold">Biggest Youth Challenge in Eswatini and Advocacy Plan:</span><br>
                    {!! nl2br(e($cyc_application->challenge_response)) !!}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold">Youth Representation Experience:</span><br>
                    {{ $cyc_application->representation_experience ? 'Yes' : 'No' }}
                </p>
                @if($cyc_application->representation_experience)
                <p class="text-blue-600 mt-4">
                    <span class="font-bold">Representation Details:</span><br>
                    {!! nl2br(e($cyc_application->representation_details)) !!}
                </p>
                @endif
                <p class="text-blue-600 mt-4">
                    <span class="font-bold">Leadership / Advocacy Experience:</span><br>
                    {!! nl2br(e($cyc_application->leadership_experience)) !!}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold">Motivation to Represent Eswatini at CYC:</span><br>
                    {!! nl2br(e($cyc_application->motivation)) !!}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold">Uploaded CV:</span><br>
                    @if($cyc_application->cv_upload)
                        @foreach($cyc_application->cv_upload as $key => $file)
                            <a href="{{ asset('storage/' . $file) }}" target="_blank" class="underline text-green-900 font-semibold">
                                CV Document {{ $key + 1 }}
                            </a><br>
                        @endforeach
                    @else
                        <em>No CV uploaded.</em>
                    @endif
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold">Supporting Documents:</span><br>
                    @if($cyc_application->supporting_documents)
                        @foreach($cyc_application->supporting_documents as $key => $file)
                            <a href="{{ asset('storage/' . $file) }}" target="_blank" class="underline text-green-900 font-semibold">
                                Supporting Document {{ $key + 1 }}
                            </a><br>
                        @endforeach
                    @else
                        <em>No supporting documents uploaded.</em>
                    @endif
                </p>
            </div>
        @else
            <p class="text-blue-600 mt-4">
                No Commonwealth Youth Council Application Found. Please Click On "Edit Application" To Start Your Application.
            </p>
        @endif
    </div>
</x-filament::page>