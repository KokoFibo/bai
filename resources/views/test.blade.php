@extends('layouts.app2')

@section('title', 'Karyawan')

@section('content')
    <div x-data="{ count: 0 }">
        <button x-on:click="count++">Increment</button>

        <span x-text="count"></span>
        <button @click="alert('Hello World!')">Say Hi</button>
    </div>
@endsection
