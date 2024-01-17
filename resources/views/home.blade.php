@extends('master')
@section('content')
{{ View::make('nav') }}
<?php
use Illuminate\Support\Facades\Session;

$user = Session::get('user');
?>

<h3 style="color: #333;">Items</h3>

<div class="row">
    @foreach ($data as $item)
        <div class="col-md-4">
            <div style="width: 18rem; margin: 10px;">

                <img src="storage/{{ $item['image'] }}" style="height: 250px; width: 100%;" alt="{{ $item['name'] }}">

                <div style="background-color: #fff; padding: 10px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                    <h5 style="color: #333; margin-bottom: 0;">{{ $item['name'] }}</h5>

                    <p style="color: #555; margin-top: 5px; margin-bottom: 10px;">Available today</p>

                    <h3 style="color: #4caf50; margin: 0;">Price: {{ $item['price'] }}</h3>

                    <a href="/bookingform/{{$item['id']}}" style="display: inline-block; padding: 10px 20px; background-color: #4caf50; color: #fff; text-decoration: none; margin-top: 10px; border-radius: 5px;">Book</a>
                </div>
            </div>
        </div>
    @endforeach
{{-- <div class="pagination">
    {{ $data->links() }}
</div> --}}
<div class="custom-pagination">
    <a href="{{ $data->previousPageUrl() }}" class="pagination-link">&lsaquo; Previous</a>
    <span class="pagination-current">{{ $data->currentPage() }}</span>
    <a href="{{ $data->nextPageUrl() }}" class="pagination-link">Next &rsaquo;</a>
</div>

</div>

<style>
  /* Custom Pagination Styles */
.custom-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
}

.pagination-link {
    display: inline-block;
    padding: 8px 12px;
    margin: 0 5px;
    text-decoration: none;
    color: #333;
    border: 1px solid #ddd;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.pagination-link:hover {
    background-color: #f1f1f1;
}

.pagination-current {
    display: inline-block;
    padding: 8px 12px;
    margin: 0 5px;
    background-color: #4caf50;
    color: white;
    border: 1px solid #4caf50;
    border-radius: 4px;
}

</style>

@endsection
