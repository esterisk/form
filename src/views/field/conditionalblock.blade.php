		
		<div style="display:none" id="{{ $field->name }}">
			<div class="row"><div class="col-sm-12"><div class="form-wrapper {{ $field->title ? ' with-title' : '' }}">
			@if ($field->title)
				<h3>{{ $field->title }}</h3>
			@endif
			
			@foreach ($field->fields as $subfield)
			<div class="form-group row
				field-{{ $subfield->name}} 
				fieldtype-{{ $subfield->fieldtype }} 
				fieldtpl-{{ $subfield->template }}
				{{ $errors->has($subfield->name) ? ' has-error' : '' }}">
				@include('esterisk.form.field.'.$subfield->template, [ 'field' => $subfield ])
			</div>
			@endforeach
			</div></div></div>
		</div>
		<script>
			$(document).ready( function() {
				$('[name={{ $field->triggerField }}]').change( function() {
					if ($('#{{ $field->triggerField.'-'.$field->showValue }}:checked').val()
						|| $('select[name={{ $field->triggerField }}]').val() == '{{ $field->showValue }}') 
						$('#{{ $field->name }}').slideDown(); else $('#{{ $field->name }}').slideUp();
				});
			});
		</script>