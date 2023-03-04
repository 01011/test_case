@extends('base')

@section('title', 'Продукты')

@section('content')
<a href="{{ route('products.create') }}" class="btn btn-outline-primary">Создать новый продукт</a>
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Название продукта</th>
        <th scope="col">Цена</th>
        <th scope="col">Действия</th>
      </tr>
    </thead>
    <tbody>
      @foreach($products as $product)
      <tr>
        <th scope="row">{{ $product->id }}</th>
        <td><a href="{{ route('products.show', $product) }}">{{ $product->product_name }}</a></td>
        <td>{{ $product->price }}</td>
        <td>
          <form action="{{ route('products.destroy', $product) }}" method="POST">
              @csrf
              @method('DELETE')
              <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary">Редактировать</a>
              <button type="submit", class="btn btn-outline-danger">Удалить</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
{{ $products->links() }}
@endsection