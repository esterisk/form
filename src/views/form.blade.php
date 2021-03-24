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
	<div class="row">
	@foreach ($form->mainEditFieldList as $field)
	@if(true || !$field->controlblock)
	<div class="form-group
		{{ $field->name ? 'field-'.$field->name : ''}}
		fieldtype-{{ $field->fieldtype }}
		fieldtpl-{{ $field->template }}
		{{ $errors->has($field->name) ? ' has-error' : '' }}
		{{ $field->layoutBlockCols }} ">
	@endif
		@include($field->template(), [ 'field' => $field ])
	@if(true || !$field->controlblock)
	</div>
	@endif
	@endforeach
	</div>
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
