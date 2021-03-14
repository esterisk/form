		
		<div class="container form-block multiple-block" id="{{ $field->name }}" data-rows="{{ $field->getRowsNumber() }}">
			<div class="row"><div class="col-sm-12">
				<div class="form-wrapper {{ $field->title ? ' with-title' : '' }}">
			@if ($field->title)
				<h3>{{ $field->title }}</h3>
			@endif
			
			<div class="container">
			<div class="form-row">
			@foreach ($field->fields as $subfield)
			<div class="form-group col{{ $subfield->cols ? '-sm-'.$subfield->cols : '' }}">
				<label>{{ $subfield->label }}</label>
			</div>
			@endforeach
			<div class="col-sm-1 text-right"> </div>
			</div>
			
			<div id="{{ $field->name }}-rows" class="multiple-rows">
			@foreach ($field->getFormRows() as $n => $row)
				<div class="form-row" data-row-number="{{ '{'.$n.'}' }}">
					@foreach ($row as $subfield)
					<div class="form-group col{{ $subfield->cols ? '-sm-'.$subfield->cols : '' }}
						field-{{ $subfield->name}} 
						fieldtype-{{ $subfield->fieldtype }} 
						fieldtpl-{{ $subfield->template }}
						{{ $errors->has($subfield->name) ? ' has-error' : '' }}">
						@include('esterisk.form.field.'.$subfield->template, [ 'field' => $subfield ])
					</div>
					@endforeach
					<div class="col-sm-1 text-right"><button type="button" class="btn btn-light multiple-del-row" data-row="{{ '{'.$n.'}' }}">&times;</button></div>
				</div>
			@endforeach
			</div>
			
			<template class="multiple-row">
				<div class="form-row" data-row-number="{}" style="display:none">
				@foreach ($field->getTemplateRow() as $subfield)
				<div class="form-group col{{ $subfield->cols ? '-sm-'.$subfield->cols : '' }} field-{{ $subfield->name}} fieldtype-{{ $subfield->fieldtype }} fieldtpl-{{ $subfield->template }} {{ $errors->has($subfield->name) ? ' has-error' : '' }}">
					@include('esterisk.form.field.'.$subfield->template, [ 'field' => $subfield ])
				</div>
				@endforeach
				<div class="col-sm-1 text-right"><button type="button" class="btn btn-light multiple-del-row" data-row="{}">&times;</button></div>
				</div>		
			</template>
			
			<div class="text-right">
				<button class="btn btn-outline-secondary multiple-add">+ Aggiungi</button>
			</div>

			</div>
			
			</div></div></div>
		</div>
		<script>
			$(document).ready( function() {
				$('.multiple-add').click( function() {
					var maxnum = $(this).parents('.multiple-block').data('rows');
					$row = $(this).parents('.multiple-block').find('template.multiple-row').html();
					$row = $row.replace(/\{\}/g,'{'+maxnum+'}');
					$(this).parents('.multiple-block').find('.multiple-rows').append($row);
					$(this).parents('.multiple-block').find('.form-row[data-row-number="{'+maxnum+'}"]').slideDown();
					$(this).parents('.multiple-block').data('rows', maxnum + 1);
					return false;
				});
				
				$(document).on('click','.multiple-del-row', function() {
					var row = $(this).data('row');
					if (confirm('Sei sicuro di voler cancellare questa riga? L’operazione non è annullabile.')) {
						$(this).parents('.multiple-block').find('.form-row[data-row-number="'+row+'"]').slideUp(function() { 
							$(this).parents('.multiple-block').find('.form-row[data-row-number="'+row+'"]').remove(); 
						});
					}
					return false;
				});
			});
		</script>