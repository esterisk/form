/* Esterisk Form support JS - jquery version */

/* Utility */

jQuery.fn.extend({

    radioVal: function() {
        var n = $(this).attr('name');
        var v = $('input[type="radio"][name="'+n+'"]:checked').val();
        return (v == undefined ? null : v);
    },

    checkboxVal: function() {
        var n = $(this).attr('name');
        var v = $('input[type="checkbox"][name="'+n+'"]:checked').val();
        return (v == undefined ? null : v);
    },

});


/* dirty tracking */
var dirtyTimeouts = {};

jQuery.fn.extend({

	/* raccoglie i dati del form inclusi eventuali campi richtext per save via js */
	gatherData: function(action) {
		$(this).trigger('gather-data');
		var fdata = null;
		var sdata = $(this).serialize();

		if (window.FormData) {
			fdata = new FormData($(this)[0]);
		} else {
			console.log('browser not supporting FormData');
		}

		if (action == 'save') {
			$(this).data('formStatus', sdata);
			$(this).data('lastSave',(new Date()).getTime());
			$(this).dirtyFeedback('saved',0);
			return fdata;
		} else {
			return sdata;
		}
	},

	/* verifica se il contenuto del form è stato modificato */
	dirty: function() {
		var isDirty = $(this).gatherData('dirty') != $(this).data('formStatus');
		if (isDirty) {
			var seconds = Math.round( ((new Date()).getTime()-$(this).data('lastSave'))/1000 );
			$(this).dirtyFeedback('dirty',seconds);
		}
		return isDirty;
	},

	/* esegue il cron dei verifica delle modifiche */
	dirtyTimeout: function() {
		var id = '#'+$(this).attr('id');
		dirtyTimeouts[id] = setTimeout(function() { $(id).dirty(); $(id).dirtyTimeout(); }, 5000);
	},

	/* attiva partire la verifica delle modifiche  */
	startDirty: function() {
		$(this).stopDirty();
		$(this).gatherData('save');
		$(this).dirtyTimeout();
	},

	/* ferma partire la verifica delle modifiche  */
	stopDirty: function() {
		var id = '#'+$(this).attr('id');
		if (dirtyTimeouts[id]) clearTimeout( dirtyTimeouts[id] );
	},

	/* mostra nel campo _id lo stato delle modifiche  */
	dirtyFeedback: function(status, seconds) {
		if (status == 'saved') {
			$(this).find('span.dirty span.dirty-dirty').hide();
			$(this).find('span.dirty span.dirty-saved').show();
		}
		if (status == 'dirty') {
			$(this).find('span.dirty span.dirty-saved').hide();
			$(this).find('span.dirty span.dirty-dirty').show();
			if (seconds < 60) {
				$(this).find('span.dirty span.dirty-seconds span.dirty-time').text(seconds);
				$(this).find('span.dirty span.dirty-minutes').hide();
				$(this).find('span.dirty span.dirty-seconds').show();
			}
			if (seconds >= 60) {
				$(this).find('span.dirty span.dirty-minutes span.dirty-time').text(Math.round(seconds/60));
				$(this).find('span.dirty span.dirty-seconds').hide();
				$(this).find('span.dirty span.dirty-minutes').show();
			}
			if (seconds < 750) {
				$(this).find('span.dirty span.dirty-danger').hide();
				$(this).find('span.dirty span.dirty-warning').show();
			}
			if (seconds >= 750) {
				$(this).find('span.dirty span.dirty-warning').hide();
				$(this).find('span.dirty span.dirty-danger').show();
			}
		}
	},

	/* handleErrors mostra i campi che hanno problemi in un form */
	handleErrors: function(errors) {
		for (var field in errors) {
			$(this).find('[name="'+field+'"]').addClass('is-invalid');
			$(this).find('[name="'+field+'"]').parents('.form-group').find('.invalid-feedback').text(errors[field]);
		}
	}

});
$('form').has('span.dirty').startDirty();

/* Esterisk Uploader */



$(document).on('click','.esterisk-upl .esterisk-upl-cancel',function() {
	$uploader = $(this).parents('.esterisk-upl');
	$uploader.find('[type=file]').val(null);
	$uploader.removeClass('filled');
	$uploader.find('.esterisk-upl-files').html('');
    console.log('svuotato');
    console.log($uploader);
	return false;
});

$(document).on('click','.esterisk-upl .esterisk-upl-choose',function() {
	$uploader = $(this).parents('.esterisk-upl');
	$uploader.find('[type=file]').click();
	return false;
});

