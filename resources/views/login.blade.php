
<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход в административную панель</title>
    <!-- Bootstrap core CSS -->
    <link href="{{ URL::asset( 'css/bootstrap.min.css' ) }}" rel="stylesheet">
    <link href="{{ URL::asset( 'css/style.css' ) }}" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
  </head>
  <body class="text-center">
    <main class="form-signin">
    <form action="{{ route('user-login') }}" method="POST">
        @csrf
        <img class="mb-4" src="{{ URL::asset( 'img/key.svg' )}}">
        <h1 class="h3 mb-3 fw-normal">Вход в админимтративную панель</h1>
        @if(Session::has('fail'))
        <div class="alert alert-danger">{{Session::get('fail')}}</div>
        @endif
        <div class="form-floating">
            <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="{{ old('email') }}">
            <label for="floatingInput">Электронная почта</label>
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" value="{{ old('password') }}">
            <label for="floatingPassword">Пароль</label>
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button class="w-100 btn btn-lg btn-dark" type="submit">Войти</button>
    </form>
    </main>
  </body>
</html>
