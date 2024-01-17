@extends('master')
@section('content')
{{View::make('nav')}}

<?php
use Illuminate\Support\Facades\Session;

$user = Session::get('user'); 
?>

{{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}
<div class="container mt-5">
    <h2>Add Item</h2>
    <form action="{{route('addnewitem')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="itemName">Name:</label>
            <input type="text" class="form-control" id="itemName" name="name" required>
        </div>
        <div class="form-group">
            <label for="itemPrice">Price:</label>
            <input type="number" class="form-control" id="itemPrice" name="price" required>
        </div>
        <div class="form-group">
            <label for="Phone">Phone:</label>
            <input type="number" class="form-control" id="Phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="itemDescription">Description:</label>
            <textarea class="form-control" id="itemDescription" name="description" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="itemImage">Upload Image:</label>
            <input type="file" class="form-control-file" id="itemImage" name="image" accept="image/*" required>
        </div>
        <input type="hidden" value="{{$user['id']}}" name='user_id'>
        <button type="submit" class="btn btn-primary">Add Item</button>
    </form>
</div>


@endsection