@extends($field->baseTemplate)

@section('editfield-'.$field->name)
		<table class="table table-striped border">
			@foreach ($field->getDefault() as $item)
			<tr>
			@foreach ($field->showItem($item) as $col)
				<td>{{ $col }}</td>
			@endforeach
				<td><a class="btn btn-sm btn-outline-secondary float-right" data-command-title="Modifica" data-command-url="{{ $field->editItemLink($item) }}" data-command-method="get">Modifica</a></td>
			</tr>
			@endforeach
		</table>
		<p style="margin-top:10px;"><a class="btn btn-outline-secondary float-right" data-command-title="Aggiungi" data-command-url="{{ $field->newItemLink() }}" data-command-method="get">Aggiungi</a></p>
@endsection