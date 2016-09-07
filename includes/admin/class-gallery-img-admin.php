<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gallery_Img_Admin
{

    /**
     * Array of pages in admin
     * @var array
     */
    public $pages = array();

    /**
     * Instance of Gallery_Img_General_Options class
     *
     * @var Gallery_Img_General_Options
     */
    public $general_options = null;

    /**
     * Instance of Gallery_Img_Galleries class
     *
     * @var Gallery_Img_Galleries
     */
    public $galleries = null;

    /**
     * Instance of Gallery_Img_Lightbox_Options class
     *
     * @var Gallery_Img_Lightbox_Options
     */
    public $lightbox_options = null;

    /**
     * Instance of Gallery_Img_Featured_Plugins class
     *
     * @var Gallery_Img_Featured_Plugins
     */
    public $featured_plugins = null;

    /**
     * Instance of Gallery_Img_Licensing class
     *
     * @var Gallery_Img_Licensing
     */
    public $licensing = null;

    /**
     * Gallery_Img_Admin constructor.
     */
    public function __construct()
    {
        $this->init();
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('wp_loaded', array($this, 'wp_loaded'));
    }

    /**
     * Initialize Image Gallery's admin
     */
    protected function init()
    {
        $this->general_options = new Gallery_Img_General_Options();
        $this->galleries = new Gallery_Img_Galleries();
        $this->lightbox_options = new Gallery_Img_Lightbox_Options();
        $this->featured_plugins = new Gallery_Img_Featured_Plugins();
        $this->licensing = new Gallery_Img_Licensing();
    }

    /**
     * Prints Gallery Menu
     */
    public function admin_menu()
    {
        $this->pages[] = add_menu_page(__('Huge-IT  Gallery', 'gallery-images'), __('Huge-IT Gallery', 'gallery-images'), 'delete_pages', 'galleries_huge_it_gallery', array(Gallery_Img()->admin->galleries, 'load_gallery_page'), GALLERY_IMG_IMAGES_URL . "/admin_images/huge-it-gallery-logo-for-menu.png");
        $this->pages[] = add_submenu_page('galleries_huge_it_gallery', __('Galleries', 'gallery-images'), __('Galleries', 'gallery-images'), 'delete_pages', 'galleries_huge_it_gallery', array(Gallery_Img()->admin->galleries, 'load_gallery_page'));

        $this->pages[] = add_submenu_page('galleries_huge_it_gallery', __('General Options', 'gallery-images'), __('General Options', 'gallery-images'), 'delete_pages', 'Options_gallery_styles', array(Gallery_Img()->admin->general_options, 'load_page'));
        $this->pages[] = add_submenu_page('galleries_huge_it_gallery', __('Lightbox Options', 'gallery-images'), __('Lightbox Options', 'gallery-images'), 'delete_pages', 'Options_gallery_lightbox_styles', array(Gallery_Img()->admin->lightbox_options, 'load_page'));

        $this->pages[] = add_submenu_page('galleries_huge_it_gallery', __('Featured Plugins', 'gallery-images'), __('Featured Plugins', 'gallery-images'), 'delete_pages', 'huge_it_gallery_featured_plugins', array(Gallery_Img()->admin->featured_plugins, 'show_page'));
        $this->pages[] = add_submenu_page('galleries_huge_it_gallery', __('Licensing', 'gallery-images'), __('Licensing', 'gallery-images'), 'delete_pages', 'huge_it_gallery_licensing', array(Gallery_Img()->admin->licensing, 'show_page'));
    }


    public function wp_loaded()
    {
		if (isset($_REQUEST['gallery_wp_nonce_add_gallery'])) {
			$wp_nonce1 = $_REQUEST['gallery_wp_nonce_add_gallery'];
			if (!wp_verify_nonce($wp_nonce1, 'gallery_wp_nonce_add_gallery')) {
				wp_die('Security check fail');
			}
		}
        global $wpdb;
        if (isset($_GET['task']) && $_GET['page'] == 'galleries_huge_it_gallery') {
            $task = $_GET['task'];
            if ($task == 'add_cat') {
                $table_name = $wpdb->prefix . "huge_itgallery_gallerys";
                $sql_2 = "
INSERT INTO 
`" . $table_name . "` ( `name`, `sl_height`, `sl_width`, `pause_on_hover`, `gallery_list_effects_s`, `description`, `param`, `sl_position`, `ordering`, `published`, `huge_it_sl_effects`) VALUES
( 'New gallery', '375', '600', 'on', 'cubeH', '4000', '1000', 'center', '1', '300', '4')";
                $wpdb->query($sql_2);
                $query = "SELECT * FROM " . $wpdb->prefix . "huge_itgallery_gallerys order by id ASC";
                $rowsldcc = $wpdb->get_results($query);
                $last_key = key(array_slice($rowsldcc, -1, 1, TRUE));
				foreach ($rowsldcc as $key => $rowsldccs) {
                    if ($last_key == $key) {
                        header('Location: admin.php?page=galleries_huge_it_gallery&id=' . $rowsldccs->id . '&task=apply');
                    }
                }
            }
        }
    }

}

