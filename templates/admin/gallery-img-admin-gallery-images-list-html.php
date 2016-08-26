<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if (isset($_REQUEST['huge_it_gallery_nonce'])) {
    $wp_nonce = $_REQUEST['huge_it_gallery_nonce'];
    if (!wp_verify_nonce($wp_nonce, 'huge_it_gallery_nonce')) {
        wp_die('Security check fail');
    }
}
global $wpdb;
$gallery_wp_nonce = wp_create_nonce('huge_it_gallery_nonce');
if (isset($_GET['id']) && $_GET['id'] != '') {
	$id = intval($_GET['id']);
}

if(isset($_GET["addslide"])){
	if($_GET["addslide"] == 1){
		header('Location: admin.php?page=galleries_huge_it_gallery&id='.$row->id.'&task=apply');
	}
}


?>
<script type="text/javascript">
	function submitbutton(pressbutton)
	{
		if(!document.getElementById('name').value){
			alert("Name is required.");
			return;

		}
		filterInputs();
		document.getElementById("adminForm").action=document.getElementById("adminForm").action+"&task="+pressbutton;
		document.getElementById("adminForm").submit();

	}
	var  name_changeRight = function(e) {
		document.getElementById("name").value = e.value;
	}
	var  name_changeTop = function(e) {
		document.getElementById("huge_it_gallery_name").value = e.value;
		//alert(e);
	};

	function change_select()
	{
		submitbutton('apply');

	}

	/*** creating array of changed projects***/

	function filterInputs() {

		var mainInputs = "";

		jQuery("#images-list > li.highlights").each(function(){
			jQuery(this).next().addClass('submit-post');
			jQuery(this).prev().addClass('submit-post');
			jQuery(this).addClass('submit-post');
			jQuery(this).removeClass('highlights');
		})

		if(jQuery("#images-list > li.submit-post").length) {

			jQuery("#images-list > li.submit-post").each(function(){

				var inputs = jQuery(this).find('.order_by').attr("name");
				var n = inputs.lastIndexOf('_');
				var res = inputs.substring(n+1, inputs.length);
				res +=',';
				mainInputs += res;

			});

			mainInputs = mainInputs.substring(0,mainInputs.length-1);


			jQuery(".changedvalues").val(mainInputs);

			jQuery("#images-list > li").not('.submit-post').each(function(){
				jQuery(this).find('input').removeAttr('name');
				jQuery(this).find('textarea').removeAttr('name');
			});
			console.log(mainInputs);
			return mainInputs;

		}
		jQuery("#images-list > li").each(function(){
			jQuery(this).find('input').removeAttr('name');
			jQuery(this).find('textarea').removeAttr('name');
			jQuery(this).find('select').removeAttr('name');
		});
	}
	/***</add>***/
	jQuery(function() {
		/*** <posted only submit classes> ***/

		jQuery( "#images-list > li input" ).on('keyup',function(){
			jQuery(this).parents("#images-list > li").addClass('submit-post');
		});
		jQuery( "#images-list > li textarea" ).on('keyup',function(){
			jQuery(this).parents("#images-list > li").addClass('submit-post');
		});
		jQuery( "#images-list > li input" ).on('change',function(){
			jQuery(this).parents("#images-list > li").addClass('submit-post');
		});
		jQuery('.editimageicon').click(function(){
			jQuery(this).parents("#images-list > li").addClass('submit-post');
		})
		/*** </posted only submit classes> ***/

		jQuery( "#images-list" ).sortable({
			stop: function() {
				jQuery("#images-list > li").removeClass('has-background');
				count=jQuery("#images-list > li").length;
				for(var i=0;i<=count;i+=2){
					jQuery("#images-list > li").eq(i).addClass("has-background");
				}
				jQuery("#images-list > li").each(function(){
					jQuery(this).find('.order_by').val(jQuery(this).index());
				});
			},
			change: function(event, ui) {
				var start_pos = ui.item.data('start_pos');
				var index = ui.placeholder.index();
				if (start_pos < index) {
					jQuery('#images-list > li:nth-child(' + index + ')').addClass('highlights');
				} else {
					jQuery('#images-list > li:eq(' + (index + 1) + ')').addClass('highlights');
				}
			},
			update: function(event, ui) {
				jQuery('#sortable li').removeClass('highlights');
			},
			revert: true
		});
	});
</script>

<!-- GENERAL PAGE, ADD IMAGES PAGE -->

