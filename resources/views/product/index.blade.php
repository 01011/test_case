@extends('base')

@section('title', 'Продукты')

@section('head')
<script src="{{ URL::asset('js/jquery-3.6.1.min.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap.bundle.min.js') }}"></script>
<link rel="stylesheet" href="{{ URL::asset('css/jquery.dataTables.css') }}">
<script src="{{ URL::asset('js/jquery.dataTables.js') }}"></script>
@endsection

@section('content')
<a href="{{ route('products.create') }}" class="btn btn-dark mb-3">Создать новый продукт</a>
<table id="productTable" class="display">
  <thead>
    <tr>
      <th>#</th>
      <th>Название продукта</th>
      <th>Цена</th>
      <th>Действия</th>
    </tr>
  </thead>
  <tbody>
    @foreach($products as $product)
    <tr>
      <th>{{ $product->id }}</th>
      <td>{{ $product->product_name }}</td>
      <td>{{ $product->price }}</td>
      <td>
        <div class="dropdown">
            <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Действия
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('products.show', $product) }}">Подробнее</a></li>
                <li><a class="dropdown-item" href="{{ route('products.edit', $product) }}">Редактировать</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form action="{{ route('products.destroy', $product) }}" method="POST">
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

<script>
    $(document).ready( function () {
        $('#productTable').DataTable({
            language: {
                url: "{{ URL::asset('js/ru.json') }}"
            }
        });
    } );
</script>
@endsection