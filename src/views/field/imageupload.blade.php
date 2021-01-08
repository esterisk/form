@extends('esterisk.form.field.two-col-field')

@section('editfield-'.$field->name)
	<div class="esterisk-upl {{ $field->getDefault() ? 'filled' : '' }}">
		<div class="esterisk-upl-mask">
			<label>{{ $field->placeholder }}</label>
			<input type="file" name="{{ $field->name }}{{ $field->multiple ? '[]' : '' }}"{{ $field->multiple ? ' multiple' : '' }} id="{{ $field->name }}">
		</div>
		<ul class="esterisk-upl-files esterisk-upl-image list-group">
@if(is_array($field->getDefault()))

@elseif ($info = $field->getDefaultValueInfo())
			<li class="list-group-item justify-content-between align-items-center"><div class="clearfix"><figure><img src="{{ $info['url'] }}" alt="{{ $info['name'] }}"></figure><p>{{ $info['name'] }}</p><p><span class="esterisk-upl-size">{{ $info['size'] }}</span></p><p><span class="esterisk-upl-dim">{{ $info['dim'] }}</span></p><input type="hidden" name="{{ $field->name }}[keep]" value="{{ $info['name'] }}"/></li>
@endif
		</ul>
		<button class="btn btn-outline-secondary btn-sm esterisk-upl-cancel">Svuota</button>
	</div>
	<template id="{{ $field->name }}-template"><li class="list-group-item justify-content-between align-items-center" id="[id]"><div class="clearfix"><figure></figure><p>[filename]</p><p><span class="esterisk-upl-size">[size]</span></p><p><span class="esterisk-upl-dim"></span></p></li></div></template>
@endsection
