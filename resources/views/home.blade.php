@extends('layouts.app')

@section('content')
    <div class="container">
        <base-home :user-id={{Auth::id()}}></base-home>
    </div>
@endsection