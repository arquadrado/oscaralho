@extends('layouts.master')

@section('content')
        <!-- <home></home> -->
        <grid></grid>
@endsection

@section('handover')
<script>
	window.handover = {
        user: {!! $user !!},
        token: '{{ csrf_token() }}',
	};
</script>
@endsection

@section('app')
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@endsection
