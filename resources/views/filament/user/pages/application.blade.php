<x-filament-panels::page>
    <div class="p-6 bg-white rounded-lg shadow-md">
        @if($application_details[0])
            <h2 class="text-2xl text-blue-600 font-bold mb-4 text-center">
                {{ Auth::user()->name }}'s Application Details
            </h2>
            <div class="grid grid-cols-2 gap-x-4">
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> You Applied For: </span> {{ $participant_details[0]['pathway'] }} Pathway
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> In The Past 6 Months, Have You Been Part Of Any Youth Development Programme: </span> {{ $application_details[0]['recent_assistance'] }}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> Your Vocational Skill: </span> {{ $application_details[0]['vocational_skill'] }}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> How You Obtained It: </span> {{ $application_details[0]['vocational_skill_obtained'] }}
                </p>
                <p class="text-blue-600 mt-4">
                    @if(count($application_details[0]['certificate_upload']))
                        @foreach($application_details[0]['certificate_upload'] as $key => $upload)
                            <a href="{{ asset('storage/'.$upload) }}" class="text-blue-700 fw-bold hover:underline" target="_blank"> 
                                Vocational Certificate - {{$key+1}}
                            </a>
                            <br>
                        @endforeach
                    @endif
                </p>
                <p class="text-blue-600 mt-4">
                    @if(count($application_details[0]['finance_upload']))
                        @foreach($application_details[0]['finance_upload'] as $key => $upload)
                            <a href="{{ asset('storage/'.$upload) }}" class="text-blue-700 fw-bold hover:underline" target="_blank"> 
                                Supporting Document - {{$key+1}}
                            </a>
                            <br>
                        @endforeach
                        </a>
                    @endif
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> Your Employment Status: </span> {{ $application_details[0]['current_activity'] }}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> If Employed, How Long Have You Been Employed: </span> {{ $application_details[0]['duration'] }}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> Do You Have A Business Account: </span> {{ $application_details[0]['account'] }}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> Business Account Type and Number: </span> {{ $application_details[0]['account_number'] }}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> Are You Part Of A Youth-Led Organization? </span> {{ $application_details[0]['youth_organization_response'] }}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> What Is The Name Of The Organization? </span> {{ $application_details[0]['youth_organization_name'] }}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> I Will Use The Toolkit For: </span> {{ $application_details[0]['toolkit_use'] }}
                </p>
                <p class="text-blue-600 mt-4">
                    <span class="font-bold"> Why You Applied To This Program: </span> {{ $application_details[0]['motivation'] }}
                </p>
            </div>
        @else
            <p class="text-blue-600">
                No Application Found.
            </p>
        @endif
    </div>
</x-filament-panels::page>
