@extends('master')
@section('content')
<?php
use Illuminate\Support\Facades\Session;
$user = Session::get('user'); 
?>


{{ View::make('nav') }}
@if(isset($message))
<div class="alert" role="alert" id="successAlert">
  {{ $message }}
</div>
@endif
  

<h2>Book Now</h2>

<img src="images/bike02.png" alt="Product Image" style="height:350px">
<form action="/booking" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" placeholder="{{ $user['name'] }}">
    @error('name')
    <p style="color: red;">{{ $message }}</p>
    @enderror

    <label for="phone">Phone:</label>
    <input type="number" id="phone" name="phone">
    @error('phone')
    <p style="color: red;">{{ $message }}</p>
    @enderror

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="{{ $user['email'] }}">
    @error('email')
    <p style="color: red;">{{ $message }}</p>
    @enderror

    <label for="image">Upload Image:</label>
    <input type="file" id="image" name="image" accept="image/*">

    <input type="hidden" name='price' value="40000">
    <input type="hidden" name="user_id" value="{{ $user['id'] }}">
    <input type="submit" value="BOOK">
</form>

<style>
    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border: 1px solid #4CAF50;
      border-radius: 4px;
      color: #4CAF50;
      background-color: #E6F4E6;
    }
  </style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
      var alertElement = document.getElementById("successAlert");
      setTimeout(function() {
        alertElement.style.display = "none";
      }, 2000);
    });
  </script>

@endsection
