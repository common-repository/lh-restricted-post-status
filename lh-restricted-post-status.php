<?php
/**
 * Plugin Name: LH Restricted Post Status
 * Plugin URI: https://lhero.org/portfolio/lh-restricted-post-status/
 * Description: Creates an restricted post status. Admins can set which roles have access to the content
 * Author: Peter Shaw
 * Author URI: https://shawfactor.com
 * Version: 1.03
 * License: GPL v3 (http://www.gnu.org/licenses/gpl.html)
 * Text Domain: lh_restricted_post_status
 * Domain Path: /languages/
*/

if (!class_exists('WP_Statuses')) {
    
    include_once("includes/wp-statuses.php");
    
}


if (!class_exists('LH_Restricted_post_status_plugin')) {


class LH_Restricted_post_status_plugin {

var $opt_name = 'lh_restricted_post_status-options';
var $hidden_field_name = 'lh_restricted_post_status-submit_hidden';
var $actionable_roles_field_name = 'lh_restricted_post_status-actionable_roles_field_name';
var $newstatusname = 'restrict';
var $newstatuslabel = 'restricted';
var $namespace = 'lh_restricted_post_status';


var $viewcapability = 'read_restricted_posts';


var $filename;
var $options;

private function return_roles() {
    $editable_roles = get_editable_roles();
    foreach ($editable_roles as $role => $details) {
        $sub['role'] = esc_attr($role);
        $sub['name'] = translate_user_role($details['name']);
        $roles[] = $sub;
    }
    return $roles;
}


private function add_custom_cap($rolestoadd, $cap){

$grant = true; 
$roles = $this->return_roles();


foreach ($roles as $role ) {

$action = get_role( $role['role'] );

if (in_array($role['role'], $rolestoadd)) {

$action->add_cap($cap);

echo $role['role']." has had access added <br/>";

} else {

$action->remove_cap($cap);

echo $role['role']." has had access removed <br/>";

}

}



}


public function current_user_can_view() {
	/**
	 * Default capability to grant ability to view status content (if the status is set to non public)
	 *
	 * @since 0.3.0
	 *
	 * @return string
	 */



	return current_user_can($this->viewcapability);


}




public function plugin_menu() {
add_options_page('LH Restricted Post Status Options', 'Restricted Post Status', 'manage_options', $this->filename, array($this,"plugin_options")); 
}


public function plugin_options() {

if (!current_user_can('manage_options')){

wp_die( __('You do not have sufficient permissions to access this page.', $this->namespace) );

}


if( isset($_POST[ $this->hidden_field_name ]) && $_POST[ $this->hidden_field_name ] == 'Y' ) {


if (isset($_POST[$this->actionable_roles_field_name])){

//need to add some appropriate sanity check here

$options[ $this->actionable_roles_field_name ] = $_POST[ $this->actionable_roles_field_name ];


}

if (update_option( $this->opt_name, $options )){

$this->options = get_option($this->opt_name);

$this->add_custom_cap($this->options[ $this->actionable_roles_field_name ], 'read_restricted_posts');


?>
<div class="updated"><p><strong><?php _e('Accessible role updated', $this->namespace ); ?></strong></p></div>
<?php



}


}


// Now display the settings editing screen

include ('partials/option-settings.php');


}

public function register_status() {
    
    
    $public = $this->current_user_can_view();
    $posttypes = get_post_types( array('public'   => true ), 'names' );
    
    
        	register_post_status( $this->newstatusname, array(
		'label'                     => _x( 'Restricted', 'post status label', $this->namespace ),
		'public'                    => $public,
		'label_count'               => _n_noop( 'Restricted <span class="count">(%s)</span>', 'Restricted <span class="count">(%s)</span>', $this->namespace  ),
		'post_type'                 => $posttypes, // Define one or more post types the status can be applied to.
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'show_in_metabox_dropdown'  => true,
		'show_in_inline_dropdown'   => true,
		'dashicon'                  => 'dashicons-yes',
	) );
    
}


function display_post_state( $states ) {
     global $post;
     $arg = get_query_var( 'post_status' );
     if($arg != $this->newstatusname){
          if($post->post_status == $this->newstatusname){
               return array(ucwords($this->newstatuslabel));
          }
     }
    return $states;
}


// add a settings link next to deactive / edit
public function add_settings_link( $links, $file ) {

	if( $file == $this->filename ){
		$links[] = '<a href="'. admin_url( 'options-general.php?page=' ).$this->filename.'">Settings</a>';
	}
	return $links;
}

public function lh_private_content_login_status_filter($statuses){

if (!in_array('restrict', $statuses)){
    
$statuses[] = 'restrict';

}

return $statuses;

}

public function __construct() {

$this->options = get_option($this->opt_name);
$this->filename = plugin_basename( __FILE__ );


add_action( 'init', array($this,"register_status") );

add_filter( 'display_post_states', array($this,"display_post_state"));

add_action('admin_menu', array($this,"plugin_menu"));

add_filter('plugin_action_links', array($this,"add_settings_link"), 10, 2);

//filter to add the role to the LH Private Content Login statuses
add_filter('lh_private_content_login_status_filter', array($this,"lh_private_content_login_status_filter"), 10, 1);


}


}

$lh_restricted_post_status_instance = new LH_Restricted_post_status_plugin();

}
?>