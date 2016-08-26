<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Filter  Gallery Image
 * Not sure if it works, but to avoid conflicts with previous versions of plugin we leave this untouched
 * 
 * Class Gallery_Img_Post_Search
 */
class Gallery_Img_Post_Search {

	public function __construct() {
		add_filter( 'posts_request', array($this,'after_search_results') );
	}

	public function after_search_results(){
		global $wpdb;
		if ( isset( $_REQUEST['s'] ) && $_REQUEST['s'] ) {
			$serch_word = htmlspecialchars( ( $_REQUEST['s'] ) );
			$query      = str_replace( $wpdb->prefix . "posts.post_content", $this->generate_string_gallery_search( $serch_word, $wpdb->prefix . 'posts.post_content' ) . " " . $wpdb->prefix . "posts.post_content", $query );
		}

		return $query;
	}

	public function generate_string_gallery_search( $serch_word, $wordpress_query_post ) {
		$string_search = '';

		global $wpdb;
		if ( $serch_word ) {
			$rows_gallery = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itgallery_gallerys WHERE (description LIKE %s) OR (name LIKE %s)", '%' . $serch_word . '%', "%" . $serch_word . "%" ) );

			$count_cat_rows = count( $rows_gallery );

			for ( $i = 0; $i < $count_cat_rows; $i ++ ) {
				$string_search .= $wordpress_query_post . ' LIKE \'%[huge_it_gallery id="' . $rows_gallery[ $i ]->id . '" details="1" %\' OR ' . $wordpress_query_post . ' LIKE \'%[huge_it_gallery id="' . $rows_gallery[ $i ]->id . '" details="1"%\' OR ';
			}

			$rows_gallery = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itgallery_gallerys WHERE (name LIKE %s)", "'%" . $serch_word . "%'" ) );
			$count_cat_rows = count( $rows_gallery );
			for ( $i = 0; $i < $count_cat_rows; $i ++ ) {
				$string_search .= $wordpress_query_post . ' LIKE \'%[huge_it_gallery id="' . $rows_gallery[ $i ]->id . '" details="0"%\' OR ' . $wordpress_query_post . ' LIKE \'%[huge_it_gallery id="' . $rows_gallery[ $i ]->id . '" details="0"%\' OR ';
			}

			$rows_single = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itgallery_images WHERE name LIKE %s", "'%" . $serch_word . "%'" ) );

			$count_sing_rows = count( $rows_single );
			if ( $count_sing_rows ) {
				for ( $i = 0; $i < $count_sing_rows; $i ++ ) {
					$string_search .= $wordpress_query_post . ' LIKE \'%[huge_it_gallery_Product id="' . $rows_single[ $i ]->id . '"]%\' OR ';
				}

			}
		}

		return $string_search;
	}
}

new Gallery_Img_Post_Search();