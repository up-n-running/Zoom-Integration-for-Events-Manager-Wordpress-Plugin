<?php
/*
	Based on Example uninstall.php file
	Contains examples provided in the tutorial:
	WordPress uninstall.php file - The Complete Guide
	@ https://digwp.com/2019/10/wordpress-uninstall-php/
*/



// exit if uninstall constant is not defined
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

//setup vars to replace constants that might have gone mid uninstall
global $wpdb;
if( EM_MS_GLOBAL ){
	$prefix = $wpdb->base_prefix;
}else{
	$prefix = $wpdb->prefix;
}
$UEME_ZOOM_SETTINGS_TABLE = $prefix.'em_ueme_zoom_settings';
        

// delete plugin options used in V0.1 no longer used in latest version so probably nothing to delete but just in case
delete_option('upnrunning_em_zoom_settings');

//used since v1.0
delete_option('ueme_db_version');


// delete cron event
//$timestamp = wp_next_scheduled('myplugin_cron_event');
//wp_unschedule_event($timestamp, 'myplugin_cron_event');

// delete zoom settings table
global $wpdb;
$table_name = $wpdb->prefix .'myplugin_table';
$wpdb->query("DROP TABLE IF EXISTS " . $UEME_ZOOM_SETTINGS_TABLE);


//EXTRA FIELDS ON EVENT TABLE
//do the extra fields on event table exist?
$row = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . EM_EVENTS_TABLE . "' AND column_name = 'ueme_zoom_settings_id'"  );
//if so, drop them.
if(!empty($row)){
    $sql = "ALTER TABLE " . EM_EVENTS_TABLE . " DROP ueme_zoom_settings_id";
    $wpdb->query($sql);
    $sql = "ALTER TABLE " . EM_EVENTS_TABLE . " DROP ueme_zoom_url";
    $wpdb->query($sql);
}        


// delete pages
//$myplugin_pages = get_option('myplugin_pages');
//if (is_array($myplugin_pages) && !empty($myplugin_pages)) {
//	foreach ($myplugin_pages as $myplugin_page) {
//		wp_trash_post($myplugin_page);
//	}
//}

// delete custom post type posts
//$myplugin_cpt_args = array('post_type' => 'myplugin_cpt', 'posts_per_page' => -1);
//$myplugin_cpt_posts = get_posts($myplugin_cpt_args);
//foreach ($myplugin_cpt_posts as $post) {
//	wp_delete_post($post->ID, false);
//}

// delete user meta
//$users = get_users();
//foreach ($users as $user) {
//	delete_user_meta($user->ID, 'myplugin_user_meta');
//}

// delete post meta
//$myplugin_post_args = array('posts_per_page' => -1);
//$myplugin_posts = get_posts($myplugin_post_args);
//foreach ($myplugin_posts as $post) {
//	delete_post_meta($post->ID, 'myplugin_post_meta');
//}
?>
