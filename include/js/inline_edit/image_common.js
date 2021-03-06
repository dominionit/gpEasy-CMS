
	/**
	 * Folder drop down
	 *
	 */
	$('body').click(function(evt){

		var $area = false;
		if( evt.target ){
			$area = $(evt.target).closest('.gp_edit_select');
		}
		if( !$area.length ){
			CloseFolder();
			return;
		}

		var $options = $area.find('.gp_edit_select_options');
		if( !$options.is(':visible') ){
			$options.slideDown(500,function(){
				$('#gp_option_area').addClass('gp_active');
			});
			$('#gp_gallery_avail_imgs').animate({'height':80},500);
			return;
		}

		//close if the target was in the selector
		var $control = $(evt.target).closest('.gp_selected_folder');
		if( $control.length ){
			CloseFolder();
		}
	});

	function CloseFolder(){
		if( !$('.gp_active').length ) return;
		$('#gp_gallery_avail_imgs').animate({'height':220},500);
		$('.gp_edit_select_options').slideUp(500,function(){
			$('#gp_option_area').removeClass('gp_active');
		});

	}

	gplinks.gp_gallery_folder = function(rel,evt){
		evt.preventDefault();
		LoadImages(rel);
	}

	function LoadImages(directory){
		var edit_path = strip_from(gp_editor.save_path,'?');
		if( directory ){
			edit_path += '?cmd=gallery_folder&dir='+encodeURIComponent(directory);
		}else{
			edit_path += '?cmd=gallery_images';
		}
		$gp.jGoTo(edit_path);
	}



	/**
	 * Remove an image from the list of available images
	 *
	 */
	gpresponse.img_deleted_id = function(){
		$('#'+this.CONTENT).remove();
	}

