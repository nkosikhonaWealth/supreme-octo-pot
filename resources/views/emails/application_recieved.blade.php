<!DOCTYPE html>
<html>
<head>
    <title>ENYC Commonwealth Youth Council Application Received</title>
</head>
<body>
    <h2 class="fw-bold">Good Day {{$user->name}} </h2> <br>
    <p>Your application to be a Commonwealth Youth Council Member has been recieved.
    </p>
    @if($participant->pathway === 'Mindset')
    <p class="text- capitalize">You have applied for the Mindset Change Pathway and your application is yet to be reviewed. You will be informed in due time on the progress of your application</p>
    @elseif($participant->pathway === 'TVET')
    <p class="text- capitalize">You have applied for the TVET Pathway and your application is yet to be reviewed. You will be informed in due time on the progress of your application.</p>
    @else
    @endif
    <p>Please keep on checking your emails for more updates regarding the Commonwealth Youth Council Member.</p>
</body>
</html>
