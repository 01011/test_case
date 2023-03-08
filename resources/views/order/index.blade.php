@extends('base')

@section('title', 'Заказы')

@section('head')
<script src="{{ URL::asset('js/jquery-3.6.1.min.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap.bundle.min.js') }}"></script>
<link rel="stylesheet" href="{{ URL::asset('css/jquery.dataTables.css') }}">
<script src="{{ URL::asset('js/jquery.dataTables.js') }}"></script>
@endsection

@section('content')
<a href="{{ route('orders.create') }}" class="btn btn-dark mb-3">Создать новый заказ</a>
<div class="table-responsive">
    <table id="orderTable" class="display">
        <thead>
            <tr>
                <th>#</th>
                <th>Номер телефона</th>
                <th>Электронная почта</th>
                <th>Продукты</th>
                <th>Адрес</th>
                <th>Сумма заказа</th>
                <th>Дата создания</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <th>{{$order->id}}</th>
                <td>{{$order->phone}}</td>
                <td>{{$order->email}}</td>
                <td>
                    @foreach($order->products as $product)
                        <a href="{{ route('products.show', $product) }}">{{$product->product_name}}</a><br/>
                    @endforeach
                </td>
                <td>{{$order->address}}</td>
                <td>{{$order->order_sum}}</td>
                <td>{{$order->created_at}}</td>
                <td>
                    <div class="dropdown">
                        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Действия
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('orders.show', $order) }}">Подробнее</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.edit', $order) }}">Редактировать</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                            <form action="{{ route('orders.destroy', $order) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit", class="dropdown-item">Удалить</button>
                            </form>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready( function () {
        $('#orderTable').DataTable({
            language: {
                url: "{{ URL::asset('js/ru.json') }}"
            }
        });
    } );
</script>
@endsection