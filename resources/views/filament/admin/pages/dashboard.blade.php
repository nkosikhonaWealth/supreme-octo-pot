<x-filament-panels::page>

    <div class="p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4 text-center">Welcome, {{ Auth::user()->name }}!</h1>
        <h2 class="text-2xl font-bold mb-4 text-center">TVET Application Tracker</h2>
        <div class="grid lg:grid-cols-4 gap-x-4">
    		<div class="p-4 border-l-4 border-blue-700 bg-gray-100 rounded-xl my-2">
    			<h2 class="text-xl font-bold mb-2 text-center text-blue-700">Hhohho</h2>
    			<div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-6 justify-self-start">
		            	Total
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-6 justify-self-end">
	                	{{$hhohho_females + $hhohho_males}}
	                </div>
                </div>
                <div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-6 justify-self-start">
		            	Female
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-6 justify-self-end">
	                	{{$hhohho_females}}
	                </div>
                </div>
                <div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-6 justify-self-start">
		            	Male
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-6 justify-self-end">
	                	{{$hhohho_males}}
	                </div>
                </div>
            </div>
            <div class="p-4 border-l-4 border-blue-700 bg-gray-100 rounded-xl my-2">
    			<h2 class="text-xl font-bold mb-2 text-center text-blue-700">Lubombo</h2>
    			<div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-6 justify-self-start">
		            	Total
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-6 justify-self-end">
	                	{{$lubombo_females + $lubombo_males}}
	                </div>
                </div>
                <div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-6 justify-self-start">
		            	Female
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-6 justify-self-end">
	                	{{$lubombo_females}}
	                </div>
                </div>
                <div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-6 justify-self-start">
		            	Male
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-6 justify-self-end">
	                	{{$lubombo_males}}
	                </div>
                </div>
            </div>
            <div class="p-4 border-l-4 border-blue-700 bg-gray-100 rounded-xl my-2">
    			<h2 class="text-xl font-bold mb-2 text-center text-blue-700">Manzini</h2>
    			<div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-6 justify-self-start">
		            	Total
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-6 justify-self-end">
	                	{{$manzini_females + $manzini_males}}
	                </div>
                </div>
                <div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-6 justify-self-start">
		            	Female
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-6 justify-self-end">
	                	{{$manzini_females}}
	                </div>
                </div>
                <div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-6 justify-self-start">
		            	Male
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-6 justify-self-end">
	                	{{$manzini_males}}
	                </div>
                </div>
            </div>
            <div class="p-4 border-l-4 border-blue-700 bg-gray-100 rounded-xl my-2">
    			<h2 class="text-xl font-bold mb-2 text-center text-blue-700">Shiselweni</h2>
    			<div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-6 justify-self-start">
		            	Total
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-6 justify-self-end">
	                	{{$shiselweni_females + $shiselweni_males}}
	                </div>
                </div>
                <div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-6 justify-self-start">
		            	Female
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-6 justify-self-end">
	                	{{$shiselweni_females}}
	                </div>
                </div>
                <div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-6 justify-self-start">
		            	Male
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-6 justify-self-end">
	                	{{$shiselweni_males}}
	                </div>
                </div>
            </div>
        </div>
        <h2 class="text-2xl font-bold my-4 text-center">Toolkit Distribution Tracker</h2>
        <div class="grid lg:grid-cols-3 gap-3">
    		<div class="p-4 border-l-4 border-blue-700 bg-gray-100 rounded-xl">
    			<h2 class="text-xl font-bold mb-2 text-center text-blue-700">Carpentry - {{$carpentry_females + $carpentry_males}}</h2>
    			<div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-3 justify-self-start">
		            	Female
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-3 justify-self-end">
	                	{{$carpentry_females}}
	                </div>
	                <div class="text-lg font-bold text-blue-700 text-center col-md-3 justify-self-start">
		            	Male
		            </div>
		            <div class="text-lg font-bold text-blue-700 col-md-3 justify-self-end">
	                	{{$carpentry_males}}
	                </div>
                </div>
            </div>
            <div class="p-4 border-l-4 border-blue-700 bg-gray-100 rounded-xl">
    			<h2 class="text-xl font-bold mb-2 text-center text-blue-700">Electrician - {{$electrician_females + $electrician_males}}</h2>
    			<div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-3 justify-self-start">
		            	Female
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-3 justify-self-end">
	                	{{$electrician_females}}
	                </div>
	                <div class="text-lg font-bold text-blue-700 text-center col-md-3 justify-self-start">
		            	Male
		            </div>
		            <div class="text-lg font-bold text-blue-700 col-md-3 justify-self-end">
	                	{{$electrician_males}}
	                </div>
                </div>
            </div>
            <div class="p-4 border-l-4 border-blue-700 bg-gray-100 rounded-xl">
    			<h2 class="text-xl font-bold mb-2 text-center text-blue-700">Sewing - {{$sewing_females + $sewing_males}}</h2>
    			<div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-3 justify-self-start">
		            	Female
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-3 justify-self-end">
	                	{{$sewing_females}}
	                </div>
	                <div class="text-lg font-bold text-blue-700 text-center col-md-3 justify-self-start">
		            	Male
		            </div>
		            <div class="text-lg font-bold text-blue-700 col-md-3 justify-self-end">
	                	{{$sewing_males}}
	                </div>
                </div>
            </div>
            <div class="p-4 border-l-4 border-blue-700 bg-gray-100 rounded-xl">
    			<h2 class="text-xl font-bold mb-2 text-center text-blue-700">Plumbing - {{$plumbing_females + $plumbing_males}}</h2>
    			<div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-3 justify-self-start">
		            	Female
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-3 justify-self-end">
	                	{{$plumbing_females}}
	                </div>
	                <div class="text-lg font-bold text-blue-700 text-center col-md-3 justify-self-start">
		            	Male
		            </div>
		            <div class="text-lg font-bold text-blue-700 col-md-3 justify-self-end">
	                	{{$plumbing_males}}
	                </div>
                </div>
            </div>
            <div class="p-4 border-l-4 border-blue-700 bg-gray-100 rounded-xl">
    			<h2 class="text-xl font-bold mb-2 text-center text-blue-700">Motor Mechanic - {{$motor_mechanic_females + $motor_mechanic_males}}</h2>
    			<div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-3 justify-self-start">
		            	Female
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-3 justify-self-end">
	                	{{$motor_mechanic_females}}
	                </div>
	                <div class="text-lg font-bold text-blue-700 text-center col-md-3 justify-self-start">
		            	Male
		            </div>
		            <div class="text-lg font-bold text-blue-700 col-md-3 justify-self-end">
	                	{{$motor_mechanic_males}}
	                </div>
                </div>
            </div>
            <div class="p-4 border-l-4 border-blue-700 bg-gray-100 rounded-xl">
    			<h2 class="text-xl font-bold mb-2 text-center text-blue-700">Welding - {{$welding_females + $welding_males}}</h2>
    			<div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-3 justify-self-start">
		            	Female
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-3 justify-self-end">
	                	{{$welding_females}}
	                </div>
	                <div class="text-lg font-bold text-blue-700 text-center col-md-3 justify-self-start">
		            	Male
		            </div>
		            <div class="text-lg font-bold text-blue-700 col-md-3 justify-self-end">
	                	{{$welding_males}}
	                </div>
                </div>
            </div>
            <h2 class="text-2xl font-bold my-4 text-center">Total Participants - Gender Distribution</h2>
            <div class="p-4 border-l-4 border-blue-700 bg-gray-100 rounded-xl">
    			<h2 class="text-xl font-bold mb-2 text-center text-blue-700">Total Participants - {{$all_females + $all_males}}</h2>
    			<div class="flex flex-row justify-between content-center">
		            <div class="text-lg font-bold text-blue-700 text-center col-md-3 justify-self-start">
		            	Female
		            </div>
	                <div class="text-lg font-bold text-blue-700 col-md-3 justify-self-end">
	                	{{$all_females}}
	                </div>
	                <div class="text-lg font-bold text-blue-700 text-center col-md-3 justify-self-start">
		            	Male
		            </div>
		            <div class="text-lg font-bold text-blue-700 col-md-3 justify-self-end">
	                	{{$all_males}}
	                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h3 class="font-bold mb-4 text-center">Lastest 10 Participants Out Of 
        	<span class="font-bold text-blue-700">{{ $four_regions }} Participants</span>
        </h3>
        {{$this->table}}
    </div>

</x-filament-panels::page>
