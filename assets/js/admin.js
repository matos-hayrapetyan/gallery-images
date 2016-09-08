var  name_changeRight = function(e) {
	document.getElementById("name").value = e.value;
}
var  name_changeTop = function(e) {
	document.getElementById("huge_it_gallery_name").value = e.value;
};

jQuery(document).ready(function () {
    jQuery('.huge-it-editnewuploader .button-edit').click(function(e) {
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
        };

        wp.media.editor.open(button);
        return false;
    });
    jQuery(".huge-it-editnewuploader").click();
    jQuery('.remove-image-container a').on('click',function () {
        var galleryId = jQuery(this).data('gallery-id');
        var imageId = jQuery(this).data('image-id');
		var removeNonce = jQuery(this).data('nonce-value');
        jQuery('#adminForm').attr('action', 'admin.php?page=galleries_huge_it_gallery&task=edit_cat&id='+galleryId+'&removeslide='+imageId+'&gallery_nonce_remove_image='+removeNonce);
        submitbutton('apply');
    });
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
		};

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
	if(jQuery('select[name="display_type"]').val() == 2){
		jQuery('li[id="content_per_page"]').hide();
	}else{
		jQuery('li[id="content_per_page"]').show();
	}
	jQuery('select[name="display_type"]').on('change' ,function(){
		if(jQuery(this).val() == 2){
			jQuery('li[id="content_per_page"]').hide();
		}else{
			jQuery('li[id="content_per_page"]').show();
		}
	});

	jQuery('#gallery-unique-options').on('change',function(){
		jQuery( 'div[id^="gallery-current-options"]').each(function(){
			if(!jQuery(this).hasClass( "active" )){
				jQuery(this).find('ul li input[name="content_per_page"]').attr('name', '');
				jQuery(this).find('ul li select[name="display_type"]').attr('name', '');
			}
		});
	});

	jQuery('#gallery-unique-options').on('change',function(){
		jQuery( 'div[id^="gallery-current-options"]').each(function(){
			if(jQuery('#gallery-current-options-1').hasClass('active')  || jQuery('#gallery-current-options-3').hasClass('active'))
				jQuery('li.for_slider').show();
			else
				jQuery('li.for_slider').hide();
		});
	});
	jQuery('#gallery-unique-options').change();

	jQuery('#gallery-unique-options').on('change',function(){
		jQuery( 'div[id^="gallery-current-options"]').each(function(){
			if(!jQuery(this).hasClass( "active" )){
				jQuery(this).find('ul li input[name="sl_pausetime"]').attr('name', '');
			}
		});
	});

	jQuery('#gallery-unique-options').on('change',function(){
		jQuery( 'div[id^="gallery-current-options"]').each(function(){
			if(!jQuery(this).hasClass( "active" )){
				jQuery(this).find('ul li input[name="sl_changespeed"]').attr('name', '');
			}
		});
	});

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
		start: function(event, ui) {
			ui.item.data('start_pos', ui.item.index());
		},
		stop: function(event, ui) {
			jQuery("#images-list > li").removeClass('has-background');
			count=jQuery("#images-list > li").length;
			for(var i=0;i<=count;i+=2){
				jQuery("#images-list > li").eq(i).addClass("has-background");
			}
			jQuery("#images-list > li").each(function(){
				jQuery(this).find('.order_by').val(jQuery(this).index());
			});
			var start = Math.min(ui.item.data('start_pos'),ui.item.index());
			var end = Math.max(ui.item.data('start_pos'),ui.item.index());
			for(var i1=start; i1<=end; i1++){
				jQuery(document.querySelectorAll("#images-list > li")[i1]).addClass('highlights');
			}
		},
		change: function(event, ui) {
			
		},
		update: function(event, ui) {
			
		},
		revert: true
	});
	var strliID = jQuery(location).attr('hash');
	jQuery('#gallery-view-tabs li').removeClass('active');
	if (jQuery('#gallery-view-tabs li a[href="' + strliID + '"]').length > 0) {
		jQuery('#gallery-view-tabs li a[href="' + strliID + '"]').parent().addClass('active');
	} else {
		jQuery('a[href="#gallery-view-options-0"]').parent().addClass('active');
	}
	strliID = strliID.split('#').join('.');
	jQuery('#gallery-view-tabs-contents > li').removeClass('active');
	if (jQuery(strliID).length > 0) {
		jQuery(strliID).addClass('active');
	} else {
		jQuery('.gallery-view-options-0').addClass('active');
	}
	jQuery('input[data-slider="true"]').bind("slider:changed", function (event, data) {
		jQuery(this).parent().find('span').html(parseInt(data.value) + "%");
		jQuery(this).val(parseInt(data.value));
	});
	
	jQuery('#arrows-type input[name="params[slider_navigation_type]"]').change(function(){
		jQuery(this).parents('ul').find('li.active').removeClass('active');
		jQuery(this).parents('li').addClass('active');
	});
	jQuery('input[data-gallery="true"]').bind("gallery:changed", function (event, data) {
		 jQuery(this).parent().find('span').html(parseInt(data.value)+"%");
		 jQuery(this).val(parseInt(data.value));
	});
	
	jQuery('#gallery-view-tabs li a').click(function(){
		jQuery('#gallery-view-tabs > li').removeClass('active');
		jQuery(this).parent().addClass('active');
		jQuery('#gallery-view-tabs-contents > li').removeClass('active');
		var liID=jQuery(this).attr('href').split('#').join('.');//alert(liID);
		jQuery(liID).addClass('active');
                liID=liID.replace('.','');
		jQuery('#adminForm').attr('action',"admin.php?page=Options_gallery_styles&task=save#"+liID);
	});
	
	jQuery('#huge_it_sl_effects').change(function(){
		
		jQuery('.gallery-current-options').removeClass('active');
		jQuery('#gallery-current-options-'+jQuery(this).val()).addClass('active');
	});

	jQuery(".close_free_banner").on("click",function(){
		jQuery(".free_version_banner").css("display","none");
		hgSliderSetCookie( 'hgSliderFreeBannerShow', 'no', {expires:3600} );
	});
	
	jQuery('.huge-it-insert-video-button').click(function(){
		alert("Image Gallery Settings are disabled in free version. If you need those functionalityes, you need to buy the commercial version.");
		return false;
	});
	jQuery('a[href*="remove_cat"]').click(function(){
		if(!confirm('Are you sure you want to delete this item?'))
			return false;
	});
	
});

