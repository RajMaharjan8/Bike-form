<?php
use Illuminate\Support\Facades\Session;

$user = Session::get('user'); 
?>
<p>Welcome to YourApp! Thank you for registering.</p>
<p>Here is your OTP: {{$otp->token}}</p>



