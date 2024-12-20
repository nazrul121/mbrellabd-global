<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $logo = \DB::table('general_infos')->where('field','header_logo')->pluck('value')->first();
        $favicon = \DB::table('general_infos')->where('field','favicon')->pluck('value')->first();
    @endphp

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>We'll Be Back Soon</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="{{ url('storage').'/'.$favicon }}" type="image/x-icon">
  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Roboto', sans-serif;
      background: linear-gradient(to bottom right, #ffdd00, #fbb034);
      color: white;
      text-align: center;
    }
    .bg-cover {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100%;
      position: relative;
      background: url('https://source.unsplash.com/1600x900/?technology,web') no-repeat center center/cover;
      backdrop-filter: blur(5px);
    }
    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: #0f3c3f;
    }
    .content {
      position: relative;
      z-index: 2;
    }
    .card {
      background: rgba(255, 255, 255, 0.1);
      border: none;
      padding: 3rem;
      border-radius: 10px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.3);
    }
    .card img{
        max-height: 100px;
        margin-bottom: 20px;
    }
    h1 {
      font-size: 3rem;
      font-weight: 700;
    }
    p {
      font-size: 1.25rem;
      margin-bottom: 1.5rem;
    }
    .countdown {
      font-size: 2rem;
      font-weight: bold;
      color: orange;
      text-shadow: 
        2px 2px 0 red,   /* Right shadow */
        -2px 2px 0 #fff,  /* Left shadow */
        2px -2px 0 #000,  /* Top shadow */
        -2px -2px 0 #000; 
    }
    .footer {
      margin-top: 2rem;
      font-size: 1rem;
    }
    #targetTime{
    font-size: 24px;
      font-weight: bold;
      color: white;
      text-shadow: 
        2px 2px 0 orange,   /* Right shadow */
        -2px 2px 0 #000,  /* Left shadow */
        2px -2px 0 #000,  /* Top shadow */
        -2px -2px 0 #000; 
    }
  </style>
</head>
<body>


<div class="bg-cover">
  <div class="overlay"></div>
  <div class="content">
    <div class="card">
      <center> <img src="{{ url('storage').'/'.$logo }}"> </center>
      <h1>We'll Be Back Soon!</h1>
      <p>We're making improvements to our website. Stay tuned, and we'll be live shortly. <br>
        <small>Thanks for your patience!</small>
      </p>
      
      <p class="return-time">We are live at <strong id="targetTime"></strong></p>
      <div class="countdown" id="countdown"></div>
      <p class="footer">— The Team</p>
    </div>
  </div>
</div>
<script>
    // Set the target time for today at 1:30 PM
    const targetTime = new Date();
    targetTime.setHours(13, 30, 0, 0); // Set the time to 1:30 PM (24-hour format)
    
    // Display the target time in a readable format
    const options = { hour: 'numeric', minute: '2-digit', hour12: true };
    document.getElementById("targetTime").innerHTML = targetTime.toLocaleTimeString([], options);
    
    // Update the countdown every second
    const countdown = setInterval(function() {
      const now = new Date().getTime();
      const distance = targetTime - now;
    
      // Calculate time left in hours, minutes, and seconds
      const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
      // Display the countdown if time hasn't passed
      if (distance > 0) {
        document.getElementById("countdown").innerHTML = `${hours}h ${minutes}m ${seconds}s`;
      } else {
        // If the target time has passed, show "Expired"
        clearInterval(countdown);
        document.getElementById("countdown").innerHTML = "Expired";
      }
    }, 1000);
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>