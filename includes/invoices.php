<?php
if (!defined('ABSPATH')) exit;

class CSM_Invoices {
    public function __construct() {
        add_action('csm_create_invoice', [$this,'generate_invoice'], 10, 1);
    }

    public function generate_invoice($service_id) {
        $service = get_post($service_id);
        $client_id = get_post_meta($service_id,'_client_id',true);
        $client = get_post($client_id);
        if(!$service || !$client) return;

        $invoice_post = [
            'post_title' => 'Invoice for '.$client->post_title,
            'post_type' => 'csm_invoice',
            'post_status' => 'publish'
        ];
        $invoice_id = wp_insert_post($invoice_post);

        update_post_meta($invoice_id,'_service_id',$service_id);
        update_post_meta($invoice_id,'_client_id',$client_id);
    }
}
