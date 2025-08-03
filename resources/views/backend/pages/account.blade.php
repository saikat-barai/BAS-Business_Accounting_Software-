@extends('backend.master')
@section('content')
    @include('backend.components.account.account-list')
    @include('backend.components.account.create-account')
    @include('backend.components.account.account-delete')
    @include('backend.components.account.account-update')
@endsection