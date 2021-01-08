@extends('esterisk.form.field.two-col-field')

@section('editfield-'.$field->name)

	@if(!$field->relationable())
		<div class="alert alert-light border" role="alert">
		  Non puoi inserire elementi collegati finché non hai salvato il record principale.
		</div>
	@else
		{!! $field->getList() !!}
	@endif

@endsection