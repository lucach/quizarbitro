<div class="row top-buffer">
	<table class='table table-condensed'> 
		<thead><tr>
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
				<td> {{ $question->question }} </td>
				@if ($answers[$index] == 1)
					<td> Vero </td>
				@else
					<td> Falso </td>
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
