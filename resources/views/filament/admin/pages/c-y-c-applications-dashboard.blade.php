<x-filament-panels::page>

    <div class="p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4 text-center">Welcome, {{ Auth::user()->name }}!</h1>
        <h2 class="text-2xl font-bold mb-4 text-center">CYC Application Tracker</h2>

        <div class="grid lg:grid-cols-4 gap-x-4">
            @foreach($ParticipantsByRegion as $region => $inkhundlas)
                @php
                    $regionParticipants = $inkhundlas->collapse();
                    $region_females = $regionParticipants->where('gender', 'Female')->count();
                    $region_males = $regionParticipants->where('gender', 'Male')->count();
                    $region_total = $region_females + $region_males;
                @endphp
                
                <div class="p-4 border-l-4 border-blue-700 bg-gray-100 rounded-xl my-2">
                    <h2 class="text-xl font-bold mb-2 text-center text-blue-700">{{ $region }}</h2>
                    <div class="flex justify-between">
                        <div class="text-lg font-bold text-blue-700">Total</div>
                        <div class="text-lg font-bold text-blue-700">{{ $region_total }}</div>
                    </div>
                    <div class="flex justify-between">
                        <div class="text-lg font-bold text-blue-700">Female</div>
                        <div class="text-lg font-bold text-blue-700">{{ $region_females }}</div>
                    </div>
                    <div class="flex justify-between">
                        <div class="text-lg font-bold text-blue-700">Male</div>
                        <div class="text-lg font-bold text-blue-700">{{ $region_males }}</div>
                    </div>

                    <div class="mt-4 space-y-2">
                        @foreach($inkhundlas as $inkhundla => $participants)
                            @php
                                $inkhundla_females = $participants->where('gender', 'Female')->count();
                                $inkhundla_males = $participants->where('gender', 'Male')->count();
                            @endphp
                            <div class="pl-4 border-l-2 border-blue-300">
                                <h3 class="font-semibold text-blue-600">{{ $inkhundla }}</h3>
                                <div class="flex justify-between text-sm">
                                    <span>Total: {{ $inkhundla_females + $inkhundla_males }}</span>
                                    <span class="text-blue-600">F:{{ $inkhundla_females }} M:{{ $inkhundla_males }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="p-6 bg-white rounded-lg shadow-md">
        <h3 class="font-bold mb-4 text-center">Latest 10 Applicants Out Of 
            <span class="font-bold text-blue-700">{{ $Stats['four_regions'] }} Applicants</span>
        </h3>
        {{ $this->table }}
    </div>

    <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-center">CYC Application Tracker - Tinkhundla Distribution</h2>
        @foreach($ParticipantsByRegion as $region => $inkhundlas)
            <h2 class="text-xl font-bold mb-2 text-center text-blue-700">{{ $region }}</h2>
            <div class="grid lg:grid-cols-4 gap-x-4">
                @foreach($inkhundlas as $inkhundla => $participants)
                    @php
                        $inkhundla_males = $participants->where('gender', 'Male')->count();
                        $inkhundla_females = $participants->where('gender', 'Female')->count();
                        $inkhundla_total = $inkhundla_males + $inkhundla_females;
                    @endphp

                    <div class="p-4 border-l-4 border-blue-700 bg-gray-100 rounded-xl my-2">
                        <h2 class="text-xl font-bold mb-2 text-center text-blue-700">{{ $inkhundla }}</h2>
                        <div class="flex justify-between">
                            <div class="text-lg font-bold text-blue-700">Total</div>
                            <div class="text-lg font-bold text-blue-700">{{ $inkhundla_total }}</div>
                        </div>
                        <div class="flex justify-between">
                            <div class="text-lg font-bold text-blue-700">Female</div>
                            <div class="text-lg font-bold text-blue-700">{{ $inkhundla_females }}</div>
                        </div>
                        <div class="flex justify-between">
                            <div class="text-lg font-bold text-blue-700">Male</div>
                            <div class="text-lg font-bold text-blue-700">{{ $inkhundla_males }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

</x-filament-panels::page>
