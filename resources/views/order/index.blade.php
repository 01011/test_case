@extends('base')

@section('title', 'Заказы')

@section('content')
<a href="{{ route('orders.create') }}" class="btn btn-outline-primary">Создать новый заказ</a>

@endsection