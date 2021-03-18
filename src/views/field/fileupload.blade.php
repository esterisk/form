@extends($field->getLayoutTemplate())

@section('editfield-'.$field->name)
	<div class="esterisk-upl {{ $field->getDefault() ? 'filled' : '' }}">
		<div class="esterisk-upl-mask">
			<label>{{ $field->placeholder }}</label>
			<input type="file" name="{{ $field->name }}{{ $field->multiple ? '[]' : '' }}"{{ $field->multiple ? ' multiple' : '' }} id="{{ $field->name }}">
		</div>
		<ul class="esterisk-upl-files list-group">
@if(is_array($field->getDefault()))

@elseif ($info = $field->getDefaultValueInfo())
			<li class="list-group-item d-flex justify-content-between align-items-center"><span><i class="esterisk-upl-icon {{ $info['icon'] }}"></i>{{ $info['name'] }}</span> <span class="esterisk-upl-size">{{ $info['size'] }}</span><input type="hidden" name="{{ $field->name }}[keep]" value="{{ $info['name'] }}"/></li>
@endif
		</ul>
		<button class="btn btn-outline-secondary btn-sm esterisk-upl-cancel">Svuota</button>
	</div>
	<template id="{{ $field->name }}-template"><li class="list-group-item d-flex justify-content-between align-items-center"><span><i class="esterisk-upl-icon [icon]"></i>[filename]</span> <span class="esterisk-upl-size">[size]</span></li></template>
@endsection
