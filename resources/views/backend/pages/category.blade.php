@extends('backend.master')
@section('content')
    @include('backend.components.category.category-list')
    @include('backend.components.category.create-category')
    @include('backend.components.category.category-update')
@endsection