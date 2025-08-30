<x-filament-panels::page>
	<div class="p-6 bg-white rounded-lg shadow-md">
        @if($participant)
        	<h2 class="text-2xl font-bold mb-4 text-center text-blue-700">Participant Details</h2>
            <div class="flex items-center justify-center">
                <img class="full-rounded col-9 mb-4" 
                src="{{ asset('storage/'.$participant->id_upload) }}" />
            </div>
            <p class="text-blue-700 mt-4">
                <span class="font-bold"> Email: </span> {{ $participant->user->email }}
            </p>
            <div class="grid grid-cols-2 gap-4 mt-4">
            	<p class="text-blue-700 mt-4">
                    <span class="font-bold"> Name: </span> {{ $participant->user->name }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Identity Number: </span> {{ $participant->identity_number }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Phone Number: </span> {{ $participant->phone }}
                </p>
                <p class="text-blue-700 mt-4">
                    @php $date = Carbon\Carbon::parse($participant->d_o_b); @endphp
                    <span class="font-bold"> Date Of Birth: </span> {{ $date->toFormattedDateString() }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Gender: </span> {{ $participant->gender }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Residential Address: </span> {{ $participant->residential_address }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Inkhundla: </span> {{ $participant->inkhundla }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Region: </span> {{ $participant->region }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Marital Status: </span> {{ $participant->marital_status }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Living Situation: </span> {{ $participant->living_situation }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Disability: </span> {{ $participant->disability }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Disability Name: </span> {{ $participant->disability_name }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Family Situation: </span> {{ $participant->family_situation }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Family Role: </span> {{ $participant->family_role }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Beneficiaries: </span> {{ $participant->beneficiaries }}
                </p>
            </div>
        @else
            <div class="text-center text-gray-500">
                No participant found.
            </div>
        @endif
    </div>
    @if($participant->pathway === 'TVET')
		<div class="p-6 bg-white rounded-lg shadow-md">
	    	<h2 class="text-2xl font-bold mb-4 text-center">Application Details</h2>
	        <div class="grid grid-cols-2 gap-4 mt-4">
	        	<p class="text-blue-700 mt-4">
	                <span class="font-bold"> Vocational Skill: </span> {{ $participant->TVET->vocational_skill }}
	            </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Employment Status: </span> {{ $participant->TVET->current_activity }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> If Employed, For How Long: </span> {{ $participant->TVET->duration }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Will Use The Toolkit For: </span> {{ $participant->TVET->toolkit_use }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Has Been Part Of A Youth Development Programme In The Past 6 Months: </span> {{ $participant->TVET->recent_assistance }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Do You Have A Business Account: </span> {{ $participant->TVET->account }}
                </p>
                <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Business Account Type and Number: </span> {{ $participant->TVET->account_number }}
                </p>
	        </div>
            <div>
               <p class="text-blue-700 mt-4">
                    <span class="font-bold"> Motivation To Apply To The Program: </span> {{ $participant->TVET->motivation }}
                </p> 
            </div>
            <div class="grid grid-cols-2 gap-4 mt-4">
                    @if($participant->TVET->certificate_upload)
                        <div class="carousel-container relative" data-type="certificate">
                            @foreach($participant->TVET->certificate_upload as $key => $upload)
                                @php
                                    $extension = strtolower(pathinfo($upload, PATHINFO_EXTENSION));
                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
                                @endphp

                                @if($isImage)
                                <div class="image-container {{ $loop->first ? 'active' : '' }}" data-index="{{ $loop->index }}">
                                    <div class="col-6 mb-4">
                                        <div class="flex items-center justify-center">
                                            <div class="relative">
                                                <a href="{{ asset('storage/'.$upload) }}" 
                                                   class="absolute top-0 right-0 text-blue-700 hover:underline bg-white/80 px-2 rounded"
                                                   target="_blank">
                                                    View Full Size
                                                </a>
                                                <img class="full-rounded max-h-[500px] object-contain bg-gray-50" 
                                                     src="{{ asset('storage/'.$upload) }}" 
                                                     alt="Vocational Certificate {{ $key+1 }}"
                                                     style="max-height: 500px">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="image-container">
                                    <div class="text-center mb-4">
                                        <a href="{{ asset('storage/'.$upload) }}" 
                                           class="text-blue-700 hover:underline px-2"
                                           target="_blank">
                                            Vocational Certificate - {{ $key+1 }}
                                        </a>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    @if($participant->TVET->finance_upload)
                        <div class="carousel-container relative" data-type="certificate">
                            @foreach($participant->TVET->finance_upload as $key => $upload)
                                @php
                                    $extension = strtolower(pathinfo($upload, PATHINFO_EXTENSION));
                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
                                @endphp

                                @if($isImage)
                                <div class="image-container {{ $loop->first ? 'active' : '' }}" data-index="{{ $loop->index }}">
                                    <div class="col-6 mb-4">
                                        <div class="flex items-center justify-center">
                                            <div class="relative">
                                                <a href="{{ asset('storage/'.$upload) }}" 
                                                   class="absolute top-0 right-0 text-blue-700 hover:underline bg-white/80 px-2 rounded"
                                                   target="_blank">
                                                    View Full Size
                                                </a>
                                                <img class="full-rounded max-h-[500px] object-contain bg-gray-50" 
                                                     src="{{ asset('storage/'.$upload) }}" 
                                                     alt="Vocational Certificate {{ $key+1 }}"
                                                     style="max-height: 500px">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="image-container">
                                    <div class="text-center mb-4">
                                        <a href="{{ asset('storage/'.$upload) }}" 
                                           class="text-blue-700 hover:underline px-2"
                                           target="_blank">
                                            Supporting Document - {{ $key+1 }}
                                        </a>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
            </div>
	    </div>    
    @endif

    <div class="flex justify-between mt-6">
        <x-filament::button wire:click="previousParticipant">
            Previous
        </x-filament::button>

        <x-filament::button wire:click="nextParticipant">
            Next
        </x-filament::button>
    </div>

</x-filament-panels::page>
