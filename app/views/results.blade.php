@extends((!Request::AJAX()) ? 'end' : 'results-table-layout')
@section('results-table')

<div id="questions" class="row top-buffer">
    <table class='table table-condensed'> 
        <thead><tr>
            <th class='small'>Regola</th>
            <th class='large'>Domanda</th>
            <th class='small'>Tua risposta</th>
            <th class='large'>Esito</th>
        </tr></thead>
        <tbody>
            @foreach ($questions as $index => $question)
                @if ($results[$index] == 1)
                    <tr class="success">
                @else
                    <tr class="danger">
                @endif
                <td class="text-center">
                    <p id="law-{{$index}}" data-toggle="tooltip" class="law">
                        {{ $question->law }}
                    </p>
                </td>
                <td>
                    {{ $question->question }}
                </td>
                @if ($answers[$index] == 1)
                    <td class="text-center"> Vero </td>
                @else
                    <td class="text-center"> Falso </td>
                @endif
                @if ($results[$index] == 1)
                    <td> Corretto </td>
                @else
                    <td>
                        <b> Errato </b> <br>
                        @if ($question->isTrue == 1)
                            Il quesito è vero
                        @else
                            {{ $question->correction }}
                        @endif
                    </td>
                @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script type="text/javascript">
$(function(){
    @foreach ($questions as $index => $question)
        $("#law-{{$index}}").attr('title', getDescriptionByLawID('{{ $question->law }}'));
    @endforeach
    $("[id^=law-]").tooltip({'placement': 'auto'});
});
</script>

@stop
