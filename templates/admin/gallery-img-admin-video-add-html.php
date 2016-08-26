<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (isset($_REQUEST['huge_it_gallery_nonce'])) {
    $wp_nonce = $_GET['huge_it_gallery_nonce'];
    if (!wp_verify_nonce($wp_nonce, 'huge_it_gallery_nonce')) {
        wp_die('Security check fail');
    }
}
$gallery_wp_nonce = wp_create_nonce('huge_it_gallery_nonce');
global $wpdb;
if (isset($_GET['id']) && $_GET['id'] != '') {
    $id = intval($_GET['id']);
}
?>
<style>
    html.wp-toolbar {
        padding: 0px !important;
    }

    #wpadminbar, #adminmenuback, #screen-meta, .update-nag, #dolly {
        display: none;
    }

    #wpbody-content {
        padding-bottom: 30px;
    }

    #adminmenuwrap {
        display: none !important;
    }

    .auto-fold #wpcontent, .auto-fold #wpfooter {
        margin-left: 0px;
    }

    #wpfooter {
        display: none;
    }

    iframe {
        height: 250px !important;
    }

    #TB_window {
        height: 250px !important;
    }
</style>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('.huge-it-insert-video-button').click(function(){
            alert("Image Gallery Settings are disabled in free version. If you need those functionalityes, you need to buy the commercial version.");
            return false;
        });
    });
</script>
<a id="closepopup" onclick=" parent.eval('tb_remove()')" style="display:none;"> [X] </a>

<div id="huge_it_slider_add_videos">
    <div id="huge_it_slider_add_videos_wrap">
        <h2><?php echo __('Add Video URL From Youtube or Vimeo', 'gallery-images'); ?></h2>
        <div class="control-panel">
            <form method="post"
                  action="admin.php?page=galleries_huge_it_gallery&task=gallery_video&id=<?php echo $id ?>&huge_it_gallery_nonce=<?php echo $gallery_wp_nonce; ?>&closepop=1">
                <input type="text" id="huge_it_add_video_input" name="huge_it_add_video_input"/>
                <button class='save-slider-options button-primary huge-it-insert-video-button'
                        id='huge-it-insert-video-button'><?php echo __('Insert Video', 'gallery-images'); ?></button>
                <div id="add-video-popup-options">
                    <div>
                        <div>
                            <label for="show_title"><?php echo __('Title:', 'gallery-images'); ?></label>
                            <div>
                                <input name="show_title" value="" type="text"/>
                            </div>
                        </div>
                        <div>
                            <label for="show_description"><?php echo __('Description:', 'gallery-images'); ?></label>
                            <textarea id="show_description" name="show_description"></textarea>
                        </div>
                        <div>
                            <label for="show_url"><?php echo __('Url:', 'gallery-images'); ?></label>
                            <input type="text" name="show_url" value=""/>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>