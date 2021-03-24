<?php
namespace Esterisk\Form\Field;

class ImageuploadField extends FileuploadField
{
	var $fieldtype = 'imageupload';
	var $template = 'imageupload';
	var $multiple = false;
	var $accept = [ 'image/jpeg','image/png','image/gif' ];
	var $maxsize = 0;
	var $repository;
	var $webdir;
	var $title = null;
	var $unique = false;
	var $file = null;
	var $reloadAfterSave = true;
	var $variant_style = null;

    public function avatar($variant = null)
    {
        $this->template = 'avatar';
        if (in_array($variant, [ 'logo' ])) $this->variant_style = $variant;
        return $this;
    }

    public function filePath($file)
    {
        return '/'.$this->repository.'/'.$file;
    }

	public function getFileInfo($file)
	{
	    if (!file_exists($this->filePath($file))) return false;
		$stat = stat($this->filePath($file));
		$size = getImageSize($this->filePath($file));
		$info = [
			'name' => $file,
			'path' => $this->filePath($file),
			'url' => $this->webdir ? $this->webdir.'/'.$file : false,
			'bytes' => $stat['size'],
			'mtime' => $stat['mtime'],
			'dim' => $size[0].'px &times; '.$size[1].'px',
		];
		switch (mime_content_type($this->filePath($file))) {
			case 'image/jpeg':
			case 'image/png':
			case 'image/gif':
			case 'image/jpg':
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
