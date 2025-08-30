<!DOCTYPE html>
<html>
<head>
    <title>ENYC TVET Support Programme Application Processed</title>
</head>
<body>
    <h2 class="fw-bold">Good Day {{$user->name}} </h2> <br>
    <p>Your application has been reviewed by the ENYC TVET Support Programme Team, with the following results: </p>
    @if($participant->pathway === 'mindset')
    <p class="text- capitalize"><span class="font-weight-bold">Pathway:</span> Mindset Change Pathway.</p>
    @elseif($participant->pathway === 'tvet')
    <p class="text- capitalize"><span class="font-weight-bold">Pathway:</span> TVET Pathway.</p>
    @endif  
    <p class="text- capitalize"><span class="font-weight-bold">Application Status:</span> {{$application->status}}.</p>
    <p class="text- capitalize"><span class="font-weight-bold">Recommendation:</span> {{$application->recommendation}}.</p>
    <p>Please keep checking emails from the ENYC TVET Support Programme Team for more information and updates.</p>
</body>
</html>
