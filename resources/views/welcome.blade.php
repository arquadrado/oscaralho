@extends('layouts.master')

@section('content')
<div id="welcome-page">

  <div class="content">
    <div class="image">
      <img src="{{ asset('./img/orcaralho3.png')}}" alt="">
    </div>
    
    <div class="name">{{ config('app.name', 'Orcaralho') }}</div>
    
    <div class="buttons">
    <button type="submit" class="button">
      <a href="{{ route('login') }}">
        {{ __('Login') }}
      </a>
    </button>
    <!-- <button type="submit" class="button">
      <a href="{{ route('register') }}">
        {{ __('Register') }}
      </a>
    </button> -->
    </div>
  </div>
</div>
@endsection
