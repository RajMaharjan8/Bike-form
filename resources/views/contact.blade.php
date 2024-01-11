@extends('master')
@section('content')
{{View::make('nav')}}

<?php
use Illuminate\Support\Facades\Session;

$user = Session::get('user'); 
?>


<h3>Contact Us</h3>
<div class="container">
    <form action="/contact" method="POST">
        @csrf
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="{{$user['name']}}">
        @error('name')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    
        <label for="phone">Phone</label>
        <input type="number" id="phone" name="phone" placeholder="Your Contact Information">
        @error('phone')
        <p class="text-danger">{{ $message }}</p>
        @enderror

    
        <label for="message">Message:</label>
        <textarea id="message" name="message" placeholder="Your Message"></textarea>


        <input type="hidden" name="user_id" value="{{$user['id']}}">
        <input type="submit" value="Submit">
    </form>
   

</div>
@endsection