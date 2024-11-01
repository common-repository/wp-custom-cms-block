<?php
/* 
Plugin Name: Wp Custom CMS Block
Plugin URI:
Description: Create custom content management block in your admin area in fly.
Author: digontoahsan
Version: 2.1
Author URI:
License: GPL2
*/
	
	function get_wpcc_blocks(){		
		global $wpdb;		
		$blocks = $wpdb->get_results('select block_id from '.$wpdb->prefix.'custom_cms_block');
		
		for($i=0;$i<sizeof($blocks);$i++){
			$optField[$blocks[$i]->block_id] = stripslashes(get_option($blocks[$i]->block_id));
			
		}
		return $optField;
	
	}
	/*************** short code *****************/
	
	function wpcc_block($atts) {
		global $wpdb;
		$id = $atts[id];
		
		$blocks = $wpdb->get_results('select block_id from '.$wpdb->prefix.'custom_cms_block where id = '.$id);
     	return apply_filters( 'the_content',stripslashes(get_option($blocks[0]->block_id)));
	}
	add_shortcode('block', 'wpcc_block');
	/* *********** *************** */
	function set_wpccb_option(){
		global $wpdb;
		$query = 'CREATE TABLE IF NOT EXISTS '.$wpdb->prefix.'custom_cms_block (
					`id` int(10) NOT NULL AUTO_INCREMENT,
					`block_type` varchar(100) NOT NULL,
					`block_label` varchar(500) NOT NULL,
					`block_id` varchar(500) NOT NULL,
					PRIMARY KEY (`id`)
				  ) ENGINE=InnoDB  DEFAULT CHARSET=latin1;';
		$wpdb->query($query);
		
	}
	function unset_wpccb_option(){
		global $wpdb;
		$blocks = $wpdb->get_results('select block_id from '.$wpdb->prefix.'custom_cms_block');
		
		for($i=0;$i<sizeof($blocks);$i++){
			delete_option($blocks[$i]->block_id);
			
		}
		$wpdb->query('drop table '.$wpdb->prefix.'custom_cms_block');
	}

	/* *********** *************** */

	function cm_modify_menu(){
		add_menu_page('Wp Custom CMS Block','cBlock','manage_options','wpcc_block','wpccb_admin_options');//plugins_url('wp_custom_cms_block/images/icon.png')
	}

	add_action('admin_menu','cm_modify_menu');	

	/* *********** *************** */
	register_activation_hook(WP_PLUGIN_DIR.'/wp-custom-cms-block/wp_custom_cms_block.php','set_wpccb_option');
	register_deactivation_hook(WP_PLUGIN_DIR.'/wp-custom-cms-block/wp_custom_cms_block.php','unset_wpccb_option');
	/* *********** *************** */
	
	function wpccb_admin_options(){
		include('wpcc_block_admin.php');
	}
?>