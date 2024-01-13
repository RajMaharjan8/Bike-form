<?php
use Illuminate\Support\Facades\Session;

$user = Session::get('user'); 
?>

{{-- <h3>Hello {{$user['name']}}</h3> --}}
<p>Welcome to YourApp! Thank you for registering.</p>
<p>Here is your OTP: {{$otp->token}}</p>

