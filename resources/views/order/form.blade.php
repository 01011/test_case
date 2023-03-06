@extends('base')
@section('title', isset($order) ? 'Редактирование заказа '. $order->id : 'Создание нового заказа')

@section('content')
<script src="https://api-maps.yandex.ru/2.1/?apikey=fd038465-a4ec-4d89-b4a6-c4e793ffd43f&lang=ru_RU" type="text/javascript"></script>
<script src="{{ URL::asset('js/jquery-3.6.1.min.js') }}"></script>
<link href="{{ URL::asset('css/select2.min.css') }}" rel="stylesheet" />
<script src="{{ URL::asset('js/select2.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.validate.min.js') }}"></script>


<form method="POST" 
    @if(isset($order))
        action="{{ route('order.update', $order) }}"
    @else
        action="{{ route('orders.store') }}"
    @endif
id='order_form'>
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
            value="{{ old('email', isset($order) ? $order->email : null) }}" required>
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
            <input type="text" name="order_sum" id="order_sum" class="form-control" readonly required
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
    // TODO: Если продуктов будет более 1000, переписать на ajax
    var products = @php echo json_encode($products); @endphp

    var data = $.map(products, function (obj) {
        obj.text = obj.product_name;

        return obj;
    });

    // var selected = @php echo json_encode(Session::getOldInput('selected_products')); @endphp

    $(document).ready(function() {
        $('.js-multiple').select2({
            data: data,
            multiple: true,
            closeOnSelect: true
        });

        // Event handler на выбор продукта
        $('.js-multiple').on('select2:select', function ( event ) {
            var price = event.params.data.price;
            var order_sum = $('#order_sum');
            var current_price = order_sum.val();

            if (current_price == '') {
                order_sum.val(price);
            } else {
                order_sum.val((parseFloat(current_price) + parseFloat(price)).toFixed(2));
            }

            var new_product = $("<div class='col-2'><div class='card'><div class='card-header'>" + event.params.data.text + 
                        "<span class='close_button'></span></div><ul class='list-group list-group-flush'><li class='list-group-item'>Цена: " + price +
                        "</li></ul><input type='hidden' class='product_id' name='selected_products[]' value=" + event.params.data.id +
                        "><input type='hidden' class='product_price' value=" + price + "></div></div>");

            $('#selected_products').append(new_product);
            $('.js-multiple').val(null).trigger('change');
        });

        $('#order_form').validate({
            rules:{
                phone: {
                    required: false,
                    minlength: 11,
                    maxlength: 20
                },
                email: {
                    required: true,
                    email: true
                },
                order_sum: {
                    required: true,
                    number: true,
                    min: 3000
                }
            }
        })
    });

    // Event handler на удаление продукта
    $('#selected_products').on('click', '.close_button, .card-header, .card, .col-2', function( event ) {
        // Используется delegated handler, так как новые элементы появляются в DOM только при выборе в селекте.
        // Нужно условие, так как delegated события выполняются на всех родительских элементах.
        if (event.currentTarget.className == 'close_button') {
            var new_product_wrapper = $(this).closest(".col-2");
            var product_price = new_product_wrapper.find('.product_price').val();
            var product_id = new_product_wrapper.find('.product_id').val();
            var order_sum = $('#order_sum');
            var current_price = order_sum.val();

            if (current_price != '' & current_price != 0.00) {
                order_sum.val((parseFloat(current_price) - parseFloat(product_price)).toFixed(2));
                new_product_wrapper.fadeOut(300);
            }
        }
    });


</script>
@endsection