$(document).on('change', '.esterisk-upl [type=file]', function() {
	var fieldid = $(this).attr('id');
	var $uploader = $(this).parents('.esterisk-upl');
	files = $uploader.find('[type=file]')[0].files;
	if (files.length == 0) return;
	console.log(files);
	var accept = $uploader.find('[type=file]').attr('accept');
	var text = '';
	var template = $('#'+fieldid+'-template').html();
	var acceptable = true;
	if (accept.length > 0) {
        $.each(files, function (i, file) {
            if (accept.indexOf(file.type) == -1) acceptable = false;
        });
        if (!acceptable) {
    	    alert('Questo tipo di file non è accettato. Tipi accettati: "'+accept.replace(/,/g,'", "')+'"');
    	    $uploader.find('button.esterisk-upl-cancel').click();
    	    return false;
        }
    }
    $.each(files, function (i, file) {
    	var icon = 'file-icon';

		switch (file.type) {
			case 'image/jpeg':
			case 'image/png':
			case 'image/gif':
			case 'image/jpeg':
				icon = 'file-image-icon';
				break;
			case 'application/zip':
				icon = 'file-zip-icon';
				break;
			case 'application/pdf':
				icon = 'file-pdf-icon';
				break;
			case 'video/mp4':
			case 'video/quicktime':
				icon = 'file-video-icon';
				break;
			case 'audio/mpeg3':
			case 'audio/x-mpeg3':
				icon = 'file-music-icon';
				break;
			case 'application/vnd.ms-excel':
			case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
			case 'text/csv':
				icon = 'file-spreadsheet-icon';
				break;
			case 'text/plain':
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			case 'text/rtf':
			case 'application/msword':
			case 'application/vnd.oasis.opendocument.text':
				icon = 'file-text-icon';
				break;
			default:
				icon = 'file-icon';
				break;
		}

		var size = 0;
		if (file.size < 1024) size = file.size + ' B';
		else if (file.size < 1048576) size = Math.round(file.size/1024,0) + ' KB';
		else size = Math.round(file.size/1048576,2) + ' MB';
		var liid = Date.now()+'-'+i;
		text = text + template.replace('[icon]', icon).replace('[filename]', file.name).replace('[size]', size).replace('[id]', liid);
		console.log(text);

		if (text.match(/figure/)) {
			oFReader = new FileReader();
			oFReader.onload = function (oFREvent) {
				var dataURI = oFREvent.target.result;
				if ($('#'+liid+' figure img').lentgh > 0) return;
				image = document.createElement("img");
				image.src = dataURI;
				$('#'+liid+' figure').append(image);
				$image = $('#'+liid+' figure img');
				$('#'+liid+' .esterisk-upl-dim').text(Math.round(image.width)+'px &times;x '+Math.round(image.height)+'px');
		console.log($image.width());
		console.log($image.height());
			};
			oFReader.readAsDataURL(file);
		}
    });
	$uploader.find('.esterisk-upl-files').html(text);
	$uploader.addClass('filled');
    console.log('riempito');
    console.log($uploader);
});

/* Esterisk Multiple */

$(function() {
	$(document).on('click','.multiple-add', function() {
		var maxnum = $(this).data('max-num');
		$row = $(this).parents('.multiple-block').find('template.multiple-row').html();
		$row = $row.replace(/\{\}/g,'{'+maxnum+'}');
		$(this).parents('.multiple-block').find('.multiple-rows').append($row);
		$(this).parents('.multiple-block').find('.form-row[data-row-number="{'+maxnum+'}"]').slideDown();
		$(this).parents('.multiple-block').find('.form-row.form-row-labels').slideDown();
		$(this).data('max-num', maxnum + 1);
		return false;
	});

	$(document).on('click','.multiple-del-row', function() {
		var row = $(this).data('row');
		if (confirm('Sei sicuro di voler cancellare questa riga? L’operazione non è annullabile.')) {
			$(this).parents('.multiple-block').find('.form-row[data-row-number="'+row+'"]').slideUp(function() {
				$(this).parents('.multiple-block').find('.form-row[data-row-number="'+row+'"]').remove();
			});
		}
		return false;
	});
/*
	$('[data-row-number]').forEach( function() {
	    var html = $(this).html();
	    var number = $(this).data('row-number');
	    html = html.replace('{}',number);
	    $(this).html(html);
	    console.log(number,html);
	});
*/
});

/* Esterisk Select onOther */

$(function() {
	$(document).on('change','[data-onother]',function() {
		$otherDiv = $(this).parents('.select-group').find('.select-other');
		$otherInput = $(this).parents('.select-group').find('.select-other input');

		if ($(this).val() == $(this).data('onother')) {
			console.log('other');
			$otherDiv.slideDown();
			$otherInput.focus();
		} else {
			console.log('non other');
			$otherInput.blur();
			$otherDiv.slideUp();
			$otherInput.val('');
		}
	});
});

/* Esterisk Unit menu */

