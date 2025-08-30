<x-filament-panels::page>
<div class="p-6 bg-white rounded-lg shadow-md">
    @if($application_details)
        @if($application_details[0])
            @if($application_details[0]->certificate_upload)
            <h2 class="text-2xl text-blue-600 font-bold mb-4 text-center">
                {{ Auth::user()->name }}'s Training Of Trainers Application Details
            </h2>
            <div class="grid grid-cols-2 gap-x-4">
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> You Applied For: </span> {{ $participant_details[0]['pathway'] }}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> Your Employment Status: </span> {{ $application_details[0]['current_activity'] }}
                </p>
                <p class="text-blue-600 mt-4">
                    @if(count($application_details[0]['certificate_upload']))
                        @foreach($application_details[0]['certificate_upload'] as $key => $upload)
                            <a href="{{ asset('storage/'.$upload) }}" class="text-blue-700 fw-bold hover:underline" target="_blank"> 
                                Educational Certificate - {{$key+1}}
                            </a>
                            <br>
                        @endforeach
                    @endif
                </p>
                <p class="text-blue-600 mt-4">
                    @if(count($application_details[0]['cv_upload']))
                        @foreach($application_details[0]['cv_upload'] as $key => $upload)
                            <a href="{{ asset('storage/'.$upload) }}" class="text-blue-700 fw-bold hover:underline" target="_blank"> 
                                Supporting Document - {{$key+1}}
                            </a>
                            <br>
                        @endforeach
                        </a>
                    @endif
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> Have You Worked With A Youth-Focused Organization? </span> {{ $application_details[0]['youth_organization_response'] }}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> What Is The Name Of The Organization? </span> {{ $application_details[0]['youth_organization_name'] }}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> What Were You Doing? </span> {{ $application_details[0]['youth_organization_duties'] }}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> Do You Currently Reside In Your Inkhundla? </span> {{ $application_details[0]['current_residence'] }}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> Why You Applied To This Program: </span> {{ $application_details[0]['motivation'] }}
                </p>
            </div>
            @else
                <p class="text-blue-600">
                    No Application Found. Please proceed to click on the "Edit Details" Button and complete your application.
                </p>
            @endif
        @else
            <p class="text-blue-600">
                No Application Found.
            </p>
        @endif
    @else
        <p class="text-blue-600">
            No Application Found.
        </p>
    @endif
    </div>
</x-filament-panels::page>
