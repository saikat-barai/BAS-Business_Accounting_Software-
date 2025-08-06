@extends('backend.master')
@section('content')
    @include('backend.components.payment.payment-list')
    @include('backend.components.payment.create-payment')
    @include('backend.components.payment.payment-update')
@endsection