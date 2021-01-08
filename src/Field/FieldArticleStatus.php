<?php
namespace Esterisk\Form\Field;

class FieldArticleStatus extends FieldSelect
{
	var $default = 'progress';
	var $label = 'Stato';
	var $statuses = [ 
		'undefined' 	=> '', /* non definito */
		'stub' 			=> 'STUB', /* appunto per articolo */
		'progress' 		=> 'PROG', /* in lavorazione */
		'draft' 		=> 'DRAF', /* first draft, journalist still have to work */
		'submitted' 	=> 'SUBM', /* journalist done, ready to be reviewd by editor */
		'ready' 		=> 'REDY', /* reviewed by editor, ready to be published */
		'hold' 			=> 'HOLD', /* reviewed but not ready for publication */
		'published' 	=> 'PUBL', /* published */
		'archived'		=> 'ARCH', /* a published article not anymore visible in indexes but still searchable */
		'private' 		=> 'PRIV',  /* published but not visible in indexes and search */
		'retired' 		=> 'RETD' /* a published article withdrawn from publication */
	];
	var $labels = [ 
		'undefined' 	=> '', /* non definito */
		'stub' 			=> 'Appunto', /* appunto per articolo */
		'progress' 		=> 'Lavorazione', /* in lavorazione */
		'draft' 		=> 'Bozza', /* first draft, journalist still have to work */
		'submitted' 	=> 'Da rivedere', /* journalist done, ready to be reviewd by editor */
		'ready' 		=> 'Pronto', /* reviewed by editor, ready to be published */
		'hold' 			=> 'Sospeso', /* reviewed but not ready for publication */
		'published' 	=> 'Pubblicato', /* published */
		'archived'		=> 'Archiviato', /* a published article not anymore visible in indexes but still searchable */
		'private' 		=> 'Privato',  /* published but not visible in indexes and search */
		'retired' 		=> 'Ritirato' /* a published article withdrawn from publication */
	];
	var $colors = [ 
		'undefined' 	=> '', /* non definito */
		'stub' 			=> '#999', /* appunto per articolo */
		'progress' 		=> '#666', /* in lavorazione */
		'draft' 		=> '#033', /* first draft, journalist still have to work */
		'submitted' 	=> '#090', /* journalist done, ready to be reviewd by editor */
		'ready' 		=> '#060', /* reviewed by editor, ready to be published */
		'hold' 			=> '#c00', /* reviewed but not ready for publication */
		'published' 	=> '#009', /* published */
		'archived'		=> '#006', /* a published article not anymore visible in indexes but still searchable */
		'private' 		=> '#00F',  /* published but not visible in indexes and search */
		'retired' 		=> '#F00' /* a published article withdrawn from publication */
	];
	var $modifiers = [
		'undefined' => [ 'back' => [], 'forward' => [ 'progress' ] ], 
		'stub' => [ 'back' => [], 'forward' => [ 'progress' ] ], 
		'progress' => [ 'back' => [], 'forward' => [ 'draft','submitted','ready' ] ],
		'draft' => [ 'back' => [ 'progress' ], 'forward' => [ 'submitted','ready' ] ],
		'submitted' => [ 'back' => [ 'progress','draft' ], 'forward' => [ 'ready','hold' ] ],
		'ready' => [ 'back' => [ 'progress','draft','hold' ], 'forward' => [ 'published' ] ],
		'hold' => [ 'back' => [ 'draft','progress' ], 'forward' => [ 'ready' ] ],
		'published' => [ 'back' => [ 'ready','archived','retired' ], 'forward' => [] ],
		'archived' => [ 'back' => [ 'retired' ], 'forward' => ['published'] ],
		'private' => [ 'back' => [ 'ready' ], 'forward' => ['published'] ],
		'retired' => [ 'back' => [ 'ready' ], 'forward' => ['published'] ],
	];
	var $options = [];
	var $command = false;
	
	public function withValues($statuses) 
	{
		$this->statuses = $statuses;
		$this->addOptions( array_intersect_key($this->labels,$this->statuses) );
		$this->modifiers = array_intersect_key($this->modifiers,$this->statuses);
		foreach ($this->modifiers as $key => $dummy) {
			$valid = array_intersect($this->modifiers[$key]['back'],array_keys($this->statuses));
			$this->modifiers[$key]['back'] = array_shift($valid);
			$valid = array_intersect($this->modifiers[$key]['forward'],array_keys($this->statuses));
			$this->modifiers[$key]['forward'] = array_shift($valid);
		}
		return $this;
	}
	
	public function prepareForSave($value) 
	{	
		return $this->statuses[$value];
	}

	public function show($value)
	{
		$status = array_search($value, $this->statuses);

		$tools = '';
		if ($this->form->resource && $this->record && $this->command) {
			if ($back = $this->modifiers[$status]['back']) $tools .= '<a href="'.$this->form->resource->commandRoute($this->command, $this->record->getKey()).'?'.$this->name.'='.$back.'" class="btn btn-sm btn-outline-secondary" data-command-method="post" data-command-url="'.$this->form->resource->commandRoute($this->command, $this->record->getKey()).'?'.$this->name.'='.$back.'" data-command-title="'.$this->labels[$back].'">'.$this->labels[$back].'</a> ';
			if ($forward = $this->modifiers[$status]['forward']) $tools .= '<a href="'.$this->form->resource->commandRoute($this->command, $this->record->getKey()).'?'.$this->name.'='.$forward.'" class="btn btn-sm btn-success" data-command-method="post" data-command-url="'.$this->form->resource->commandRoute($this->command, $this->record->getKey()).'?'.$this->name.'='.$forward.'" data-command-title="'.$this->labels[$forward].'">'.$this->labels[$forward].'</a>';
			if ($tools) $tools = '<div class="form-list-tools-block">'.$tools.'</div>';
		}
	
		return '<span style="color:'.(isset($this->colors[$status]) ? $this->colors[$status] : '#eee').'">'.
			(isset($this->labels[$status]) ? $this->labels[$status] : ucfirst($status)).
			'</span>'.$tools;
	}

}
