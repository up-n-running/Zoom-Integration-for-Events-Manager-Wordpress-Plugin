<?php

/*
 * Install Script for the add on. The function that does all the work is ueme_install 
 * Which is called from the upnrunning-eventman-extras.php file whenever it is
 * registered with wordpress (on plugin install/activation) 
 */

global $ueme_db_version;
$ueme_db_version = '1.0';

/*
 * This is the main install function. It Updates the database and registers
 * a wordpress option 'ueme_db_version' in the db so that when the next majoy version is
 * released this install script will know whether it's a fresh install or an upgrade
 */
function ueme_install() {

    //Checks if the main Events Manager plugin is installled!
    //If not it feeds error back to user then we just quit before altering db
    if( !check_dependencies() ) {
        return;
    }

    //dont think i need this because this function is only called from an 
    //activate / install plugin hook
    //but better safe than sorry ;)
    //if a non-admin goes to this file's url in the browser without
    //going through the plugin install process through admin then
    //quit before altering db
    if( !current_user_can( 'manage_options' ) )
    {
        return;
    }
        
	global $wpdb;
	global $ueme_db_version;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
    //CREATE NEW TABLE FOR ZOOM SETTINGS - this table will grow to store zoom
    //api keys should we choose to integrate with a zoom plugin.
    //The constant UEME_ZOOM_SETTINGS_TABLE is defined in upnrunning-eventman-extras.php
    //NOTE: the zoomset_api_key column is not used as ov v1.0 but shows that we can use this
    //table to store necessary settings for multiple zoom accounts 
    //should we add proper zoom integration or integration
    //with an existing 3rd party zoom plugin
	$charset_collate = $wpdb->get_charset_collate();     
	$sql = "CREATE TABLE " . UEME_ZOOM_SETTINGS_TABLE . " (
                zoomset_id bigint(20) NOT NULL AUTO_INCREMENT,
                zoomset_name varchar(255) NOT NULL DEFAULT '',                
                zoomset_detach_recurrences tinyint(1) NOT NULL DEFAULT 0,
                zoomset_api_key varchar(255) NOT NULL DEFAULT '',
		PRIMARY KEY  (zoomset_id)
        ) $charset_collate;";
	dbDelta( $sql );
        
    //I havent written a /wp-admin screen to maintain the records in this table yet
    //but i dont need to unless build in proper zoom integration through zoom api
    //or by hooking into an existing 3rd party plugin, for the time being the
    //2 zoom settings records we need can be added to the table in install script
    
    //Is the table empty (it will be if table was just created from scratch in previous step)
    $number_of_settings = $wpdb->get_var(  "SELECT COUNT(*) FROM " . UEME_ZOOM_SETTINGS_TABLE );
    //if so, add the 2 settings records we need
    if( $number_of_settings==0 ) {
        $wpdb->query("INSERT INTO " . UEME_ZOOM_SETTINGS_TABLE . " (zoomset_name, zoomset_detach_recurrences, zoomset_api_key) VALUES ('Zoom Event - Use individual Event Links', 1, '')" );
        $wpdb->query("INSERT INTO " . UEME_ZOOM_SETTINGS_TABLE . " (zoomset_name, zoomset_detach_recurrences, zoomset_api_key) VALUES ('Zoom Event - Use Link from Recurring Event', 0, '')" );
    }  

    //To provide a more elegant solution than just using custom fields we add 2 extra columns onto the
    //Events db table used by the main Events Manager Plugin. These column names are prefixed with
    //ueme_ to avoid  any future field naming conflicts.
    //the constant EM_EVENTS_TABLE used below is defined in the main Events Manager plugin itself
    
    //do these extra columns already exist?
    $row = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . EM_EVENTS_TABLE . "' AND column_name = 'ueme_zoom_settings_id'"  );
    //if not, add them.
    if(empty($row)){
        
        $sql = "ALTER TABLE " . EM_EVENTS_TABLE . " ADD ueme_zoom_settings_id bigint(20) DEFAULT -1";
        $wpdb->query($sql);
        $sql = "ALTER TABLE " . EM_EVENTS_TABLE . " ADD ueme_zoom_url varchar(254) NOT NULL DEFAULT ''";
        $wpdb->query($sql);
    }        

	add_option( 'ueme_db_version', $ueme_db_version );
}

function check_dependencies()
{
    //this is defined by the main Event Manager Plugin - if it's not set then it's not installed
    if ( !defined('EM_VERSION') ) {
         // dependency not installed, show an error and bail
         add_action('admin_notices', 'dependency_check_failed_admin_notice');
         return false;
    }
    return true;
}

function dependency_check_failed_admin_notice()
{
    ?>
    <div class="error">
        <p>
            <?php _e('The Plugin: \'upnrunning-eventman-extras\' requires Events Manager plugin to be installed and activated. Please install and activate it and if necessary then re-activate \'upnrunning-eventman-extras\' to re-run its database install script again.', 'ueme'); ?>
        </p>
    </div>
    <?php
}

?>