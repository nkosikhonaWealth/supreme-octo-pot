<x-filament-panels::page>
    @if($participant_details)
        @if($participant_details->TVET)
            @if($participant_details->TVET->participant_result->status=='Awarded')
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h2 class="text-2xl text-blue-600 font-bold mb-4 text-center">Welcome, {{ auth()->user()->name }}!</h2>
                <p class="text-blue-600">We appreciate your continued commitment to the programme through proper use of your tools and timely feedback through consistent monthly reporting.
                </p>
            </div>
            <x-filament-widgets::widgets
                :widgets="$this->getWidgets()"
                :columns="12"
            />
            @else
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <h2 class="text-2xl text-blue-600 font-bold mb-4 text-center">Welcome, {{ auth()->user()->name }}!</h2>
                    <p class="text-blue-600">Be sure to keep checking your registered email -
                    <span class="font-bold text-blue-600">{{ auth()->user()->email }}</span>, for any important updates regarding the ENYC TVET Support Programme.
                    </p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <p class="text-blue-600">
                        If you are interested in applying for the Training of Trainers, kindly follow the instructions below:
                    </p>
                    <p class="text-blue-600">
                        Using the menu on the left hand side of the screen, make your way to the "My Activities" menu. Once you can see the available options, please click on "Training of Trainers Application," so you can proceed with the application.
                    </p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-md justify-center">
                    <h2 class="text-2xl font-bold mb-4 text-center text-blue-600">Your Personal Details</h2>
                    <div class="flex items-center justify-center">
                        <img class="full-rounded col-9" 
                        src="{{ asset('storage/'.$participant_details->id_upload) }}" />
                        <alt="ENYC YDP Participant ID Image"/>
                    </div>
                    <div class="grid grid-cols-2 gap-x-4">
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Identity Number: </span> {{ $participant_details->identity_number}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Gender: </span> {{ $participant_details->gender}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Date Of Birth: </span> {{ \Carbon\Carbon::parse($participant_details->d_o_b)->format('F j, Y') }}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Phone Number: </span> {{ $participant_details->phone}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Marital Status: </span> {{ $participant_details->marital_status}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Living Situation: </span> {{ $participant_details->living_situation}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Residential Address: </span> {{ $participant_details->residential_address}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Inkhundla: </span> {{ $participant_details->inkhundla}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Region: </span> {{ $participant_details->region}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Disability: </span> {{ $participant_details->disability}}
                        </p>
                        @if($participant_details->disability_name)
                            <p class="text-blue-600 mt-4">
                                <span class="font-bold">Disability Name: </span> {{ $participant_details->disability_name }}
                            </p>
                        @endif
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Family Situation: </span> {{ $participant_details->family_situation}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Family Role: </span> {{ $participant_details->family_role}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Beneficiaries: </span> {{ $participant_details->beneficiaries}}
                        </p>
                    </div>
                </div>
            @endif
        @else
            <div class="p-6 bg-white rounded-lg shadow-md justify-center">
                    <h2 class="text-2xl font-bold mb-4 text-center text-blue-600">Your Personal Details</h2>
                    <div class="flex items-center justify-center">
                        <img class="full-rounded col-9" 
                        src="{{ asset('storage/'.$participant_details->id_upload) }}" />
                        <alt="ENYC YDP Participant ID Image"/>
                    </div>
                    <div class="grid grid-cols-2 gap-x-4">
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Identity Number: </span> {{ $participant_details->identity_number}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Gender: </span> {{ $participant_details->gender}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Date Of Birth: </span> {{ \Carbon\Carbon::parse($participant_details->d_o_b)->format('F j, Y') }}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Phone Number: </span> {{ $participant_details->phone}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Marital Status: </span> {{ $participant_details->marital_status}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Living Situation: </span> {{ $participant_details->living_situation}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Residential Address: </span> {{ $participant_details->residential_address}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Inkhundla: </span> {{ $participant_details->inkhundla}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Region: </span> {{ $participant_details->region}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Disability: </span> {{ $participant_details->disability}}
                        </p>
                        @if($participant_details->disability_name)
                            <p class="text-blue-600 mt-4">
                                <span class="font-bold">Disability Name: </span> {{ $participant_details->disability_name }}
                            </p>
                        @endif
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Family Situation: </span> {{ $participant_details->family_situation}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Family Role: </span> {{ $participant_details->family_role}}
                        </p>
                        <p class="text-blue-600 mt-4">
                            <span class="font-bold"> Beneficiaries: </span> {{ $participant_details->beneficiaries}}
                        </p>
                    </div>
                </div>
        @endif
    @else
        <div class="p-6 bg-white rounded-lg shadow-md">
                <h2 class="text-2xl text-blue-600 font-bold mb-4 text-center">Good Day {{ auth()->user()->name }}!</h2>
                <p class="text-blue-600">
                    Welcome To The ENYC Youth Development Portal. We look foward to being your partner as you continue your development journey as a young person.
                </p>
        </div>
    @endif
</x-filament-panels::page>
