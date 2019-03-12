@extends('layouts.master')

@section('content')

<div id="register-page">

  <div class="header">
    <div class="image">
      <img src="{{ asset('./img/orcaralho3.png')}}" alt="">
    </div>
    <div class="title">{{ __('Register') }}</div>
  </div>

  <div class="form">
    <form method="POST" action="{{ route('register') }}">
      @csrf

      <div class="field">
        <label for="name" class="label">{{ __('Name') }}</label>
        <input id="name" type="name" class="input{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
        @if ($errors->has('name'))
        <span class="error-message" role="alert">
            <strong>{{ $errors->first('name') }}</strong>
        </span>
        @endif
      </div>
      
      <div class="field">
        <label for="email" class="label">{{ __('E-Mail Address') }}</label>
        <input id="email" type="email" class="input{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
        @if ($errors->has('email'))
        <span class="error-message" role="alert">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
        @endif
      </div>
      
      <div class="field">
        <label for="password" class="label">{{ __('Password') }}</label>
        <input id="password" type="password" class="input{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ old('password') }}" required autofocus>
        @if ($errors->has('password'))
        <span class="error-message" role="alert">
            <strong>{{ $errors->first('password') }}</strong>
        </span>
        @endif
      </div>

      <div class="field">
        <label for="password_confirmation" class="label">{{ __('Password confirmation') }}</label>
        <input id="password-confirm" type="password" class="input" name="password_confirmation" required autofocus>
      </div>
      
      <button type="submit" class="button">
          {{ __('Register') }}
      </button>

      @if (Route::has('login'))
          <a class="" href="{{ route('login') }}">
              {{ __('Log in') }}
          </a>
      @endif

    </form>
  </div>

</div>

@endsection
