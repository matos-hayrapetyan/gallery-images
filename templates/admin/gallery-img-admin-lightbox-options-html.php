<script>
	jQuery(document).ready(function () {
		popupsizes(jQuery('#light_box_size_fix'));
		function popupsizes(checkbox){
			if(checkbox.is(':checked')){
				jQuery('.lightbox-options-block .not-fixed-size').css({'display':'none'});
				jQuery('.lightbox-options-block .fixed-size').css({'display':'block'});
			}else {
				jQuery('.lightbox-options-block .fixed-size').css({'display':'none'});
				jQuery('.lightbox-options-block .not-fixed-size').css({'display':'block'});
			}
		}
		jQuery('#light_box_size_fix').change(function(){
			popupsizes(jQuery(this));
		});


		jQuery('input[data-slider="true"]').bind("slider:changed", function (event, data) {
			jQuery(this).parent().find('span').html(parseInt(data.value)+"%");
			jQuery(this).val(parseInt(data.value));
		});
	});
</script>
<div class="wrap">
	<?php require(GALLERY_IMG_TEMPLATES_PATH.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'gallery-img-admin-free-banner.php');?>
	<div style="clear:both;"></div>
	<img src="<?php echo GALLERY_IMG_IMAGES_URL.'/admin_images/lightbox_opt.png'; ?>" style="width: 100%;">
</div>