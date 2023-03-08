@extends('base')
@section('title', 'Продукт ' . $product->product_name)

@section('content')
<a href="{{ route('products.index') }}" class="come_back"><span class="back_arrow"></span> Вернуться к списку продуктов</a>
<div class="row mb-3 mt-3">
  <div class="col">
    <div class="card">
      <div class="card-header">Продукт {{ $product->id }}</div>
        <div class="card-body">
          <h5 class="card-title">{{ $product->product_name }}</h5>
        </div>
        <div class="card-footer text-muted">Цена: {{ $product->price }}</div>
    </div>
  </div>
</div>

<form action="{{ route('products.destroy', $product) }}" method="POST">
    @csrf
    @method('DELETE')
    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-dark">Редактировать</a>
    <button type="submit", class="btn btn-danger">Удалить</button>
</form>
@endsection