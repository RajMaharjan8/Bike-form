@extends('master')
@section('content')
{{View::make('nav')}}
<?php
use Illuminate\Support\Facades\Session;

$user = Session::get('user'); 
?>

<h3 style="color: #333;">Book your bike</h3>

<div style="width: 18rem; display: inline-block; margin: 10px;">

    <img src="images/bike02.png" style="height: 250px; width: 100%;" alt="Bike 02 Image">

    <div style="background-color: #fff; padding: 10px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <h5 style="color: #333; margin-bottom: 0;">Bike 02</h5>

        <p style="color: #555; margin-top: 5px; margin-bottom: 10px;">Available today</p> 


        <h3 style="color: #4caf50; margin: 0;">Price: 40,000</h3>

        <a href="/bookingform" style="display: inline-block; padding: 10px 20px; background-color: #4caf50; color: #fff; text-decoration: none; margin-top: 10px; border-radius: 5px;">Book</a>
    </div>

</div>

@endsection