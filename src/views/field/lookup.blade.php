@extends($field->getLayoutTemplate())

@section('editfield-'.$field->name)
<div id="{{ $field->name }}-wrap">
  <input id="{{ $field->name }}_lookup" type="text" value="{{ $field->getDefaultLabel() }}" class="typeahead form-control" placeholder="{{ $field->placeholder }}">
</div>
<input id="{{ $field->name }}" type="hidden" name="{{ $field->name }}" value="{{ $field->getDefault() }}"{{ $field->isrequired() }}>

<script>
$('document').ready( function() {

var {{ $field->name }}_source = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  identify: function(obj) { return obj.value; },
  remote: {
	url: '{{ $field->lookupSource() }}?search=%QUERY',
	wildcard: '%QUERY'
  }
});

$('#{{ $field->name }}_lookup').typeahead({
/*  name: '{{ $field->name }}', */
  hint: true,
  highlight: true,
  minLength: 1
},
{
  display: 'label',
  source: {{ $field->name }}_source
});

$("#{{ $field->name }}_lookup").on("typeahead:selected typeahead:autocompleted", function(e,datum) { 
	$('#{{ $field->name }}').val(datum.value);
})

});
</script>
@endsection
