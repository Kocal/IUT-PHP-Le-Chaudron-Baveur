@extends('layouts.default')

@section('title', e($item->name))

@section('content')
    {!! $item !!}
@endsection
