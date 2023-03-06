@extends('base')
@section('title', isset($order) ? 'Редактирование заказа '. $order->id : 'Создание нового заказа')

@section('content')
<script src="https://api-maps.yandex.ru/2.1/?apikey=fd038465-a4ec-4d89-b4a6-c4e793ffd43f&lang=ru_RU" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<form method="POST" 
    @if(isset($order))
        action="{{ route('order.update', $order) }}"
    @else
        action="{{ route('orders.store') }}"
    @endif
>
    @csrf
    @isset($order)
        @method('PUT')
    @endisset
    <div class="row mb-3">
        <div class="col">
            <label for="phone" class="form-label">Телефон</label>
            <input type="text" name="phone" id="phone" class="form-control" placeholder="+7 999 999 99 99" 
            value="{{ old('phone', isset($order) ? $order->phone : '') }}">
            @error('phone')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col">
            <label for="email" class="form-label">Электронная почта</label>
            <input type="text" name="email" id="email" class="form-control" placeholder="example@example.ru"
            value="{{ old('email', isset($order) ? $order->email : null) }}">
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label for="address" class="form-label">Адрес</label>
            <input type="text" name="address" id="address" class="form-control" placeholder="Введите адрес"
            value="{{ old('address', isset($order) ? $order->address : '') }}">
            @error('address')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row" id="selected_products">
        <div class="col-2">
            <input type="hidden" name="products[]" value=1>
            <div class="card">
                <div class="card-header">
                    TV6obLSW1
                    <span class="close_button"></span>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Цена: 3740.055</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label for="selected_products" class="form-label">Состав заказа</label>
            <select class="js-multiple form-control" name="selected_products[]" multiple="multiple"></select>
            @error('selected_products')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label for="order_sum" class="form-label">Сумма заказа</label>
            <input type="text" name="order_sum" id="order_sum" class="form-control" readonly
            value="{{ old('order_sum', isset($order) ? $order->order_sum : '') }}">
            @error('order_sum')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <button type="submit" class="btn btn btn-outline-primary">
        @if(isset($order))
            Изменить заказ
        @else
            Создать новый заказ
        @endif
    </button>
</form>
<script>
    
</script>
<script>
    var data = @php echo json_encode($products); @endphp

    var selected = @php echo json_encode(Session::getOldInput('selected_products')); @endphp

    var counter = 0

    $(document).ready(function() {

        $('.js-multiple').select2({
            data: data,
            multiple: true,
            closeOnSelect: true
        });

        if (selected != '') {
            $('.js-multiple').val(selected);
            $('.js-multiple').trigger('change');
        }

        $('.js-multiple').on('select2:select', function (e) {
            var price = e.params.data.price;
            var order_sum = $('#order_sum');
            var current_price = order_sum.val();

            if (current_price == '') {
                order_sum.val(price);
            } else {
                order_sum.val((parseFloat(current_price) + parseFloat(price)).toFixed(2));
            }

            var newdiv = $("<div>1</div>");

            $('#selected_products').append(newdiv);

        });    

        $('.js-multiple').on('select2:unselect', function (e) {
            var price = e.params.data.price;
            var order_sum = $('#order_sum');
            var current_price = order_sum.val();

            order_sum.val((parseFloat(current_price) - parseFloat(price)).toFixed(2));
        }); 

        console.log(selected)
    });
</script>
@endsection