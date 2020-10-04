		
		<div style="display:none" id="{{ $field->name }}">
			@foreach ($field->fields as $subfield)
			<div class="form-group 
				field-{{ $subfield->name}} 
				fieldtype-{{ $subfield->fieldtype }} 
				fieldtpl-{{ $subfield->template }}
				{{ $errors->has($subfield->name) ? ' has-error' : '' }}">
				@include('form.'.$subfield->template, [ 'field' => $subfield ])
			</div>
			@endforeach
		</div>
		<script>
			$(document).ready( function() {
				$('[name={{ $field->triggerField }}]').change( function() {
					if ($('#{{ $field->triggerField.'-'.$field->showValue }}:checked').val()) 
						$('#{{ $field->name }}').slideDown(); else $('#{{ $field->name }}').slideUp();
				});
			});
		</script>