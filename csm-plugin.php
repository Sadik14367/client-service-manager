
<?php
/**
 * Plugin Name: Client Service Manager
 * Description: All-in-one admin-only client, services, invoice manager with manual/auto SMS & Email and renew workflow.
 * Version: 1.0
 * Author: Sadikur R. Mejan
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__).'includes/cpt.php';
require_once plugin_dir_path(__FILE__).'includes/metaboxes.php';
require_once plugin_dir_path(__FILE__).'includes/admin-menu.php';
require_once plugin_dir_path(__FILE__).'includes/invoices.php';
require_once plugin_dir_path(__FILE__).'includes/notifications.php';

// Initialize plugin classes
new CSM_CPT();
new CSM_Metaboxes();
new CSM_AdminMenu();
new CSM_Invoices();
new CSM_Notifications();
