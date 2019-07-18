@extends('layouts.app')
@section('content')
    <{{$view}} v-bind="{{$data}}"></{{$view}}>
@endsection
