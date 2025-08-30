<x-filament-panels::page>
	<div class="space-y-6">
        <!-- Region Statistics -->
        <div class="grid lg:grid-cols-4 gap-x-4">
            @foreach($regions as $regionName => $regionData)
                <div class="p-4 border-l-4 border-blue-700 bg-gray-100 rounded-xl my-2">
                    <h2 class="text-xl font-bold mb-2 text-center text-blue-700">{{ $regionName }}</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700">Total:</span>
                            <span class="font-bold">{{ $regionData['total'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700">♂ Male:</span>
                            <span>{{ $regionData['male'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700">♀ Female:</span>
                            <span>{{ $regionData['female'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Vocational Skills Statistics -->
        <div class="grid lg:grid-cols-4 gap-x-4">
            @foreach($skills as $skillName => $skillData)
                <div class="p-4 border-l-4 border-blue-700 bg-gray-100 rounded-xl my-2">
                    <h2 class="text-xl font-bold mb-2 text-center text-blue-700">{{ $skillName }}</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700">Total:</span>
                            <span class="font-bold">{{ $skillData['total'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700">♂ Male:</span>
                            <span>{{ $skillData['male'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700">♀ Female:</span>
                            <span>{{ $skillData['female'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Results Table -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-4 text-center">Participant Results</h2>
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
