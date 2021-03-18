@extends($field->getLayoutTemplate())

@section('editfield-'.$field->name)
			<textarea id="{{ $field->name }}" class="form-control" name="{{ $field->name }}" rows="{{ $field->rows }}" placeholder="{{ $field->placeholder }}"{{ $field->isrequired() }}>{{ $field->getDefault() }}</textarea>
			
@endsection

@section('jscript')
<script>
$(document).ready(function() {
  $('#{{ $field->name }}').summernote();
});
</script>			
@endsection