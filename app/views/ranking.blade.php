@extends('layout')
@section('assets')
<script type="text/javascript" src="{{asset('assets/javascript/jquery.tablesorter.min.js')}}"></script>
<script type="text/javascript">
    $(function()
    {
        $("#rank-table").tablesorter({
            sortForce : [[ 1,1 ]], // sort by descending points
        });
    });
</script>
@stop
@section('content')
    <h1 class="text-center top-buffer">Classifica</h1>
    <p class="big text-center">Scopri chi conosce meglio il regolamento!</p>
    <p class="text-center">
        Il punteggio è determinato dalla difficoltà dei quiz svolti, dai
        risultati ottenuti e da quanto si è stati attivi recentemente.
    </p>
    <div class="row top-buffer">
        <div class="col-md-6 col-md-push-3">
            <table id="rank-table" class="table tablesorter table-striped text-center">
                <thead>
                  <tr>
                    <th>Nome utente</th>
                    <th>Punti</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($data as $user)
                    <tr>
                        <td> {{ $user->username }} </td>
                        <td> {{ $user->average_score }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop
