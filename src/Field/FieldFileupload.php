<?php
namespace Esterisk\Form\Field;

class FieldFileupload extends Field
{
	var $fieldtype = 'fileupload';
	var $template = 'fileupload';
	var $multiple = false;
	var $accepts = false;
	var $maxsize = 0;
	var $repository;
	var $webdir;
	var $title = null;
	var $unique = false;
	var $file = null;
	var $reloadAfterSave = true;

	public function prepareForSave($value) 
	{
		$name = $this->name;
		if (($keep = $this->keepValid($value)) !== false) return $keep;
		else return '';
	}

	public function afterSave($request, $record)
	{
		$name = $this->name;
		$this->file = $request->$name;
		if (($keep = $this->keepValid($this->file)) !== false) return $keep;
		if (!$this->file) return '';
		$this->record = $record;
		
		$title = $this->applyTitle();
		$this->applyExtension($title);
		if ($this->unique) $title = $this->applyUnique($title);
		
		$request->$name->move('/'.$this->repository, $title);
		return $title;
	}
	
	public function keepValid($value)
	{
		$name = $this->name;
		if (is_array($value) && isset($value['keep'])) {
			if (!$this->form->original || $this->form->original->$name == $value['keep']) return $value['keep'];
			else return '';
		} else return false;
	}

	public function applyTitle()
	{
		if (empty($this->title)) return $this->applyDefautlTitle();
		if (gettype($this->title) == 'string') return $this->applyStringTitle();
		if (gettype($this->title) == 'object' && get_class($this->title) == 'Closure') return $this->applyClosureTitle();
		if (gettype($this->title) == 'object') return $this->applyObjectTitle();
		return $this->applyDefautlTitle();
	}
	
	public function applyDefautlTitle()
	{
		return $this->file->getClientOriginalName();
	}
	
	public function applyStringTitle()
	{
		$title = preg_replace_callback('|\{([^\}]+)\}|', function($matches) {
			$field = $matches[1];
			if ($matches[1] == '_id') return $this->record->getKey();
			return isset($this->record->$field) ? $this->record->$field : '';
		}, $this->title);
		return $title;
	}
	
	public function applyClosureTitle()
	{
		$closure = $this->title;
		return $closure($this->file, $this->record);
	}
	
	public function applyObjectTitle()
	{
		return $this->title->fileTitle($this->file, $this->record);
	}
	
	public function applyExtension(&$title)
	{
		$ext = str_replace('jpeg','jpg',$this->file->extension());
		if (!preg_match('/\.'.$ext.'$/', $title)) $title = $title.'.'.$ext;
	}
	
	public function applyUnique(&$title)
	{
		$original_title = $title;
		$counter = 0;
		while (file_exists($this->repository.'/'.$title)) {
			$title = preg_replace('|\.([^\.]+)$|', '_'.(++$counter).'$1', $original_title);
		}
	}
	
	public function getDefaultValueInfo()
	{
		$value = $this->getDefault();
		if ($value) {
			if ($this->multiple) {
		
			} else {
				return $this->getFileInfo($value);
			}
		} else return null;
	}

	public function getFileInfo($file)
	{
		$stat = stat('/'.$this->repository.'/'.$file);
		$info = [
			'name' => $file,
			'path' => '/'.$this->repository.'/'.$file,
			'url' => $this->webdir ? $this->webdir.'/'.$file : false,
			'bytes' => $stat['size'],
			'mtime' => $stat['mtime'],
		];
		switch (mime_content_type('/'.$this->repository.'/'.$file)) {
			case 'image/jpeg':
			case 'image/png':
			case 'image/gif':
			case 'image/jpeg':
				$info['icon'] = 'file-image-icon';
				break;
			case 'application/zip':
				$info['icon'] = 'file-zip-icon';
				break;
			case 'application/pdf':
				$info['icon'] = 'file-pdf-icon';
				break;
			case 'video/mp4':
			case 'video/quicktime':
				$info['icon'] = 'file-video-icon';
				break;
			case 'audio/mpeg3':
			case 'audio/x-mpeg3':
				$info['icon'] = 'file-music-icon';
				break;
			case 'application/vnd.ms-excel':
			case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
			case 'text/csv':
				$info['icon'] = 'file-spreadsheet-icon';
				break;
			case 'text/plain':
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			case 'text/rtf':
			case 'application/msword':
			case 'application/vnd.oasis.opendocument.text':
				$info['icon'] = 'file-text-icon';
				break;
			default: 
				$info['icon'] = 'file-icon';
				break;
		}
		$info['size'] = 0;
		if ($stat['size'] < 1024) $info['size'] = $stat['size'] . ' B';
		else if ($stat['size'] < 1048576) $info['size'] = round($stat['size']/1024) . ' KB';
		else $info['size'] = round($stat['size']/1048576,2) . ' MB';
		return $info;
	}


}
