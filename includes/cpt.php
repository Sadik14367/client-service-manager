<?php
if (!defined('ABSPATH')) exit;

class CSM_CPT {
    public function __construct() {
        add_action('init', [$this,'register_cpts']);
        add_action('init', [$this,'register_taxonomies']);
    }

    public function register_cpts() {
        // Clients CPT
        register_post_type('csm_client', [
            'label'=>'Clients',
            'public'=>false,
            'show_ui'=>true,
            'supports'=>['title','editor'],
            'menu_icon'=>'dashicons-businessperson'
        ]);
        // Services CPT
        register_post_type('csm_service', [
            'label'=>'Services',
            'public'=>false,
            'show_ui'=>true,
            'supports'=>['title','editor'],
            'menu_icon'=>'dashicons-list-view'
        ]);
        // Invoices CPT
        register_post_type('csm_invoice', [
            'label'=>'Invoices',
            'public'=>false,
            'show_ui'=>true,
            'supports'=>['title','editor'],
            'menu_icon'=>'dashicons-media-document'
        ]);
    }

    public function register_taxonomies() {
        register_taxonomy('csm_service_type','csm_service', [
            'label'=>'Service Types',
            'public'=>false,
            'show_ui'=>true
        ]);
    }
}