$(function() {

    $(document).on('click', '[data-unit]', function() {
        var $menu = $(this).parents('.unit-menu');
        var unit = $(this).data('unit');
        $menu.find('button').text(unit);
        $menu.find('input[name="'+$(this).data('unit-field')+'"]').val(unit);
        $('.dropdown-toggle').dropdown('hide')
        return false;
    });

});


/* Esterisk Conditional Block */

function checkAllConditions() {
	$('[data-condition-trigger]').each( function() {
		$(this).checkCondition();
	});
}

function activateCondition() {
	if ($('[data-condition-trigger]').length > 0) {
		$('input[type=checkbox]').click( function() { checkAllConditions(); } );
		$('input[type=radio]').click( function() { checkAllConditions(); } );
		$('select').change( function() { checkAllConditions(); } );
	}
}

$(function() {
	checkAllConditions();
	activateCondition();
});

jQuery.fn.extend({

	checkCondition: function() {
		var fields = $(this).data('condition-trigger');
		var opened = false;
		var triggers = new Array;
		var names = new Array;
		var values = new Array;
		var types = [ 'select', 'checkbox', 'radio' ];
		found = fields.match(/(.*)=(.+)/);

		if (types.includes(found[1])) types = [ found[1] ];
		else if (found[1] != '') names = found[1].split('|');
		values = found[2].split('|');

		for (var v=0; v<values.length; v++) {
			if (names.length) for (var n=0; n<names.length; n++) {
				if (types.includes('checkbox')) triggers.push('input[type=checkbox][name="'+names[n]+'"][value="'+values[v]+'"]:checked');
				if (types.includes('radio')) triggers.push('input[type=radio][name="'+names[n]+'"][value="'+values[v]+'"]:checked');
				if (types.includes('select')) triggers.push('select[name="'+names[n]+'"] option[value="'+values[v]+'"]:selected');
			} else {
				if (types.includes('checkbox')) triggers.push('input[type=checkbox][value="'+values[v]+'"]:checked');
				if (types.includes('radio')) triggers.push('input[type=radio][value="'+values[v]+'"]:checked');
				if (types.includes('select')) triggers.push('select option[value="'+values[v]+'"]:selected');
			}
		}

		for (var t=0; t<triggers.length; t++) {
			if ($(this).parents('form').find(triggers[t]).length > 0) {
				opened = true;
			}
		}
		if (opened) $(this).slideDown(); else $(this).slideUp();
	},

});

/* Esterisk Conditional Select */

function pushConditions(trigged, trigger, optionSets) {
    console.log(trigged, trigger);
	if (window.conditionalOptions == undefined) window.conditionalOptions = {};
	if (window.conditionalOptions[trigger] == undefined) {
		window.conditionalOptions[trigger] = {};
		$('select[name="'+trigger+'"]').change( 				function() { setConditionalSelects(trigger,$(this).val()); });
		$('input[type=checkbox][name="'+trigger+'"]').click( 	function() { setConditionalSelects(trigger,$(this).checkboxVal()); });
		$('input[type=radio][name="'+trigger+'"]').click( 		function() { setConditionalSelects(trigger,$(this).radioVal()); });
	}
	window.conditionalOptions[trigger][trigged] = optionSets;
	$(function() {
    	if ($('select[name="'+trigger+'"]').length) 				setConditionalSelects(trigger,$('[name="'+trigger+'"]').val());
	    if ($('input[type=radio][name="'+trigger+'"]').length) 		setConditionalSelects(trigger,$('[name="'+trigger+'"]').radioVal());
    	if ($('input[type=checkbox][name="'+trigger+'"]').length) 	setConditionalSelects(trigger,$('[name="'+trigger+'"]').checkboxVal());
    });
}

function setConditionalSelects(name,value)
{
	var condition = conditionalOptions[name];
	if (condition) {
		for (var s in condition) {
			var options = condition[s];
			if (!value || !options[value]) {
				$('[name="'+s+'"]').attr('disabled','disabled');
			} else {
				$('[name="'+s+'"]').attr('disabled',false);
				var currentVal = $('[name="'+s+'"]').val();
				if (!currentVal) currentVal = $('[name="'+s+'"]').data('default');
				$('[name="'+s+'"]').children('option').remove();
				$.each(options[value], function(key, value) {
    				$('[name="'+s+'"]').append($('<option>', { value : key }).text(value));
				});
				$('[name="'+s+'"]').val(currentVal);
			}
		}
	}
}

/* Integer Range */

$(function() {

    $('[type=range][data-integer]').change( function() {
        $('#'+$(this).data('integer')).val( $(this).val() );
    });

    $('[data-range]').change( function() {
        $('[type=range][data-integer='+$(this).attr('id')+']').val( $(this).val() );
    });

});
