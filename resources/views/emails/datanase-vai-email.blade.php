
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Database file backup</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">

<div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
    <header style="background-color: #007bff; color: #ffffff; text-align: center; padding: 20px;">
        <h1>Database file backup</h1>
    </header>
    
    <div style="padding: 20px;">
        <p>Hellow {{$email}}</p>
        <p><b>{{$subject}}</b></p>
        
        <p>{{$body}}</p> <br> <br><br>
        
        <p>Mail from <br>{{$mail_from}}</p>
    </div>
    
    <footer style="background-color: #f8f8f8; padding: 10px; text-align: center;">
        <p>&copy; {{date('Y')}} {{ env('APP_NAME') }}. All rights reserved.</p>
    </footer>
</div>

</body>
</html>

