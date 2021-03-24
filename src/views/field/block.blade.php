@extends($field->getLayoutTemplate())

@section('blockfield-'.$field->name)

		@if($field->triggerButton)
		<button type="button" class="btn btn-outline-secondary form-wrapper-opener" rel="{{ $field->name }}">Apri {{ $field->title }}</button>
		@endif

		<div class="form-block" {!! ($field->trigger || $field->triggerButton) ? ' style="display:none"' : '' !!} id="{{ $field->name }}"{!! $field->trigger ? ' data-condition-trigger="'.$field->trigger.'"' : '' !!}>
			<div class="row"><div class="col-sm-12">

				<div class="{{ $field->border ? 'form-wrapper ' : '' }}{{ $field->title ? ' with-title' : '' }}">
			@if ($field->title)
				<h3>{{ $field->title }}</h3>
			@endif

        	<div class="row">
			@foreach ($field->fields as $subfield)
			<div class="form-group
        		{{ $field->name ? 'field-'.$field->name : ''}}
				fieldtype-{{ $subfield->fieldtype }}
				fieldtpl-{{ $subfield->template }}
				{{ $errors->has($subfield->name) ? ' has-error' : '' }}
        		{{ $subfield->layoutBlockCols }} ">
				@include($subfield->template(), [ 'field' => $subfield ])
			</div>
			@endforeach
			</div>
				</div>

			</div></div>

			@if($field->triggerButton)
			<button type="button" class="btn btn-outline-secondary form-wrapper-closer" rel="{{ $field->name }}">Chiudi {{ $field->title }}</button>
			@endif
		</div>
		@if($field->triggerButton)
		<script>
			$(document).ready( function() {
				$(document).on('click', '.form-wrapper-opener[rel="{{ $field->name }}"]', function() { $('#{{ $field->name }}').slideDown(); $('.form-wrapper-opener[rel="{{ $field->name }}"]').slideUp(); })
				$(document).on('click', '.form-wrapper-closer[rel="{{ $field->name }}"]', function() { $('#{{ $field->name }}').slideUp(); $('.form-wrapper-opener[rel="{{ $field->name }}"]').slideDown(); })
			});
		</script>
		@endif

@endsection
