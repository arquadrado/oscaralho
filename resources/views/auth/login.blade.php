@extends('layouts.master')

@section('content')
<div id="login-page">

    <div class="header">
      <div class="image">
        <img src="{{ asset('./img/orcaralho3.png')}}" alt="">
      </div>
      <div class="title">{{ __('Login') }}</div>
    </div>
    
    <div class="form">
      <form method="POST" action="{{ route('login') }}">
        @csrf

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
          <label for="" class="label">{{ __('Remember Me') }}</label>
          <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
        </div>
        
        <button type="submit" class="button">
            {{ __('Login') }}
        </button>
        
        @if (Route::has('password.request'))
            <a class="" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        @endif
        <br>
        <!-- @if (Route::has('register'))
            <a class="" href="{{ route('register') }}">
                {{ __('Register') }}
            </a>
        @endif -->
      </form>
  </div>
</div>
@endsection
