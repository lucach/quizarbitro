@extends('layout')
@section('content')
    <h3 class="text-center" style="margin-top:10%">Benvenuto. Per iniziare, scegli un link dal menu in alto.</h3>

<script type="text/javascript">
        @if (Session::has('info'))
            showNotification('info', "{{ Session::get("info") }}");
        @endif

</script>

@stop