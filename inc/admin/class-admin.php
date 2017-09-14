<?php

namespace Nds_Admin_Form_Demo\Inc\Admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://www.nuancedesignstudio.in
 * @since      1.0.0
 *
 * @author    Karan NA Gupta
 */
class Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	/**
	 * The text domain of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_text_domain    The text domain of this plugin.
	 */
	private $plugin_text_domain;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name	The name of this plugin.
	 * @param    string $version	The version of this plugin.
	 * @param	 string $plugin_text_domain	The text domain of this plugin
	 */
	public function __construct( $plugin_name, $version, $plugin_text_domain ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_text_domain = $plugin_text_domain;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nds-admin-form-demo-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
		$params = array ( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
		wp_enqueue_script( 'nds_ajax_handle', plugin_dir_url( __FILE__ ) . 'js/nds-admin-form-demo-ajax-handler.js', array( 'jquery' ), $this->version, false );				
		wp_localize_script( 'nds_ajax_handle', 'params', $params );		

	}
	
	/**
	 * Callback for the admin menu
	 * 
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
				
		add_menu_page(	__( 'Admin Form Demo', $this->plugin_text_domain ), //page title
						__( 'Admin Form Demo', $this->plugin_text_domain ), //menu title
						'manage_options', //capability
						$this->plugin_name //menu_slug
					);
		
		 // Add a submenu page and save the returned hook suffix.
		$html_form_page_hook = add_submenu_page( 
									$this->plugin_name, //parent slug
									__( 'Admin Form Demo', $this->plugin_text_domain ), //page title
									__( 'HTML Form Submit', $this->plugin_text_domain ), //menu title
									'manage_options', //capability
									$this->plugin_name, //menu_slug
									array( $this, 'html_form_page_content' ) //callback for page content
									);
		
		// Add a submenu page and save the returned hook suffix.
		$ajax_form_page_hook = add_submenu_page( 
									$this->plugin_name, //parent slug
									__( 'Admin Form Demo', $this->plugin_text_domain ), //page title
									__( 'Ajax Form Sumit', $this->plugin_text_domain ), //menu title
									'manage_options', //capability
									$this->plugin_name . '-ajax', //menu_slug
									array( $this, 'ajax_form_page_content' ) //callback for page content
									);
		
		/*
		 * The $page_hook_suffix can be combined with the load-($page_hook) action hook
		 * https://codex.wordpress.org/Plugin_API/Action_Reference/load-(page) 
		 * 
		 * The callback below will be called when the respective page is loaded
		 */				
		add_action( 'load-'.$html_form_page_hook, array( $this, 'loaded_html_form_submenu_page' ) );
		add_action( 'load-'.$ajax_form_page_hook, array( $this, 'loaded_ajax_form_submenu_page' ) );
	}
	
	/*
	 * Callback for the add_submenu_page action hook
	 * 
	 * The plugin's HTML form is loaded from here
	 * 
	 * @since	1.0.0
	 */
	public function html_form_page_content() {
		//show the form
		include_once( 'views/partials-html-form-view.php' );
	}
	
	/*
	 * Callback for the add_submenu_page action hook
	 * 
	 * The plugin's HTML Ajax is loaded from here
	 * 
	 * @since	1.0.0
	 */
	public function ajax_form_page_content() {
		include_once( 'views/partials-ajax-form-view.php' );
	}	
	
	/*
	 * Callback for the load-($html_form_page_hook)	 
	 * Called when the plugin's submenu HTML form page is loaded
	 * 
	 * @since	1.0.0
	 */
	public function loaded_html_form_submenu_page() {
		// called when the particular page is loaded.
	}
	
	/*
	 * Callback for the load-($ajax_form_page_hook)	 
	 * Called when the plugin's submenu Ajax form page is loaded
	 * 
	 * @since	1.0.0
	 */
	public function loaded_ajax_form_submenu_page() {
		// called when the particular page is loaded.		
	}
	
	/**
	 * 
	 * @since    1.0.0
	 */
	public function the_form_response() {
		
		if( isset( $_POST['nds_add_user_meta_nonce'] ) && wp_verify_nonce( $_POST['nds_add_user_meta_nonce'], 'nds_add_user_meta_form_nonce') ) {
			$nds_user_meta_key = sanitize_key( $_POST['nds']['user_meta_key'] );
			$nds_user_meta_value = sanitize_text_field( $_POST['nds']['user_meta_value'] );
			$nds_user =  get_user_by( 'login',  $_POST['nds']['user_select'] );
			$nds_user_id = absint( $nds_user->ID ) ;
			
			// server processing logic
			
			if( isset( $_POST['ajaxrequest'] ) && $_POST['ajaxrequest'] === 'true' ) {
				// server response
				echo '<pre>';					
					print_r( $_POST );
				echo '</pre>';				
				wp_die();
            }

			// server response
			$admin_notice = "success";
			$this->custom_redirect( $admin_notice, $_POST );
			exit;
		}			
		else {
			wp_die( __( 'Invalid nonce specified', $this->plugin_name ), __( 'Error', $this->plugin_name ), array(
						'response' 	=> 403,
						'back_link' => 'admin.php?page=' . $this->plugin_name,

				) );
		}
	}

	/**
	 * Redirect
	 * 
	 * @since    1.0.0
	 */
	public function custom_redirect( $admin_notice, $response ) {
		wp_redirect( esc_url_raw( add_query_arg( array(
									'nds_admin_add_notice' => $admin_notice,
									'nds_response' => $response,
									),
							admin_url('admin.php?page='. $this->plugin_name ) 
					) ) );

	}


	/**
	 * Print Admin Notices
	 * 
	 * @since    1.0.0
	 */
	public function print_plugin_admin_notices() {              
		  if ( isset( $_REQUEST['nds_admin_add_notice'] ) ) {
			if( $_REQUEST['nds_admin_add_notice'] === "success") {
				$html =	'<div class="notice notice-success is-dismissible"> 
							<p><strong>The request was successful. </strong></p><br>';
				$html .= '<pre>' . htmlspecialchars( print_r( $_REQUEST['nds_response'], true) ) . '</pre></div>';
				echo $html;
			}
			
			// handle other types of form notices

		  }
		  else {
			  return;
		  }

	}


}