		<div class="col-sm-12 id-field">
			<input id="{{ $field->name }}" type="{{ $field->fieldtype }}" name="{{ $field->name }}" value="{{ $field->getDefault() }}">
			@if(empty($field->getDefault()))
			NUOVO
			@if(!empty($field->source_id)) (duplicato da ID {{ $field->source_id }}) @endif
			@else
			ID: {{ $field->show($field->getDefault()) }}
{{--				@if( (($updated_at = $field->getDefault('updated_at')) && substr($updated_at,0,4) != '0000') 
					|| (($record != null) && ($updated_at = $record->updated_at)))
					- Ultimo salvataggio: {{ \Esterisk\Form\Field\FieldDateTime::readable($updated_at) }}
				@endif
--}}
			@endif
			@if($field->activateDirty)
			<span class="dirty">
				@if(!empty($field->getDefault()))
				<span class="dirty-saved">
					<span class="text-success font-weight-bold">Salvato</span>
				</span>
				@else
				<span class="dirty-saved">
					<span class="text-secondary font-weight-bold">Non ancora modificato</span>
				</span>
				@endif
				<span class="dirty-dirty">
					<span class="dirty-warning">
						<span class="text-warning font-weight-bold">Modificato</span>
					</span>
					<span class="dirty-danger">
						<span class="text-danger font-weight-bold">Modificato</span>
					</span>
					Non salvi da 
					<span class="dirty-seconds"><span class="dirty-time">0</span> secondi</span>
					<span class="dirty-minutes"><span class="dirty-time">0</span> minuti</span>
				</span>
			</span>
			@endif
		</div>
	