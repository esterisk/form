@extends($field->getLayoutTemplate())

@section('editfield-'.$field->name)
	<div class="esterisk-upl {{ $field->getDefault() ? 'filled' : '' }}">
		<div class="esterisk-upl-mask esterisk-avatar{{ $field->variant_style ? ' avatar-'.$field->variant_style : '' }}">
			<label>{{ $field->placeholder }}</label>
			<input type="file" name="{{ $field->name }}{{ $field->multiple ? '[]' : '' }}"{{ $field->multiple ? ' multiple' : '' }} id="{{ $field->name }}"{!! $field->accept() !!}>
		</div>
       	<div class="esterisk-upl-choose-wrap"><button class="btn btn-outline-secondary btn-sm esterisk-upl-choose">Scegli file…</button></div>
		<ul class="esterisk-upl-files esterisk-upl-image list-group esterisk-avatar">
@if(is_array($field->getDefault()))

@elseif ($info = $field->getDefaultValueInfo())
			<li class="list-group-item justify-content-between align-items-center"><figure><img src="{{ $info['url'] }}" alt="{{ $info['name'] }}"></figure><input type="hidden" name="{{ $field->name }}[keep]" value="{{ $info['name'] }}"/></li>
@endif
		</ul>
		<div class="esterisk-upl-cancel-wrap"><button class="btn btn-outline-secondary btn-sm esterisk-upl-cancel">Svuota</button></div>
	</div>
	<template id="{{ $field->name }}-template"><li class="list-group-item justify-content-between align-items-center" id="[id]"><figure></figure></li></template>
@endsection
