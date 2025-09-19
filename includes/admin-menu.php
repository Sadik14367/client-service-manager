<?php
if (!defined('ABSPATH')) exit;

class CSM_AdminMenu {
    public function __construct() {
        add_action('admin_menu', [$this,'register_menu']);
        add_action('admin_enqueue_scripts', [$this,'enqueue_assets']);
    }

    public function register_menu() {
        add_menu_page('Client Manager', 'Client Manager', 'manage_options', 'csm_dashboard', [$this,'dashboard_page'], 'dashicons-admin-users', 6);
        add_submenu_page('csm_dashboard','Send Notification','Send Notification','manage_options','csm_send_notification',[$this,'notification_page']);
    }

    public function dashboard_page() {
        echo '<div class="wrap"><h1>Client Service Manager Dashboard</h1>';
        echo '<p>Manage clients, services, invoices, and notifications from here.</p></div>';
    }

    public function notification_page() {
        echo '<div class="wrap"><h1>Send Notifications</h1>';
        echo '<p>Send SMS or Email to clients.</p>';
        echo '<form method="post">';
        echo '<textarea name="csm_message" placeholder="Your message..." style="width:100%;height:100px;"></textarea>';
        echo '<p><input type="submit" name="send_notification" class="button button-primary" value="Send"></p>';
        echo '</form></div>';

        if(isset($_POST['send_notification'])){
            $msg = sanitize_textarea_field($_POST['csm_message']);
            do_action('csm_send_manual_notification',$msg);
            echo '<div class="updated notice"><p>Notification sent!</p></div>';
        }
    }

    public function enqueue_assets($hook) {
        if(strpos($hook,'csm')===false) return;
        wp_enqueue_style('csm_admin_css',plugins_url('../assets/admin.css',__FILE__));
        wp_enqueue_script('csm_admin_js',plugins_url('../assets/admin.js',__FILE__),['jquery'],'1.0',true);
    }
}
