<?php
use Illuminate\Support\Facades\Session;

$user = Session::get('user'); 
?>
<p>To reset your password.</p>
<p>Here is your OTP: {{$otp->token}}</p>



