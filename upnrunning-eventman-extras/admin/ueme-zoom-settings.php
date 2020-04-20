<?php
/**
 * Code to manage the Zoom Settings Config screen in the admin console
 * TO DO - REWRITE THIS USING db table instead of get_option('upnrunning_em_zoom_settings')
 */
/* COMMENTED OUT AS NEED TO REWRITE - SETTINGS ARE CONFIGURED IN INSTALL SCRIPT FOR TIME BEING  
//Add the screen to the admin nav menu
function upnrinning_em_zoom_submenu () {
    $plugin_page = add_submenu_page('edit.php?post_type='.EM_POST_TYPE_EVENT, 'Zoom Settings', 'Zoom Settings', 'edit_events', "events-manager-zoom-settings", 'upnrunning_em_admin_zoom_settings_page');
    add_action( 'admin_print_styles-'. $plugin_page, 'em_admin_load_zoom_settings' );
    add_action( 'admin_head-'. $plugin_page, 'em_admin_general_zoom_settings' );
}
add_action('admin_menu','upnrinning_em_zoom_submenu', 20);

// This is called whenthe user clicks on Zoom Settings on the admin nav menu or whenever anyone clicks submit on the admin zoom settings screen
function upnrunning_em_admin_zoom_settings_page() {
    global $wpdb, $EM_Event, $EM_Notices, $upnrunning_em_zoom_settings;
    $upnrunning_em_zoom_settings = is_array(get_option('upnrunning_em_zoom_settings')) ? get_option('upnrunning_em_zoom_settings'):array() ;
    if( !empty($_REQUEST['action']) ){
        if( $_REQUEST['action'] == "zoom_settings_save" && wp_verify_nonce($_REQUEST['_wpnonce'], 'zoom_settings_save') ) {
            //Just add it to the array or replace
            if( !empty($_REQUEST['zoom_settings_id']) && array_key_exists($_REQUEST['zoom_settings_id'], $upnrunning_em_zoom_settings) ){
                //A previous style, so we just update
                $upnrunning_em_zoom_settings[$_REQUEST['zoom_settings_id']] = $_REQUEST['zoom_settings_name'];
                $EM_Notices->add_confirm('Zoom Settings Updated');
            } else {
                //A new style, so we either add it to the end of the array, or start it off at index 1 if it's the first item to be added.
                if( count($upnrunning_em_zoom_settings) > 0 ){
                    $upnrunning_em_zoom_settings[] = $_REQUEST['zoom_settings_name'];
                }else{
                    $upnrunning_em_zoom_settings[1] = $_REQUEST['zoom_settings_name'];
                }
                $EM_Notices->add_confirm('Zoom Settings Added');
            }
            update_option('upnrunning_em_zoom_settings',$upnrunning_em_zoom_settings);
        } elseif( $_REQUEST['action'] == "zoom_settings_delete" && wp_verify_nonce($_REQUEST['_wpnonce'], 'zoom_settings_delete') ){
            //Unset the style from the array and save
            if(is_array($_REQUEST['zoom_settings'])){
                foreach($_REQUEST['zoom_settings'] as $id){
                    unset($upnrunning_em_zoom_settings[$id]);
                }
                update_option('upnrunning_em_zoom_settings',$upnrunning_em_zoom_settings);
                $EM_Notices->add_confirm('Zoom Settings Deleted');
            }
        }
    }
    upnrunning_em_zoom_settings_table_layout();
}

//this function displays the html for the zoom settings admin page
function upnrunning_em_zoom_settings_table_layout() {
    global $EM_Notices, $upnrunning_em_zoom_settings;
    ?>
    <div class='wrap'>
        <div id='icon-edit' class='icon32'>
            <br/>
        </div>
        <h2>Zoom Settings</h2>
        <?php echo $EM_Notices; ?>
        <div id='col-container'>
            <!-- begin col-right -->
            <div id='col-right'>
                <div class='col-wrap'>
                     <form id='zoom-settings' method='post' action=''>
                        <input type='hidden' name='action' value='zoom_settings_delete'/>
                        <input type='hidden' name='_wpnonce' value='<?php echo wp_create_nonce('zoom_settings_delete'); ?>' />
                        <?php if (count($upnrunning_em_zoom_settings)>0) : ?>
                            <table class='widefat'>
                                <thead>
                                    <tr>
                                        <th class='manage-column column-cb check-column' scope='col'><input type='checkbox' class='select-all' value='1'/></th>
                                        <th><?php echo __('ID', 'dbem') ?></th>
                                        <th><?php echo __('Name', 'dbem') ?></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th class='manage-column column-cb check-column' scope='col'><input type='checkbox' class='select-all' value='1'/></th>
                                        <th><?php echo __('ID', 'dbem') ?></th>
                                        <th><?php echo __('Name', 'dbem') ?></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php foreach ($upnrunning_em_zoom_settings as $zoom_settings_id => $zoom_settings_name) : ?>
                                    <tr>
                                        <td><input type='checkbox' class ='row-selector' value='<?php echo $zoom_settings_id ?>' name='zoom_settings[]'/></td>
                                        <td><a href='<?php echo get_bloginfo('wpurl') ?>/wp-admin/admin.php?page=events-manager-zoom-settings&amp;action=edit&amp;zoom_settings_id=<?php echo $zoom_settings_id ?>'><?php echo $zoom_settings_id; ?></a></td>
                                        <td><a href='<?php echo get_bloginfo('wpurl') ?>/wp-admin/admin.php?page=events-manager-zoom-settings&amp;action=edit&amp;zoom_settings_id=<?php echo $zoom_settings_id ?>'><?php echo htmlspecialchars($zoom_settings_name, ENT_QUOTES); ?></a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
 
                            </table>
 
                            <div class='tablenav'>
                                <div class='alignleft actions'>
                                <input class='button-secondary action' type='submit' name='doaction2' value='Delete'/>
                                <br class='clear'/>
                                </div>
                                <br class='clear'/>
                            </div>
                        <?php else: ?>
                            <p>No Zoom Settings inserted yet!</p>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <!-- end col-right -->
 
            <!-- begin col-left -->
            <div id='col-left'>
                <div class='col-wrap'>
                    <div class='form-wrap'>
                        <div id='ajax-response'>
                            <h2><?php echo empty($_REQUEST['zoom_settings_id']) ? 'Add':'Update'; ?> Zoom Settings</h2>
                            <form name='add' id='add' method='post' action='' class='add:the-list: validate'>
                                <input type='hidden' name='action' value='zoom_settings_save' />
                                <input type='hidden' name='_wpnonce' value='<?php echo wp_create_nonce('zoom_settings_save'); ?>' />
                                <div class='form-field form-required'>
                                    <label for='zoom-settings-name'>Zoom Settings Name</label>
                                    <?php if( !empty($_REQUEST['zoom_settings_id']) && array_key_exists($_REQUEST['zoom_settings_id'], $upnrunning_em_zoom_settings)): ?>
                                    <input id='zoom-settings-name' name='zoom_settings_name' type='text' size='40' value="<?php echo $upnrunning_em_zoom_settings[$_REQUEST['zoom_settings_id']]; ?>" />
                                    <input id='zoom-settings-id' name='zoom_settings_id' type='hidden' value="<?php echo $_REQUEST['zoom_settings_id']; ?>" />
                                    <?php else: ?>
                                    <input id='zoom-settings-name' name='zoom_settings_name' type='text' size='40' />
                                    <?php endif; ?>
                                </div>
                                <p class='submit'>
                                    <?php if( !empty($_REQUEST['zoom_settings_id']) ): ?>
                                    <input type='submit' class='button' name='submit' value='Update Zoom Settings' />
                                    or <a href="admin.php?page=events-manager-zoom-settings">Add New</a>
                                    <?php else: ?>
                                    <input type='submit' class='button' name='submit' value='Add Zoom Settings' />
                                    <?php endif; ?>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col-left -->
        </div>
    </div>
    <?php
}
*/
?>