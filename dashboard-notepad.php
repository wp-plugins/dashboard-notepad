<?php
/*
Plugin Name: Dashboard Notepad
Plugin URI: http://sillybean.net/code/wordpress/dashboard-notepad/
Description: The very simplest of notepads for your Dashboard. Based on <a href="http://www.contutto.com/">Alex G&uuml;nsche's</a> Headache With Pictures. You can use the <code>&lt;?php dashboard_notes(); ?&gt;</code> template tag or the <code>[dashboard_notes]</code> shortcode to display your notes publicly.
Author: Stephanie Leary
Version: 1.24
Author URI: http://sillybean.net/
*/

/*
	Dashboard Notepad Copyright (C) 2009  Stephanie Leary  (email : steph@sillybean.net)
	
	Based on:
	Headache With Pictures -- WP plugin to quickly note things on the dashboard.
	Copyright (C) 2006 Alex G&uuml;nsche <ag@zirona.com>
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation in the Version 2.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
*/

function dashboard_notepad_widget() {
	$options = dashboard_notepad_widget_options();
	if (!empty($_POST['dashboard_notepad_submit']) ) {			
			if ( current_user_can('unfiltered_html') )
				$options['notes'] =  stripslashes($_POST['dashboard_notepad']);
			else
				$options['notes'] = stripslashes( wp_filter_post_kses( $_POST['dashboard_notepad'] ) );
			update_option('dashboard_notepad', $options);
	} else
		$dashboard_notepad = htmlspecialchars($options['notes'], ENT_QUOTES);
	if (current_user_can($options['can_edit'])) $admin = TRUE;
	else $admin = FALSE;
	$form = '<form method="post" action="'.$_SERVER['PHP_SELF'].'">';
	$form .= '<textarea id="dashboard_notepad" name="dashboard_notepad"';
	if (!$admin) $form.= ' readonly="readonly"';
	$form .= '>'. $options['notes'].'</textarea>';
	if ($admin) $form .= '<p><input type="submit" value="' . __('Save Notes', 'dashboard-notepad') . '" class="button widget-control-save"></p> 
		<input type="hidden" name="dashboard_notepad_submit" value="true" />';
	$form .= '</form>';
	echo $form;
}

function dashboard_notepad_css() {
	echo '<style type="text/css">textarea#dashboard_notepad { width: 95%; height: 12em; background: #fcfcfc; }</style>';
}
 
function dashboard_notepad_widget_setup() {
	$options = dashboard_notepad_widget_options();
	if (!is_array($options)) $options = array('title' => __('Notepad', 'dashboard-notepad'));
        if (current_user_can($options['can_read']) || ($options['can_read'] == 'guest')) {
		wp_add_dashboard_widget( 'dashboard_notepad_widget_id', $options['notepad_title'], 'dashboard_notepad_widget', 'dashboard_notepad_widget_control');
	}
}

add_action("admin_head-index.php", 'dashboard_notepad_css'); 			 // add styles to Dashboard only
add_action('wp_dashboard_setup', 'dashboard_notepad_widget_setup');


function dashboard_notepad_widget_options() {
	$defaults = array( 'notes' => __('Enter here whatever is on your mind.', 'dashboard-notepad'), 'can_edit' => 'edit_dashboard', 'can_read' => 'read', 
		'notepad_title' => __('Notepad', 'dashboard-notepad'), 'autop' => '');
	$options = get_option('dashboard_notepad');
	if (!is_array($options)) $options = array();
	return array_merge( $defaults, $options );
}

function dashboard_notepad_widget_control() {
	$options = dashboard_notepad_widget_options();
	if ( 'post' == strtolower($_SERVER['REQUEST_METHOD']) && isset( $_POST['widget_id'] ) && 'dashboard_notepad_widget_id' == $_POST['widget_id'] ) {
		if ( isset($_POST['can_edit']) )
			$options['can_edit'] = $_POST['can_edit'];
		if ( isset($_POST['can_read']) )
			$options['can_read'] = $_POST['can_read'];
		if ( isset($_POST['notepad_title']) )
			$options['notepad_title'] = $_POST['notepad_title'];
		$options['autop'] = $_POST['autop'];
		update_option( 'dashboard_notepad', $options );
	}
?>
	<p><label for="notepad_title"><?php _e( 'Widget title:' , 'dashboard-notepad'); ?></label>
		<input type="text" id="notepad_title" name="notepad_title" value="<?php echo $options['notepad_title']; ?>" /></p>
        <p>
        <select id="can_edit" name="can_edit">
			<option value="edit_dashboard" <?php selected('edit_dashboard', $options['can_edit']); ?>><?php _e('Admins', 'dashboard-notepad'); ?></option>
			<option value="edit_pages" <?php selected('edit_pages', $options['can_edit']); ?>><?php _e('Editors', 'dashboard-notepad'); ?></option>
			<option value="publish_posts" <?php selected('publish_posts', $options['can_edit']); ?>><?php _e('Authors', 'dashboard-notepad'); ?></option>
			<option value="edit_posts" <?php selected('edit_posts', $options['can_edit']); ?>><?php _e('Contributors', 'dashboard-notepad'); ?></option>
			<option value="read" <?php selected('read', $options['can_edit']); ?>><?php _e('Subscribers', 'dashboard-notepad'); ?></option>
		</select>
        <label for="can_edit"><?php _e( 'and above can <strong>edit</strong> the notes.' , 'dashboard-notepad'); ?></label>
	</p>
    <p>
		<select id="can_read" name="can_read">
			<option value="edit_dashboard" <?php selected('edit_dashboard', $options['can_read']); ?>><?php _e('Admins', 'dashboard-notepad'); ?></option>
			<option value="edit_pages" <?php selected('edit_pages', $options['can_read']); ?>><?php _e('Editors', 'dashboard-notepad'); ?></option>
			<option value="publish_posts" <?php selected('publish_posts', $options['can_read']); ?>><?php _e('Authors', 'dashboard-notepad'); ?></option>
			<option value="edit_posts" <?php selected('edit_posts', $options['can_read']); ?>><?php _e('Contributors', 'dashboard-notepad'); ?></option>
			<option value="read" <?php selected('read', $options['can_read']); ?>><?php _e('Subscribers', 'dashboard-notepad'); ?></option>
            <option value="guest" <?php selected('guest', $options['can_read']); ?>><?php _e('The Public', 'dashboard-notepad'); ?></option>
		</select>
        <label for="can_read"><?php _e( 'and above can <strong>read</strong> the notes.' , 'dashboard-notepad'); ?></label>
	</p>
    <p>
    <label><input id="autop" name="autop" type="checkbox" value="yes" <?php checked('yes', $options['autop']); ?> /> 
		<?php _e('Automatically add paragraphs when displaying the notes on the front end.', 'dashboard-notepad'); ?></label>
    </p>
<?php
}

// show dashboard notes on front end
function dashboard_notes() {
	$options = dashboard_notepad_widget_options();
	if (current_user_can($options['can_read']) || ($options['can_read'] == 'guest')) {
		echo '<div id="dashboard-notes">';
		if ($options['autop'] == 'yes')
			echo wpautop($options['notes']);
		else echo $options['notes'];
		echo '</div';
	}
}

add_shortcode('dashboard_notes', 'dashboard_notes');

// i18n
$plugin_dir = basename(dirname(__FILE__)). '/languages';
load_plugin_textdomain( 'DashboardNotepad', WP_PLUGIN_DIR.'/'.$plugin_dir, $plugin_dir );
?>