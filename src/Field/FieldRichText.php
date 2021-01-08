<?php
namespace Esterisk\Form\Field;

class FieldRichText extends FieldTextarea
{
	var $rows = 5;
	var $fieldtype = 'textarea';
	var $template = 'textarea';
	var $emptyValue = '';
	var $configuration = 'simple';

	public function initScript()
	{
		return '$("#'.$this->name.'").richtext("'.$this->configuration.'");';
	}
	
	public function addConfiguration($name, $js) {
		$this->configurations[$name] = $js;
	}
	
	public function customConfiguration($js) {
		$this->configurations['custom'] = $js;
		$this->configuration('custom');
	}

}
