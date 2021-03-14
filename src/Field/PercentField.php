<?php
namespace Esterisk\Form\Field;

class PercentField extends RealField
{
	var $max = 100;
	var $min = 0;
	var $prepend = '%';
}
