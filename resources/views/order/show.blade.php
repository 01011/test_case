@extends('base')
@section('title', 'Заказ ' . $order->id)

@section('content')
<a href="{{ route('orders.index') }}" class="come_back"><span class="back_arrow"></span> Вернуться к списку заказов</a>
<div class="row mb-3">
  <div class="col">
    <div class="card">
      <div class="card-header">Заказ {{ $order->id }}</div>
        <div class="card-body">
          <h5 class="card-title">Контакты:</h5>
          <div class="row mb-3">
            <div class="col card-text">Телефон: <strong>{{$order->phone}}</strong></div>
            <div class="col card-text">Электронная почта: <strong>{{$order->email}}</strong></div>
          </div>
          <h5 class="card-title">Адрес:</h5>
          <p class="card-text">{{ $order->address }}</p>
          <h5 class="card-title">Состав заказа:</h5>
          <div class="row mb-3">
            @foreach($order->products as $product)
            <div class='col-2'>
              <div class='card'>
                <div class='card-header'>{{ $product->product_name }}</div>
                <ul class='list-group list-group-flush'>
                  <li class='list-group-item'>Цена: {{ $product->price }}</li>
                  <li class='list-group-item'>Количество: {{ $product->pivot->quantity }}</li>
                </ul>
              </div>
            </div>
            @endforeach
          </div>
          <h5 class="card-title">Сумма заказа: {{$order->order_sum}}</h5>
        </div>
        <div class="card-footer text-muted">Cоздан: {{ $order->created_at }}, Обновлен: {{ $order->updated_at }}</div>
    </div>
  </div>
</div>
<form action="{{ route('orders.destroy', $order) }}" method="POST">
    @csrf
    @method('DELETE')
    <a href="{{ route('orders.edit', $order) }}" class="btn btn-outline-dark">Редактировать</a>
    <button type="submit", class="btn btn-danger">Удалить</button>
</form>
@endsection