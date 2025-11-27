@extends('layouts.app')

@section('content')
<h1>Moje tickety</h1>
<ul>
    @foreach($tickets as $ticket)
        <li>{{ $ticket->title }} ({{ $ticket->category->name }})</li>
    @endforeach
</ul>
@endsection
