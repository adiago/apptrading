@extends('layouts.app')

@section('content')
    <div class="container">
        <account :user="{{Auth::user()}}"></account>
    </div>
@endsection