<div class="wrap">
	<?php require(GALLERY_IMG_TEMPLATES_PATH.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'gallery-img-admin-free-banner.php');?>
	<?php $path_site = plugins_url("../images", __FILE__); ?>
	<div style="clear: both;"></div>
	<form action="admin.php?page=galleries_huge_it_gallery&id=<?php echo $row->id; ?>" method="post" name="adminForm" id="adminForm">
		<input type="hidden" class="changedvalues" value="" name="changedvalues" size="80">

		<div id="poststuff" >
			<div id="gallery-header">
				<ul id="gallerys-list">

					<?php
					foreach($rowsld as $rowsldires){
						if($rowsldires->id != $row->id){
							?>
							<li>
								<a href="#" onclick="window.location.href='admin.php?page=galleries_huge_it_gallery&task=edit_cat&id=<?php echo $rowsldires->id; ?>&huge_it_gallery_nonce=<?php echo $gallery_wp_nonce; ?>'" ><?php echo $rowsldires->name; ?></a>
							</li>
							<?php
						}
						else{ ?>
							<li class="active" onclick="this.firstElementChild.style.width = ((this.firstElementChild.value.length + 1) * 8) + 'px';" style="background-image:url(<?php echo GALLERY_IMG_IMAGES_URL.'/admin_images/edit.png' ;?>);cursor: pointer;">
								<input class="text_area" onkeyup = "name_changeTop(this)" onfocus="this.style.width = ((this.value.length + 1) * 8) + 'px'" type="text" name="name" id="name" maxlength="250" value="<?php echo esc_html(stripslashes($row->name));?>" />
							</li>
							<?php
						}
					}
					?>
					<li class="add-new">
						<a onclick="window.location.href='admin.php?page=galleries_huge_it_gallery&amp;task=add_cat&huge_it_gallery_nonce=<?php echo $gallery_wp_nonce;?>'">+</a>
					</li>
				</ul>
			</div>
			<div id="post-body" class="metabox-holder columns-2">
				<!-- Content -->
				<div id="post-body-content">


					<?php add_thickbox(); ?>

					<div id="post-body">
						<div id="post-body-heading">
							<h3><?php echo __('Images/Videos', 'gallery-images'); ?></h3>



							<script>
								jQuery(document).ready(function($){
									jQuery(".wp-media-buttons-icon").click(function() {
										jQuery(".attachment-filters").css("display","none");
									});
									var _custom_media = true,
										_orig_send_attachment = wp.media.editor.send.attachment;


									jQuery('.huge-it-newuploader .button').click(function(e) {
										var send_attachment_bkp = wp.media.editor.send.attachment;

										var button = jQuery(this);
										var id = button.attr('id').replace('_button', '');
										_custom_media = true;

										jQuery("#"+id).val('');
										wp.media.editor.send.attachment = function(props, attachment){
											if ( _custom_media ) {
												jQuery("#"+id).val(attachment.url+';;;'+jQuery("#"+id).val());
												jQuery("#save-buttom").click();
											} else {
												return _orig_send_attachment.apply( this, [props, attachment] );
											};
										}

										wp.media.editor.open(button);

										return false;
									});

									jQuery('.add_media').on('click', function(){
										_custom_media = false;

									});
									jQuery(".wp-media-buttons-icon").click(function() {
										jQuery(".media-menu .media-menu-item").css("display","none");
										jQuery(".media-menu-item:first").css("display","block");
										jQuery(".separator").next().css("display","none");
										jQuery('.attachment-filters').val('image').trigger('change');
										jQuery(".attachment-filters").css("display","none");
									});
								});
							</script>
							<input type="hidden" name="imagess" id="_unique_name" />
							<span class="wp-media-buttons-icon"></span>
							<div class="huge-it-newuploader uploader button button-primary add-new-image">
								<input type="button" class="button wp-media-buttons-icon" name="_unique_name_button" id="_unique_name_button" value="Add Image" />
							</div>

							<a href="admin.php?page=galleries_huge_it_gallery&task=gallery_video&id=<?php echo $id; ?>&huge_it_gallery_nonce=<?php echo $gallery_wp_nonce; ?>&TB_iframe=1" class="button button-primary add-video-slide thickbox"  id="slideup3s" value="iframepop">
								<span class="wp-media-buttons-icon"></span><?php echo __('Add Video', 'gallery-images'); ?>
							</a>




						</div>
						<ul id="images-list">
							<?php

							function get_youtube_id_from_url($url){
								if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
									return $match[1];
								}
							}

							$i=2;
							foreach ($rowim as $key=>$rowimages){ ?>
								<?php if($rowimages->sl_type == ''){$rowimages->sl_type = 'image';}
								switch($rowimages->sl_type){
									case 'image':	?>
										<li <?php if($i%2==0){echo "class='has-background'";}$i++; ?>>
											<input class="order_by" type="hidden" name="order_by_<?php echo $rowimages->id; ?>" value="<?php echo $rowimages->ordering; ?>" />
											<div class="image-container">
												<img src="<?php echo $rowimages->image_url; ?>" />
												<div>
													<script>
														jQuery(document).ready(function($){
															var _custom_media = true,
																_orig_send_attachment = wp.media.editor.send.attachment;

															jQuery('.huge-it-editnewuploader .button<?php echo $rowimages->id; ?>').click(function(e) {
																var send_attachment_bkp = wp.media.editor.send.attachment;
																var button = jQuery(this);
																var id = button.attr('id').replace('_button', '');
																_custom_media = true;
																wp.media.editor.send.attachment = function(props, attachment){
																	if ( _custom_media ) {
																		jQuery("#"+id).val(attachment.url);
																		jQuery("#save-buttom").click();
																	} else {
																		return _orig_send_attachment.apply( this, [props, attachment] );
																	};
																}

																wp.media.editor.open(button);
																return false;
															});

															jQuery('.add_media').on('click', function(){
																_custom_media = false;
															});
															jQuery(".huge-it-editnewuploader").click(function() {
															});
															jQuery(".wp-media-buttons-icon").click(function() {
																jQuery(".wp-media-buttons-icon").click(function() {
																	jQuery(".media-menu .media-menu-item").css("display","none");
																	jQuery(".media-menu-item:first").css("display","block");
																	jQuery(".separator").next().css("display","none");
																	jQuery('.attachment-filters').val('image').trigger('change');
																	jQuery(".attachment-filters").css("display","none");

																});
															});
															if(jQuery('#rating').val()=='off'){
																jQuery('.like_dislike_wrapper').css('display','none');
																jQuery('.heart_wrapper').css('display','none');
															}else if (jQuery('#rating').val()=='dislike'){
																jQuery('.like_dislike_wrapper').css('display','block');
																jQuery('.heart_wrapper').css('display','none');
																jQuery('.heart_wrapper').find('input').removeAttr('name');
															}else if (jQuery('#rating').val()=='heart'){
																jQuery('.heart_wrapper').css('display','block');


															}


															jQuery('#rating').on('change',function(){
																if(jQuery(this).val()=='off'){
																	jQuery('.like_dislike_wrapper').css('display','none');
																	jQuery('.heart_wrapper').css('display','none');
																}else if (jQuery(this).val()=='dislike'){
																	jQuery('.like_dislike_wrapper').css('display','block');
																	jQuery('.heart_wrapper').css('display','none');
																	jQuery('.heart_wrapper').find('input').removeAttr('name');
																}else if (jQuery(this).val()=='heart'){
																	jQuery('.heart_wrapper').css('display','block');
																	jQuery('.like_dislike_wrapper').css('display','none');
																	jQuery('.heart_wrapper').each(function(){
																		var num=jQuery(this).find('input').attr('num');
																		jQuery(this).find('input').attr('name','like_'+num);
																	})

																}

															})
														});
														function deleteproject<?php echo $rowimages->id; ?>() {
															jQuery('#adminForm').attr('action', 'admin.php?page=galleries_huge_it_gallery&task=edit_cat&id=<?php echo $row->id; ?>&removeslide=<?php echo $rowimages->id; ?>');
														}

													</script>
													<input type="hidden" name="imagess<?php echo $rowimages->id; ?>" id="_unique_name<?php echo $rowimages->id; ?>" value="<?php echo esc_attr($rowimages->image_url); ?>" />
													<span class="wp-media-buttons-icon"></span>
													<div class="huge-it-editnewuploader uploader button<?php echo $rowimages->id; ?> add-new-image">
														<input type="button" class="button<?php echo $rowimages->id; ?> wp-media-buttons-icon editimageicon" name="_unique_name_button<?php echo $rowimages->id; ?>" id="_unique_name_button<?php echo $rowimages->id; ?>" value="Edit image" />
													</div>
												</div>
											</div>
											<div class="image-options">
												<div>
													<label for="titleimage<?php echo $rowimages->id; ?>"><?php echo __('Title:', 'gallery-images'); ?></label>
													<input  class="text_area" type="text" id="titleimage<?php echo $rowimages->id; ?>" name="titleimage<?php echo $rowimages->id; ?>" id="titleimage<?php echo $rowimages->id; ?>"  value="<?php echo esc_attr(str_replace('__5_5_5__','%',$rowimages->name)); ?>">
												</div>
												<div class="description-block">
													<label for="im_description<?php echo $rowimages->id; ?>"><?php echo __('Description:', 'gallery-images'); ?></label>
													<textarea id="im_description<?php echo $rowimages->id; ?>" name="im_description<?php echo $rowimages->id; ?>" ><?php echo str_replace('__5_5_5__','%',$rowimages->description) ?></textarea>
												</div>
												<div class="link-block">
													<label for="sl_url<?php echo $rowimages->id; ?>"><?php echo __('URL:', 'gallery-images'); ?></label>
													<input class="text_area url-input" type="text" id="sl_url<?php echo $rowimages->id; ?>" name="sl_url<?php echo $rowimages->id; ?>"  value="<?php echo esc_attr(str_replace('__5_5_5__','%',$rowimages->sl_url)); ?>" >
													<label class="long" for="sl_link_target<?php echo $rowimages->id; ?>">
														<span><?php echo __('Open in new tab', 'gallery-images'); ?></span>
														<input type="hidden" name="sl_link_target<?php echo $rowimages->id; ?>" value="" />
														<input  <?php if($rowimages->link_target == 'on'){ echo 'checked="checked"'; } ?>  class="link_target" type="checkbox" id="sl_link_target<?php echo $rowimages->id; ?>" name="sl_link_target<?php echo $rowimages->id; ?>" />
													</label>



												</div>
												<div class="remove-image-container">
													<a onclick="deleteproject<?php echo $rowimages->id; ?>(); submitbutton('apply');" id="remove_image<?php echo $rowimages->id; ?>" class="button remove-image">Remove Image</a>
												</div>
												<div class="like_dislike_wrapper">
													<label for="like_<?php echo $rowimages->id; ?>"><?php echo __('Ratings:', 'gallery-images'); ?></label>
													<label for="like_<?php echo $rowimages->id; ?>" class="like"><?php echo __('Like', 'gallery-images'); ?></label>
													<input  class="" type="number" id="like_<?php echo $rowimages->id; ?>" name="like_<?php echo $rowimages->id; ?>" value="<?php echo str_replace('__5_5_5__','%',$rowimages->like); ?>">
													<label for="dislike_<?php echo $rowimages->id; ?>" class="dislike"><?php echo __('Dislike', 'gallery-images'); ?></label>
													<input  class="" num="<?php echo $rowimages->id; ?>" type="number" id="dislike_<?php echo $rowimages->id; ?>" name="dislike_<?php echo $rowimages->id; ?>" value="<?php echo str_replace('__5_5_5__','%',$rowimages->dislike); ?>">
												</div>
												<div class="heart_wrapper">
													<label for="like_<?php echo $rowimages->id; ?>"><?php echo __('Ratings:', 'gallery-images'); ?></label>
													<label for="like_<?php echo $rowimages->id; ?>" class="like"><?php echo __('Hearts', 'gallery-images'); ?></label>
													<input  class="" num="<?php echo $rowimages->id; ?>" type="number" id="like_<?php echo $rowimages->id; ?>" name="like_<?php echo $rowimages->id; ?>" value="<?php echo str_replace('__5_5_5__','%',$rowimages->like); ?>">
												</div>
											</div>

											<div class="clear"></div>
										</li>
										<?php
										break;
									case 'last_posts':	?>
										<li <?php if($i%2==0){echo "class='has-background'";}$i++; ?>  >
											<input class="order_by" type="hidden" name="order_by_<?php echo $rowimages->id; ?>" value="<?php echo $rowimages->ordering; ?>" />
											<div class="image-container">
												<img width='100' height='100' src="<?php echo plugins_url( 'images/pin.png' , __FILE__ ); ?>" />
											</div>
											<div class="recent-post-options image-options">
												<div>
													<div class="left">
														<?php $categories = get_categories(); ?>
														<label for="titleimage<?php echo $rowimages->id; ?>"><?php echo __('Show Posts From:', 'gallery-images'); ?></label>
														<select name="titleimage<?php echo $rowimages->id; ?>" class="categories-list">
															<option <?php if(str_replace('__5_5_5__','%',$rowimages->name) == 0){echo 'selected="selected"';} ?> value="0"><?php echo __('All Categories', 'gallery-images'); ?></option>
															<?php foreach ($categories as $strcategories){ ?>
																<option <?php if(str_replace('__5_5_5__','%',$rowimages->name) == $strcategories->cat_name){echo 'selected="selected"';} ?> value="<?php echo esc_attr($strcategories->cat_name); ?>"><?php echo $strcategories->cat_name; ?></option>
															<?php	}	?>
														</select>
													</div>
													<div  class="left">
														<label for="sl_url<?php echo $rowimages->id; ?>"><?php echo __('Showing Posts Count:', 'gallery-images'); ?></label>
														<input class="text_area url-input number" type="number" id="sl_url<?php echo $rowimages->id; ?>" name="sl_url<?php echo $rowimages->id; ?>"  value="<?php echo esc_attr(str_replace('__5_5_5__','%',$rowimages->sl_url)); ?>" >
													</div>
												</div>

												<div>
													<label class="long" for="sl_stitle<?php echo $rowimages->id; ?>"><?php echo __('Show Title:', 'gallery-images'); ?></label>
													<input type="hidden" name="sl_stitle<?php echo $rowimages->id; ?>" value="" />
													<input  <?php if($rowimages->sl_stitle == '1'){ echo 'checked="checked"'; } ?>  class="link_target" type="checkbox" name="sl_stitle<?php echo $rowimages->id; ?>" value="1" />
												</div>
												<div>
													<div class="left margin">
														<label class="long" for="sl_sdesc<?php echo $rowimages->id; ?>"><?php echo __('Show Description:', 'gallery-images'); ?></label>
														<input type="hidden" name="sl_sdesc<?php echo $rowimages->id; ?>" value="" />
														<input  <?php if($rowimages->sl_sdesc == '1'){ echo 'checked="checked"'; } ?>  class="link_target" type="checkbox" name="sl_sdesc<?php echo $rowimages->id; ?>" value="1" />
													</div>
													<div class="left">
														<label for="im_description<?php echo $rowimages->id; ?>"><?php echo __('Description Symbols Number:', 'gallery-images'); ?></label>
														<input value="<?php echo str_replace('__5_5_5__','%',$rowimages->description) ?>" class="text_area url-input number" id="im_description<?php echo $rowimages->id; ?>" type="number" name="im_description<?php echo $rowimages->id; ?>" />
													</div>
												</div>
												<div>
													<div class="left margin">
														<label class="long" for="sl_postlink<?php echo $rowimages->id; ?>"><?php echo __('Use Post Link:', 'gallery-images'); ?></label>
														<input type="hidden" name="sl_postlink<?php echo $rowimages->id; ?>" value="" />
														<input  <?php if($rowimages->sl_postlink == '1'){ echo 'checked="checked"'; } ?>  class="link_target" type="checkbox" name="sl_postlink<?php echo $rowimages->id; ?>" value="1" />
													</div>
													<div  class="left">
														<label class="long" for="sl_link_target<?php echo $rowimages->id; ?>"><?php echo __('Open Link In New Tab:', 'gallery-images'); ?></label>
														<input type="hidden" name="sl_link_target<?php echo $rowimages->id; ?>" value="" />
														<input  <?php if($rowimages->link_target == 'on'){ echo 'checked="checked"'; } ?>  class="link_target" type="checkbox" id="sl_link_target<?php echo $rowimages->id; ?>" name="sl_link_target<?php echo $rowimages->id; ?>" />

													</div>
												</div>
												<div class="remove-image-container">
													<a class="button remove-image" href="admin.php?page=sliders_huge_it_slider&id=<?php echo $row->id; ?>&task=apply&removeslide=<?php echo $rowimages->id; ?>"><?php echo __('Remove Last posts', 'gallery-images'); ?></a>
												</div>
											</div>

											<div class="clear"></div>
										</li>
										<?php
										break;

									case 'video':
										?>

										<li <?php if($i%2==0){echo "class='has-background'";}$i++; ?>  >
											<input class="order_by" type="hidden" name="order_by_<?php echo $rowimages->id; ?>" value="<?php echo $rowimages->ordering; ?>" />
											<?php 	if(strpos($rowimages->image_url,'youtube') !== false || strpos($rowimages->image_url,'youtu') !== false) {
												$liclass="youtube";
												$video_thumb_url=get_youtube_id_from_url($rowimages->image_url);
												$thumburl='<img src="http://img.youtube.com/vi/'.$video_thumb_url.'/mqdefault.jpg" alt="" />';
											}else if (strpos($rowimages->image_url,'vimeo') !== false) {
												$liclass="vimeo";
												$vimeo = $rowimages->image_url;
												$vimeo_explode = explode( "/", $vimeo );
												$imgid =  end($vimeo_explode);
												$hash = unserialize(wp_remote_fopen("http://vimeo.com/api/v2/video/".$imgid.".php"));
												$imgsrc=$hash[0]['thumbnail_large'];
												$thumburl ='<img src="'.$imgsrc.'" alt="" />';
											}
											?>
											<div class="image-container">
												<?php echo $thumburl; ?>
												<div class="play-icon <?php echo $liclass; ?>"></div>

												<div>
													<script>
														jQuery(document).ready(function ($) {

															jQuery("#slideup<?php echo $key; ?>").click(function () {
																window.parent.uploadID = jQuery(this).prev('input');
																formfield = jQuery('.upload').attr('name');
																tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');

																return false;
															});
															window.send_to_editor = function (html) {
																imgurl = jQuery('img', html).attr('src');
																window.parent.uploadID.val(imgurl);

																tb_remove();
																jQuery("#save-buttom").click();
															};
														});
														function deleteproject<?php echo $rowimages->id; ?>() {
															jQuery('#adminForm').attr('action', 'admin.php?page=galleries_huge_it_gallery&task=edit_cat&id=<?php echo $row->id; ?>&removeslide=<?php echo $rowimages->id; ?>');
														}
													</script>
													<input type="hidden" name="imagess<?php echo $rowimages->id; ?>" value="<?php echo esc_attr($rowimages->image_url); ?>" />
												</div>
											</div>
											<div class="image-options">
												<div>
													<label for="titleimage<?php echo $rowimages->id; ?>"><?php echo __('Title:', 'gallery-images'); ?></label>
													<input  class="text_area" type="text" id="titleimage<?php echo $rowimages->id; ?>" name="titleimage<?php echo $rowimages->id; ?>" id="titleimage<?php echo $rowimages->id; ?>"  value="<?php echo esc_attr(str_replace('__5_5_5__','%',$rowimages->name)); ?>">
												</div>
												<div class="description-block">
													<label for="im_description<?php echo $rowimages->id; ?>"><?php echo __('Description:', 'gallery-images'); ?></label>
													<textarea id="im_description<?php echo $rowimages->id; ?>" name="im_description<?php echo $rowimages->id; ?>" ><?php echo esc_html(str_replace('__5_5_5__','%',$rowimages->description)); ?></textarea>
												</div>
												<div class="link-block">
													<label for="sl_url<?php echo $rowimages->id; ?>"><?php echo __('URL:', 'gallery-images'); ?></label>
													<input class="text_area url-input" type="text" id="sl_url<?php echo $rowimages->id; ?>" name="sl_url<?php echo $rowimages->id; ?>"  value="<?php echo str_replace('__5_5_5__','%',$rowimages->sl_url); ?>" >
													<label class="long" for="sl_link_target<?php echo $rowimages->id; ?>">
														<span><?php echo __('Open in new tab', 'gallery-images'); ?></span>
														<input type="hidden" name="sl_link_target<?php echo $rowimages->id; ?>" value="" />
														<input  <?php if($rowimages->link_target == 'on'){ echo 'checked="checked"'; } ?>  class="link_target" type="checkbox" id="sl_link_target<?php echo $rowimages->id; ?>" name="sl_link_target<?php echo $rowimages->id; ?>" />
													</label>
												</div>
												<div class="remove-image-container">
													<a onclick="deleteproject<?php echo $rowimages->id; ?>(); submitbutton('apply');" id="remove_image<?php echo $rowimages->id; ?>" class="button remove-image"><?php echo __('Remove Video', 'gallery-images'); ?></a>
												</div>
												<div class="like_dislike_wrapper">
													<label for="like_<?php echo $rowimages->id; ?>"><?php echo __('Ratings:', 'gallery-images'); ?></label>
													<label for="like_<?php echo $rowimages->id; ?>" class="like"><?php echo __('Like', 'gallery-images'); ?></label>
													<input  class="" type="number" id="like_<?php echo $rowimages->id; ?>" name="like_<?php echo $rowimages->id; ?>" value="<?php echo str_replace('__5_5_5__','%',$rowimages->like); ?>">
													<label for="dislike_<?php echo $rowimages->id; ?>" class="dislike"><?php echo __('Dislike', 'gallery-images'); ?></label>
													<input  class="" num="<?php echo $rowimages->id; ?>" type="number" id="dislike_<?php echo $rowimages->id; ?>" name="dislike_<?php echo $rowimages->id; ?>" value="<?php echo str_replace('__5_5_5__','%',$rowimages->dislike); ?>">
												</div>
												<div class="heart_wrapper">
													<label for="like_<?php echo $rowimages->id; ?>"><?php echo __('Ratings:', 'gallery-images'); ?></label>
													<label for="like_<?php echo $rowimages->id; ?>" class="like"><?php echo __('Hearts', 'gallery-images'); ?></label>
													<input  class="" num="<?php echo $rowimages->id; ?>" type="number" id="like_<?php echo $rowimages->id; ?>" name="like_<?php echo $rowimages->id; ?>" value="<?php echo str_replace('__5_5_5__','%',$rowimages->like); ?>">
												</div>
											</div>
											<div class="clear"></div>
										</li>
										<?php
										break;
								} ?>
							<?php } ?>
						</ul>
					</div>

				</div>

				<!-- SIDEBAR -->
				<div id="postbox-container-1" class="postbox-container">
					<div id="side-sortables" class="meta-box-sortables ui-sortable">
						<div id="gallery-unique-options" class="postbox">
							<h3 class="hndle"><span><?php echo __('Image Gallery Custom Options', 'gallery-images'); ?></span></h3>
							<ul id="gallery-unique-options-list">
								<li>
									<label for="huge_it_gallery_name"><?php echo __('Gallery name', 'gallery-images'); ?></label>
									<input type = "text" name="name" id="huge_it_gallery_name" value="<?php echo esc_html(stripslashes($row->name));?>" onkeyup = "name_changeRight(this)">
								</li>
								<li>
									<label for="huge_it_sl_effects"><?php echo __('Select View', 'gallery-images'); ?></label>
									<select name="huge_it_sl_effects" id="huge_it_sl_effects">
										<option <?php if($row->huge_it_sl_effects == '0'){ echo 'selected'; } ?>  value="0"><?php echo __('Gallery/Content-Popup', 'gallery-images'); ?></option>
										<option <?php if($row->huge_it_sl_effects == '1'){ echo 'selected'; } ?>  value="1"><?php echo __('Content Slider', 'gallery-images'); ?></option>
										<option <?php if($row->huge_it_sl_effects == '5'){ echo 'selected'; } ?>  value="5"><?php echo __('Lightbox-Gallery', 'gallery-images'); ?></option>
										<option <?php if($row->huge_it_sl_effects == '3'){ echo 'selected'; } ?>  value="3"><?php echo __('Slider', 'gallery-images'); ?></option>
										<option <?php if($row->huge_it_sl_effects == '4'){ echo 'selected'; } ?>  value="4"><?php echo __('Thumbnails View', 'gallery-images'); ?></option>
										<option <?php if($row->huge_it_sl_effects == '6'){ echo 'selected'; } ?>  value="6"><?php echo __('Justified', 'gallery-images'); ?></option>
										<option <?php if($row->huge_it_sl_effects == '7'){ echo 'selected'; } ?>  value="7"><?php echo __('Blog Style Gallery', 'gallery-images'); ?></option>
									</select>
								</li>
								<script>
									jQuery(document).ready(function ($){
										if($('select[name="display_type"]').val() == 2){
											$('li[id="content_per_page"]').hide();
										}else{
											$('li[id="content_per_page"]').show();
										}
										$('select[name="display_type"]').on('change' ,function(){
											if($(this).val() == 2){
												$('li[id="content_per_page"]').hide();
											}else{
												$('li[id="content_per_page"]').show();
											}
										});

										$('#gallery-unique-options').on('change',function(){
											$( 'div[id^="gallery-current-options"]').each(function(){
												if(!$(this).hasClass( "active" )){
													$(this).find('ul li input[name="content_per_page"]').attr('name', '');
													$(this).find('ul li select[name="display_type"]').attr('name', '');
												}
											});
										});

										$('#gallery-unique-options').on('change',function(){
											$( 'div[id^="gallery-current-options"]').each(function(){
												if($('#gallery-current-options-1').hasClass('active')  || $('#gallery-current-options-3').hasClass('active'))
													$('li.for_slider').show();
												else
													$('li.for_slider').hide();
											});
										});
										$('#gallery-unique-options').change();


										$('#gallery-unique-options').on('change',function(){
											$( 'div[id^="gallery-current-options"]').each(function(){
												if(!$(this).hasClass( "active" )){
													$(this).find('ul li input[name="sl_pausetime"]').attr('name', '');
												}
											});
										});

										$('#gallery-unique-options').on('change',function(){
											$( 'div[id^="gallery-current-options"]').each(function(){
												if(!$(this).hasClass( "active" )){
													$(this).find('ul li input[name="sl_changespeed"]').attr('name', '');
												}
											});
										});





									});
								</script>
								<div id="gallery-current-options-0" class="gallery-current-options <?php if($row->huge_it_sl_effects == 0){ echo ' active'; }  ?>">
									<ul id="view7">

										<li>
											<label for="display_type"><?php echo __('Displaying Content', 'gallery-images'); ?></label>
											<select id="display_type" name="display_type">

												<option <?php if($row->display_type == 0){ echo 'selected'; } ?>  value="0"><?php echo __('Pagination', 'gallery-images'); ?></option>
												<option <?php if($row->display_type == 1){ echo 'selected'; } ?>   value="1"><?php echo __('Load More', 'gallery-images'); ?></option>
												<option <?php if($row->display_type == 2){ echo 'selected'; } ?>   value="2"><?php echo __('Show All', 'gallery-images'); ?></option>

											</select>
										</li>
										<li id="content_per_page">
											<label for="content_per_page"><?php echo __('Images Per Page', 'gallery-images'); ?></label>
											<input type="text" name="content_per_page" id="content_per_page" value="<?php echo esc_attr($row->content_per_page); ?>" class="text_area" />
										</li>



									</ul>
								</div>
								<div id="gallery-current-options-5" class="gallery-current-options <?php if($row->huge_it_sl_effects == 5){ echo ' active'; }  ?>">
									<ul id="view7">

										<li>
											<label for="display_type"><?php echo __('Displaying Content', 'gallery-images'); ?></label>
											<select id="display_type" name="display_type">

												<option <?php if($row->display_type == 0){ echo 'selected'; } ?>  value="0"><?php echo __('Pagination', 'gallery-images'); ?></option>
												<option <?php if($row->display_type == 1){ echo 'selected'; } ?>   value="1"><?php echo __('Load More', 'gallery-images'); ?></option>
												<option <?php if($row->display_type == 2){ echo 'selected'; } ?>   value="2"><?php echo __('Show All', 'gallery-images'); ?></option>

											</select>
										</li>
										<li id="content_per_page">
											<label for="content_per_page"><?php echo __('Images Per Page', 'gallery-images'); ?></label>
											<input type="text" name="content_per_page" id="content_per_page" value="<?php echo esc_attr($row->content_per_page); ?>" class="text_area" />
										</li>



									</ul>
								</div>
								<div id="gallery-current-options-4" class="gallery-current-options <?php if($row->huge_it_sl_effects == 4){ echo ' active'; }  ?>">
									<ul id="view7">

										<li>
											<label for="display_type"><?php echo __('Displaying Content', 'gallery-images'); ?></label>
											<select id="display_type" name="display_type">

												<option <?php if($row->display_type == 0){ echo 'selected'; } ?>  value="0"><?php echo __('Pagination', 'gallery-images'); ?></option>
												<option <?php if($row->display_type == 1){ echo 'selected'; } ?>   value="1"><?php echo __('Load More', 'gallery-images'); ?></option>
												<option <?php if($row->display_type == 2){ echo 'selected'; } ?>   value="2"><?php echo __('Show All', 'gallery-images'); ?></option>

											</select>
										</li>
										<li id="content_per_page">
											<label for="content_per_page"><?php echo __('Images Per Page', 'gallery-images'); ?></label>
											<input type="text" name="content_per_page" id="content_per_page" value="<?php echo esc_attr($row->content_per_page); ?>" class="text_area" />
										</li>



									</ul>
								</div>
								<div id="gallery-current-options-6" class="gallery-current-options <?php if($row->huge_it_sl_effects == 6){ echo ' active'; }  ?>">
									<ul id="view7">

										<li>
											<label for="display_type"><?php echo __('Displaying Content', 'gallery-images'); ?></label>
											<select id="display_type" name="display_type">

												<option <?php if($row->display_type == 0){ echo 'selected'; } ?>  value="0"><?php echo __('Pagination', 'gallery-images'); ?></option>
												<option <?php if($row->display_type == 1){ echo 'selected'; } ?>   value="1"><?php echo __('Load More', 'gallery-images'); ?></option>
												<option <?php if($row->display_type == 2){ echo 'selected'; } ?>   value="2"><?php echo __('Show All', 'gallery-images'); ?></option>

											</select>
										</li>
										<li id="content_per_page">
											<label for="content_per_page"><?php echo __('Images Per Page', 'gallery-images'); ?></label>
											<input type="text" name="content_per_page" id="content_per_page" value="<?php echo esc_attr($row->content_per_page); ?>" class="text_area" />
										</li>



									</ul>
								</div>
								<div id="gallery-current-options-3" class="gallery-current-options <?php if($row->huge_it_sl_effects == 3){ echo ' active'; }  ?>">
									<ul id="slider-unique-options-list">
										<li>
											<label for="sl_width"><?php echo __('Width', 'gallery-images'); ?></label>
											<input type="text" name="sl_width" id="sl_width" value="<?php echo esc_attr($row->sl_width); ?>" class="text_area" />
										</li>
										<li>
											<label for="sl_height"><?php echo __('Height', 'gallery-images'); ?></label>
											<input type="text" name="sl_height" id="sl_height" value="<?php echo esc_attr($row->sl_height); ?>" class="text_area" />
										</li>
										<li>
											<label for="gallery_list_effects_s"><?php echo __('Effects', 'gallery-images'); ?></label>
											<select name="gallery_list_effects_s" id="gallery_list_effects_s">
												<option <?php if($row->gallery_list_effects_s == 'none'){ echo 'selected'; } ?>  value="none"><?php echo __('None', 'gallery-images'); ?></option>
												<option <?php if($row->gallery_list_effects_s == 'cubeH'){ echo 'selected'; } ?>   value="cubeH"><?php echo __('Cube Horizontal', 'gallery-images'); ?></option>
												<option <?php if($row->gallery_list_effects_s == 'cubeV'){ echo 'selected'; } ?>  value="cubeV"><?php echo __('Cube Vertical', 'gallery-images'); ?></option>
												<option <?php if($row->gallery_list_effects_s == 'fade'){ echo 'selected'; } ?>  value="fade"><?php echo __('Fade', 'gallery-images'); ?></option>
												<option <?php if($row->gallery_list_effects_s == 'sliceH'){ echo 'selected'; } ?>  value="sliceH"><?php echo __('Slice Horizontal', 'gallery-images'); ?></option>
												<option <?php if($row->gallery_list_effects_s == 'sliceV'){ echo 'selected'; } ?>  value="sliceV"><?php echo __('Slice Vertical', 'gallery-images'); ?></option>
												<option <?php if($row->gallery_list_effects_s == 'slideH'){ echo 'selected'; } ?>  value="slideH"><?php echo __('Slide Horizontal', 'gallery-images'); ?></option>
												<option <?php if($row->gallery_list_effects_s == 'slideV'){ echo 'selected'; } ?>  value="slideV"><?php echo __('Slide Vertical', 'gallery-images'); ?></option>
												<option <?php if($row->gallery_list_effects_s == 'scaleOut'){ echo 'selected'; } ?>  value="scaleOut"><?php echo __('Scale Out', 'gallery-images'); ?></option>
												<option <?php if($row->gallery_list_effects_s == 'scaleIn'){ echo 'selected'; } ?>  value="scaleIn"><?php echo __('Scale In', 'gallery-images'); ?></option>
												<option <?php if($row->gallery_list_effects_s == 'blockScale'){ echo 'selected'; } ?>  value="blockScale"><?php echo __('Block Scale', 'gallery-images'); ?></option>
												<option <?php if($row->gallery_list_effects_s == 'kaleidoscope'){ echo 'selected'; } ?>  value="kaleidoscope"><?php echo __('Kaleidoscope', 'gallery-images'); ?></option>
												<option <?php if($row->gallery_list_effects_s == 'fan'){ echo 'selected'; } ?>  value="fan"><?php echo __('Fan', 'gallery-images'); ?></option>
												<option <?php if($row->gallery_list_effects_s == 'blindH'){ echo 'selected'; } ?>  value="blindH"><?php echo __('Blind Horizontal', 'gallery-images'); ?></option>
												<option <?php if($row->gallery_list_effects_s == 'blindV'){ echo 'selected'; } ?>  value="blindV"><?php echo __('Blind Vertical', 'gallery-images'); ?></option>
												<option <?php if($row->gallery_list_effects_s == 'random'){ echo 'selected'; } ?>  value="random"><?php echo __('Random', 'gallery-images'); ?></option>
											</select>
										</li>
										<li>
											<label for="slider_position"><?php echo __('Slider Position', 'gallery-images'); ?></label>
											<select name="sl_position" id="slider_position">
												<option <?php if($row->sl_position == 'left'){ echo 'selected'; } ?>  value="left"><?php echo __('Left', 'gallery-images'); ?></option>
												<option <?php if($row->sl_position == 'right'){ echo 'selected'; } ?>   value="right"><?php echo __('Right', 'gallery-images'); ?></option>
												<option <?php if($row->sl_position == 'center'){ echo 'selected'; } ?>  value="center"><?php echo __('Center', 'gallery-images'); ?></option>
											</select>
										</li>
									</ul>
								</div>
								<div id="gallery-current-options-7" class="gallery-current-options <?php if($row->huge_it_sl_effects == 7){ echo ' active'; }  ?>">
									<ul id="view7">

										<li>
											<label for="display_type"><?php echo __('Displaying Content', 'gallery-images'); ?></label>
											<select id="display_type" name="display_type">

												<option <?php if($row->display_type == 0){ echo 'selected'; } ?>  value="0"><?php echo __('Pagination', 'gallery-images'); ?></option>
												<option <?php if($row->display_type == 1){ echo 'selected'; } ?>   value="1"><?php echo __('Load More', 'gallery-images'); ?></option>
												<option <?php if($row->display_type == 2){ echo 'selected'; } ?>   value="2"><?php echo __('Show All', 'gallery-images'); ?></option>

											</select>
										</li>
										<li id="content_per_page">
											<label for="content_per_page"><?php echo __('Images Per Page', 'gallery-images'); ?></label>
											<input type="text" name="content_per_page" id="content_per_page" value="<?php echo esc_attr($row->content_per_page); ?>" class="text_area" />
										</li>



									</ul>
								</div>
								<div id="gallery-current-options-1" class="gallery-current-options <?php if($row->huge_it_sl_effects == 1){ echo ' active'; }  ?>">
									<ul id="slider-unique-options-list">
										<li>
											<label for="autoslide"><?php echo __('Autoslide', 'gallery-images'); ?></label>
											<input type="hidden" value="off" name="autoslide" />
											<input type="checkbox" name="autoslide"  value="on" id="autoslide"  <?php if($row->autoslide  == 'on'){ echo 'checked="checked"'; } ?> />
										</li>
									</ul>
								</div>
								<li class="for_slider">
									<label for="pause_on_hover"><?php echo __('Pause on hover', 'gallery-images'); ?></label>
									<input type="hidden" value="off" name="pause_on_hover" />
									<input type="checkbox" name="pause_on_hover"  value="on" id="pause_on_hover"  <?php if($row->pause_on_hover  == 'on'){ echo 'checked="checked"'; } ?> />
								</li>
								<li class="for_slider">
									<label for="sl_pausetime"><?php echo __('Pause time', 'gallery-images'); ?></label>
									<input type="text" name="sl_pausetime" id="sl_pausetime" value="<?php echo esc_html($row->description); ?>" class="text_area" />
								</li>
								<li class="for_slider">
									<label for="sl_changespeed"><?php echo __('Change speed', 'gallery-images'); ?></label>
									<input type="text" name="sl_changespeed" id="sl_changespeed" value="<?php echo esc_html(stripslashes($row->param)); ?>" class="text_area" />
								</li>
								<li>
									<label for="rating"><?php echo __('Ratings', 'gallery-images'); ?></label>
									<select id="rating" name="rating">

										<option <?php if($row->rating == 'off'){ echo 'selected'; } ?>  value="off"><?php echo __('Off', 'gallery-images'); ?></option>
										<option <?php if($row->rating == 'dislike'){ echo 'selected'; } ?>   value="dislike"><?php echo __('Like/Dislike', 'gallery-images'); ?></option>
										<option <?php if($row->rating == 'heart'){ echo 'selected'; } ?>   value="heart"><?php echo __('Heart', 'gallery-images'); ?></option>

									</select>
								</li>
							</ul>
							<div id="major-publishing-actions">
								<div id="publishing-action">
									<input type="button" onclick="submitbutton('apply')" value="Save gallery" id="save-buttom" class="button button-primary button-large">
								</div>
								<div class="clear"></div>
								<!--<input type="button" onclick="window.location.href='admin.php?page=galleries_huge_it_gallery'" value="Cancel" class="button-secondary action">-->
							</div>
						</div>
						<div id="gallery-shortcode-box" class="postbox shortcode ms-toggle">
							<h3 class="hndle"><span><?php echo __('Usage', 'gallery-images'); ?></span></h3>
							<div class="inside">
								<ul>
									<li rel="tab-1" class="selected">
										<h4><?php echo __('Shortcode', 'gallery-images'); ?></h4>
										<p><?php echo __('Copy &amp; paste the shortcode directly into any WordPress post or page.', 'gallery-images'); ?></p>
										<textarea class="full" readonly="readonly">[huge_it_gallery id="<?php echo $row->id; ?>"]</textarea>
									</li>
									<li rel="tab-2">
										<h4><?php echo __('Template Include', 'gallery-images'); ?></h4>
										<p><?php echo __('Copy &amp; paste this code into a template file to include the slideshow within your theme.', 'gallery-images'); ?></p>
										<textarea class="full" readonly="readonly">&lt;?php echo do_shortcode("[huge_it_gallery id='<?php echo $row->id; ?>']"); ?&gt;</textarea>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        <?php echo wp_nonce_field('huge_it_gallery_nonce','huge_it_gallery_nonce')?>
		<input type="hidden" name="task" value="" />
	</form>
</div>