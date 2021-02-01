
		<div class="container form-block multiple-block" id="{{ $field->name }}">
			<div class="row"><div class="col-sm-12">
				<div class="form-wrapper {{ $field->title ? ' with-title' : '' }}">
			@if ($field->title)
				<h3>{{ $field->title }}</h3>
			@endif

			<div class="container">
			<div class="form-row form-row-labels"{!! $field->showLabels() ? '' : ' style="display:none"' !!}>
			@foreach ($field->getLabels() as $label => $cols)
			<div class="form-group col{{ $cols ? '-sm-'.$cols : '' }}">
				<label>{{ $label }}</label>
			</div>
			@endforeach
			<div class="col-sm-1 text-right"> </div>
			</div>

			<div id="{{ $field->name }}-rows" class="multiple-rows">
			@php $rowmax = 0; @endphp
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
        			@php $rowmax = $n > $rowmax ? $n : $rowmax; @endphp
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
				<button class="btn btn-outline-secondary multiple-add" data-max-num="{{ $rowmax + 1 }}">+ Aggiungi</button>
			</div>

			</div>

			</div></div></div>
		</div>