/* Cookies */
function hgSliderGetCookie(name) {
	var matches = document.cookie.match(new RegExp(
		"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	));
	return matches ? decodeURIComponent(matches[1]) : undefined;
}

function hgSliderSetCookie(name, value, options) {
	options = options || {};

	var expires = options.expires;

	if (typeof expires == "number" && expires) {
		var d = new Date();
		d.setTime(d.getTime() + expires * 1000);
		expires = options.expires = d;
	}
	if (expires && expires.toUTCString) {
		options.expires = expires.toUTCString();
	}


	if(typeof value == "object"){
		value = JSON.stringify(value);
	}
	value = encodeURIComponent(value);
	var updatedCookie = name + "=" + value;

	for (var propName in options) {
		updatedCookie += "; " + propName;
		var propValue = options[propName];
		if (propValue !== true) {
			updatedCookie += "=" + propValue;
		}
	}

	document.cookie = updatedCookie;
}

function hgSliderDeleteCookie(name) {
	setCookie(name, "", {
		expires: -1
	})
};

function filterInputs() {
	var mainInputs = "";
	jQuery("#images-list > li.highlights").each(function(){
		jQuery(this).next().addClass('submit-post');
		jQuery(this).prev().addClass('submit-post');
		jQuery(this).addClass('submit-post');
		jQuery(this).removeClass('highlights');
	});
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
		return mainInputs;

	};
	jQuery("#images-list > li").each(function(){
		jQuery(this).find('input').removeAttr('name');
		jQuery(this).find('textarea').removeAttr('name');
		jQuery(this).find('select').removeAttr('name');
	});
};

function submitbutton(pressbutton){
	if(!document.getElementById('name').value){
		alert("Name is required.");
		return;
	}
	filterInputs();
	document.getElementById("adminForm").action=document.getElementById("adminForm").action+"&task="+pressbutton;
	document.getElementById("adminForm").submit();
}
function change_select(){
	submitbutton('apply');
}
function ordering(name,as_or_desc)
{
	document.getElementById('asc_or_desc').value=as_or_desc;
	document.getElementById('order_by').value=name;
	document.getElementById('admin_form').submit();
}
function saveorder()
{
	document.getElementById('saveorder').value="save";
	document.getElementById('admin_form').submit();

}
function listItemTask(this_id,replace_id)
{
	document.getElementById('oreder_move').value=this_id+","+replace_id;
	document.getElementById('admin_form').submit();
}
function doNothing() {
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	if( keyCode == 13 ) {


		if(!e) var e = window.event;

		e.cancelBubble = true;
		e.returnValue = false;

		if (e.stopPropagation) {
			e.stopPropagation();
			e.preventDefault();
		}
	}
}

