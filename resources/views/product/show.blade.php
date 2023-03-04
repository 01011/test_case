@extends('base')
@section('title', 'Продукт ' . $product->product_name)

@section('content')
<a href="{{ route('products.index') }}" class="btn btn-outline-secondary mb-3">Вернуться к списку продуктов</a>
<div class="card mb-3" style="width: 18rem;">
  <div class="card-header">
    {{ $product->product_name }}
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">Цена: {{ $product->price }}</li>
  </ul>
</div>
<form action="{{ route('products.destroy', $product) }}" method="POST">
    @csrf
    @method('DELETE')
    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary">Редактировать</a>
    <button type="submit", class="btn btn-outline-danger">Удалить</button>
</form>
@endsection