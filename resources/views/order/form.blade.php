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
        action="{{ route('orders.update', $order) }}"
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
            <div id="map" style=""></div>
            <input type="text" name="address" id="address" class="form-control" placeholder="Введите адрес"
            value="{{ old('address', isset($order) ? $order->address : '') }}" readonly>
            <input type="hidden" name="coords" id="coords" value="{{ old('coords', isset($order) ? $order->coords : '') }}">
            @error('address')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row" id="selected_products">
        @isset($order)
            @foreach($order->products as $product)
            <div class='col-2'>
                <div class='card'>
                    <div class='card-header'>{{ $product->product_name }}<span class='close_button'></span></div>
                    <ul class='list-group list-group-flush'>
                        <li class='list-group-item'>Цена: {{ $product->price }}</li>
                        <li class='list-group-item'>Количество: 
                            <input type="text" name="selected_products[{{ $product->id }}][]" 
                            class="form-control quantity" data-init-value="{{ $product->pivot->quantity }}" data-price="{{ $product->price }}" value="{{ $product->pivot->quantity }}">
                        </li>
                    </ul>
                    <input type='hidden' class='product_id' name='selected_products[{{ $product->id }}][]' value="{{ $product->id }}">
                    <input type='hidden' class='product_price' value="{{ $product->price }}">
                </div>
            </div>
            @endforeach
        @endisset
    </div>
    <div class="row mb-3">
        <div class="col">
            <label for="selected_products" class="form-label">Состав заказа</label>
            <select class="js-multiple form-control" multiple="multiple"></select>
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

    // Подготовка данных для Select2.
    // TODO приводить к необходимому виду на серверной стороне.
    var data = $.map(products, function (obj) {
        obj.text = obj.product_name;

        return obj;
    });

    
    

    // Реализация выбора продуктов.
    $(document).ready(function() {
        $('.js-multiple').select2({
            data: data,
            multiple: true,
            closeOnSelect: true
        });

        // Деактивируем выборов продуктов, которые уже выбраны (при редактировании продукта).
        $('.product_id').each(function(i, obj) {
            $('option[value=' + $(obj).val() + ']').prop('disabled', true);
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

            var selected_products = $()

            var new_product = $(
                "<div class='col-2'>" +
                    "<div class='card'>" +
                        "<div class='card-header'>" + event.params.data.text + "<span class='close_button'></span></div>" +
                        "<ul class='list-group list-group-flush'>" +
                            "<li class='list-group-item'>Цена: " + price + "</li>" +
                            "<li class='list-group-item'>Количество: " +
                                "<input type='text' name='selected_products[" + event.params.data.id + "][]' class='form-control quantity' data-init-value=1 data-price=" + price + " value=1>" +
                            "</li>" +
                        "</ul>" +
                        "<input type='hidden' class='product_id' name='selected_products[" + event.params.data.id + "][]' value=" + event.params.data.id + ">" +
                        "<input type='hidden' class='product_price' value=" + price + "></div></div>"
            );

            $('#selected_products').append(new_product);
            // Блокируем повторный выбор выбранного продукта.
            $('option[value=' + event.params.data.id + ']').prop('disabled', true);
            $('.js-multiple').val(null).trigger('change');
        });

        // Event handler на удаление продукта
        $(document).on('click', 'span.close_button', function( event ) {
            // Используется delegated handler, так как новые элементы появляются в DOM только при выборе в селекте.
            console.log(1);
            var new_product_wrapper = $(this).closest(".col-2");
            var product_price = new_product_wrapper.find('.product_price').val();
            var quantity = new_product_wrapper.find('.quantity').val();
            var product_id = new_product_wrapper.find('.product_id').val();
            var current_price = document.getElementById('order_sum').value;
            // var current_price = order_sum.val();

            if (current_price != '' & current_price != 0.00) {
                document.getElementById('order_sum').value = (parseFloat(current_price) - (parseFloat(product_price) * quantity)).toFixed(2);
                new_product_wrapper.fadeOut(300, function() {
                    $(this).remove();
                });
                // Даем возможность выбрать удаленный продукт повторно.
                $('option[value=' + product_id + ']').prop('disabled', false);
            }
        });

        // Обновление цены при изменении количетсва.
        $(document).on('change', 'input.quantity', function(e) {
            var old_value = $(this).attr('data-init-value');
            var value = $(this).val();
            var price = $(this).attr('data-price');
            var order_sum = document.getElementById('order_sum').value;

            if(value > old_value) {
                let diff = ((value - old_value) * parseFloat(price)).toFixed(2);
                document.getElementById('order_sum').value = (parseFloat(order_sum) + parseFloat(diff)).toFixed(2);
            } 

            if(old_value > value) {
                let diff = ((old_value - value) * parseFloat(price)).toFixed(2);
                document.getElementById('order_sum').value = (parseFloat(order_sum) - parseFloat(diff)).toFixed(2);
            }

            $(this).attr('data-init-value', value);
        });
        

        // Валидация на клиентской стороне. На серверной части также выполняется валидация в классе запроса.
        // Сумма заказа заново формируется при сохранении модели.
        $.validator.addMethod(
            "regex",
            function(value, element, regexp) {
                if (regexp.constructor != RegExp) {
                    regexp = new RegExp(regexp);
                } else if (regexp.global) {
                    regexp.lastIndex = 0;
                }
                return this.optional(element) || regexp.test(value);
            },"Пожалуйста, укажите валидный номер телефона."
        );

        $('#order_form').validate({
            rules:{
                phone: {
                    required: false,
                    minlength: 11,
                    maxlength: 20,
                    regex: /^\+7\d{10}$|^8\d{10}$|^\+7\s\d{3}\s\d{3}\s\d{2}\s\d{2}$/,
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
            },
            messages: {
                phone: {
                    minlength: 'Минимальное количество символов 11.',
                    maxlength: 'Максимальное количество символов 20.',
                },
                email: {
                    required: 'Поле является обязательным для заполнения.',
                    email: 'Пожалуйста, укажите валидный адрес электронной почты.',
                },
                order_sum: {
                    required: 'Поле является обязательным, пожалуйста, выберите продукт.',
                    number: 'Значение поля должно быть числом.',
                    min: 'Минимальная сумма заказа 3000 руб.',
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('error').removeClass(validClass);
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('error').addClass(validClass);
            },
            errorElement: 'div',
            errorClass: 'error alert alert-danger',
        });
    });

    // Определение адреса по Яндекс картам.
    var myMap, myPlacemark, coords, myGeocoder;
    ymaps.ready(init);
    function init(){
        // Создание карты.
        myMap = new ymaps.Map("map", {
            // Москва
            center: [55.76, 37.64],
            controls: ['zoomControl'],
            zoom: 10
        });

        // Выводим маркер при редактировании заказа.
        if (document.getElementById("coords").value != ''){
            coords = document.getElementById("coords").value.split(',');
            myPlacemark = new ymaps.Placemark([coords[0], coords[1]]);
            myMap.geoObjects.add(myPlacemark);
        }
        
        // При клике создаем маркер, определяем адрес и записываем его в поле address.
        myMap.events.add('click', function (e) {
            // Сохраняем координаты клика.
            coords = e.get('coords');
            // Если маркер есть, удаляем его.
            if (myPlacemark != undefined) {
                myMap.geoObjects.remove(myPlacemark);
                document.getElementById("address").value = '';
                document.getElementById("coords").value = '';
                myPlacemark = undefined;
            }

            // Устанавливаем метку на карту, получаем адрес и указываем его в поле address.
            if (myPlacemark === undefined) {
                myPlacemark = new ymaps.Placemark([coords[0].toPrecision(6), coords[1].toPrecision(6)], {}, {
                    draggable: true
                });
                myMap.geoObjects.add(myPlacemark);
                setAddress();

                // Handler на событие перетаскивания.
                myPlacemark.events.add("dragend", function (e) {
                    coords = this.geometry.getCoordinates();
                    setAddress();
                }, myPlacemark);

                // Определяем адрес по координатам и записываем в поле address.
                function setAddress() {
                    myGeocoder = ymaps.geocode(coords, {
                        json: true,
                        results: 1,
                    });
                    myGeocoder.then(
                        function (res) {
                            let description = res.GeoObjectCollection.featureMember[0].GeoObject.description;
                            let name = res.GeoObjectCollection.featureMember[0].GeoObject.name;
                            document.getElementById("address").value = description + ', ' + name;
                            document.getElementById("coords").value = coords[0].toPrecision(6) + ',' + coords[1].toPrecision(6);
                        },
                        function (err) {
                            console.log('Ошибка API');
                        }
                    );
                }           
            }
        });
    }
</script>
@endsection