@extends('backend.master')
@section('content')
    @include('backend.components.invoice.invoice-list')
    @include('backend.components.invoice.create-invoice')
    @include('backend.components.invoice.show-invoice')
    @include('backend.components.invoice.invoice-update')
@endsection