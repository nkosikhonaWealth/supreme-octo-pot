<!DOCTYPE html>
<html>
<head>
    <title>ENYC TVET Support Programme Contact Form Submission</title>
</head>
<body>
    <h2>You have a new message from {{ isset($name) ? e($name) : 'Unknown' }}</h2>
    <p><strong>Email:</strong> {{ isset($email) ? e($email) : 'Unknown' }}</p>
    <p><strong>Message:</strong></p>
    <p>{{ isset($contact_message) ? e($contact_message) : 'No message provided' }}</p>
</body>
</html>
