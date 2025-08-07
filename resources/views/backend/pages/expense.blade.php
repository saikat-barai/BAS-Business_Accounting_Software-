@extends('backend.master')
@section('content')
    @include('backend.components.expense.expense-list')
    @include('backend.components.expense.create-expense')
    @include('backend.components.expense.expense-update')
@endsection