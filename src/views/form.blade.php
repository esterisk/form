@if ($errors->any())
<div class="alert alert-warning" role="alert">
<p>Ci sono errori nei campi:</p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
</div>
@endif

<form id="editor-form" class="esterisk-form form-horizontal{{ $form->htmlclass ? ' '.$form->htmlclass : '' }}" method="{{ $form->method }}" action="{{ $form->action }}" enctype="multipart/form-data">
	@if ($form->method == 'post')
	{{ csrf_field() }}
	@endif
	@foreach ($form->mainEditFieldList as $field)
	@if(!$field->controlblock)
	<div class="form-group row
		field-{{ $field->name}}
		fieldtype-{{ $field->fieldtype }}
		fieldtpl-{{ $field->template }}
		{{ $errors->has($field->name) ? ' has-error' : '' }}">
	@endif
		@include('esterisk.form.field.'.$field->template, [ 'field' => $field ])
	@if(!$field->controlblock)
	</div>
	@endif
	@endforeach
</form>

@section('jscript')
<script src="/js/esterisk-form.js"></script>
	@foreach ($form->scriptLibs as $lib)
<script src="{{ $lib }}"></script>
	@endforeach

@parent
	@foreach ($form->fields as $field)
		@if (method_exists($field, 'initScript'))
			<script>
			{!! $field->initScript() !!}
			</script>
		@endif
	@endforeach
@endsection
