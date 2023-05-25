<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Email Verification</title>
 <link rel="shortcut icon" href="favicon/question.png" type="image/x-icon">
 <!-- CSS styles -->
 <style>
 * {
 box-sizing: border-box;
 margin: 0;
 padding: 0;
 }

 body {
 font-family: 'Lato', sans-serif;
 background-color: #f9f9f9;
 display: flex;
 justify-content: center;
 align-items: center;
 height: 100vh;
 }

 .container {
 text-align: center;
 max-width: 600px;
 padding: 20px;
 border-radius: 10px;
 box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
 background-color: #fff;
 transition: transform 0.3s ease-in-out;
 }

 .container:hover {
 transform: scale(1.05);
 }

 h2 {
 color: #333;
 margin-bottom: 20px;
 }

 p {
 font-size: 16px;
 color: #777;
 margin-bottom: 20px;
 }

 a {
 color: #007bff;
 text-decoration: none;
 }

 .icon {
 width: 100px;
 height: 100px;
 margin-bottom: 20px;
 }

 </style>
</head>

<body>
 <div class="container">
 <img src="https://cdn-icons-png.flaticon.com/512/179/179386.png" alt="Email icon" class="icon">
 <h2>Verification Pending</h2>
 <p>Your account registration is in pending verification.</p>
 <p>Please check your email for the verification link.</p>
 <p>If you haven't received the email, make sure to check your spam folder.</p>
 <p><a href="login.php">Go to Login</a></p>
 </div>
</body>

</html>
