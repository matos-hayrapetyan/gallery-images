<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Gallery_Img_Featured_Plugins{
	/**
	 * Prints Featured Plugins page 
	 */
	public function show_page( ){
		include( GALLERY_IMG_TEMPLATES_PATH.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'gallery-img-admin-featured-plugins.php' );
	}
}