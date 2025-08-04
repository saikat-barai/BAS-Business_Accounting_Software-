@extends('backend.master')
@section('content')
    @include('backend.components.client.client-list')
    @include('backend.components.client.create-client')
    @include('backend.components.client.client-update')
@endsection