@extends('base')
@section('title', isset($product) ? 'Редактирование продукта '. $product->product_name : 'Создание нового продукта')

@section('content')
<form method="POST" 
    @if(isset($product))
        action="{{ route('products.update', $product) }}"
    @else
        action="{{ route('products.store') }}"
    @endif
>
    @csrf
    @isset($product)
        @method('PUT')
    @endisset
    <div class="row">
        <div class="col">
            <label for="product_name" class="form-label">Имя продукта</label>
            <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Введите имя продукта" 
            value="{{ old('product_name', isset($product) ? $product->product_name : null) }}">
            @error('product_name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col mb-3">
            <label for="price" class="form-label">Цена</label>
            <input type="text" name="price" id="price" class="form-control" placeholder="Введите цену продукта"
            value="{{ old('price', isset($product) ? $product->price : null) }}">
            @error('price')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <button type="submit" class="btn btn btn-outline-primary">
        @if(isset($product))
            Изменить продукт
        @else
            Создать новый продукт
        @endif
    </button>
</form>
@endsection