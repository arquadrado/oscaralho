@extends('layouts.master')

@section('content')
        <!-- <home></home> -->
        <main-container></main-container>
@endsection

@section('handover')
<script>
	window.handover = {
        user: {!! $user !!},
        token: '{{ csrf_token() }}',
        bounds: {!! $bounds !!},
	};
</script>
@endsection

@section('app')
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@endsection
