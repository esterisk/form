{{-- richiede https://github.com/xcash/bootstrap-autocomplete --}}

		<label for="{{ $field->name }}" class="col-md-4 control-label">{{ $field->label }}</label>

		<div class="col-md-8">
			<select id="{{ $field->name }}" type="text" 
				class="form-control form-lookup" 
				name="{{ $field->name }}" 
				data-url="{{ $field->dataSrcUrl }}"
				data-noresults-text="{{ $field->noResultMessage }}"
    				autocomplete="off"
    				data-min-length=2
    				data-request-throttling=200
    				data-default-value="{{ $field->getDefault() }}"
    				data-default-text="{{ $field->getDefaultText() }}"
    				{{ $field->isrequired() }}>
    			</select>
			@if (!empty($field->help))
			<small class="form-text text-muted">{{ $field->help }}</small>
			@endif

			@if ($errors->has($field->name))
				<span class="help-block">
					<strong>{{ $errors->first($field->name) }}</strong>
				</span>
			@endif
		</div>

<script src="/js/typeahead.bundle.min.js"></script>
<script>
var {{ $field->name }}_fetcher = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  remote: {
    url: '{{ $field->dataSrcUrl }}?q=%QUERY',
    wildcard: '%QUERY'
  }
});

$('#{{ $field->name }} .form-lookup').typeahead(null, {
  name: '{{ $field->name }}',
  display: 'value',
  source: bestPictures
});
</script>