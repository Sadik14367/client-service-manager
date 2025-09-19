<?php
if (!defined('ABSPATH')) exit;
$client_id = get_post_meta($post->ID,'_client_id',true);
$service_id = get_post_meta($post->ID,'_service_id',true);
$client = get_post($client_id);
$service = get_post($service_id);

?>
<h2>Invoice</h2>
<p><strong>Client:</strong> <?php echo esc_html($client->post_title); ?></p>
<p><strong>Service:</strong> <?php echo esc_html($service->post_title); ?></p>
<p><strong>Price:</strong> <?php echo esc_html(get_post_meta($service_id,'_service_price',true)); ?></p>
<p><strong>Order Date:</strong> <?php echo esc_html(get_post_meta($service_id,'_order_date',true)); ?></p>
<p><strong>Domain Expiry:</strong> <?php echo esc_html(get_post_meta($service_id,'_domain_expiry',true)); ?></p>
<p><strong>Hosting Expiry:</strong> <?php echo esc_html(get_post_meta($service_id,'_hosting_expiry',true)); ?></p>
