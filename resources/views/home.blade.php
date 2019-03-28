@extends('layouts.master')

@section('content')
        <!-- <home></home> -->
        <!-- <main-container></main-container> -->
        <router-view></router-view>
@endsection

@section('handover')
<script>
	window.handover = {
        user: {!! $user !!},
        token: '{{ csrf_token() }}',
        budgets: {!! $budgets !!},
        bounds: {!! $bounds !!},
        categories: {!! $categories !!},
	};
</script>
@endsection

@section('app')
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@endsection
