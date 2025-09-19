<?php
if (!defined('ABSPATH')) exit;

class CSM_Metaboxes {
    public function __construct() {
        add_action('add_meta_boxes', [$this,'add_meta_boxes']);
        add_action('save_post', [$this,'save_meta'],10,2);
    }

    public function add_meta_boxes() {
        add_meta_box('csm_client_info','Client Info', [$this,'render_client_meta'],'csm_client','normal','high');
        add_meta_box('csm_service_info','Service Info', [$this,'render_service_meta'],'csm_service','normal','high');
        add_meta_box('csm_invoice_actions','Invoice Actions', [$this,'render_invoice_meta'],'csm_invoice','side','default');
    }

    public function render_client_meta($post) {
        wp_nonce_field('csm_save_meta','csm_meta_nonce');
        $mobile = get_post_meta($post->ID,'_client_mobile',true);
        $email = get_post_meta($post->ID,'_client_email',true);
        $address = get_post_meta($post->ID,'_client_address',true);

        echo '<p><label>Mobile<br><input type="text" name="_client_mobile" value="'.esc_attr($mobile).'" class="widefat"></label></p>';
        echo '<p><label>Email<br><input type="email" name="_client_email" value="'.esc_attr($email).'" class="widefat"></label></p>';
        echo '<p><label>Address<br><textarea name="_client_address" class="widefat">'.esc_textarea($address).'</textarea></label></p>';
    }

    public function render_service_meta($post) {
        wp_nonce_field('csm_save_meta','csm_meta_nonce');
        $client_id = get_post_meta($post->ID,'_client_id',true);
        $price = get_post_meta($post->ID,'_service_price',true);
        $order_date = get_post_meta($post->ID,'_order_date',true);
        $domain_expiry = get_post_meta($post->ID,'_domain_expiry',true);
        $hosting_expiry = get_post_meta($post->ID,'_hosting_expiry',true);
        $service_type = wp_get_post_terms($post->ID,'csm_service_type',['fields'=>'names']);
        $service_type = isset($service_type[0]) ? $service_type[0] : '';

        $clients = get_posts(['post_type'=>'csm_client','numberposts'=>-1]);
        echo '<p><label>Client<br><select name="_client_id" class="widefat"><option value="">-- Select Client --</option>';
        foreach($clients as $c){
            $sel = ($client_id==$c->ID)?'selected':'';
            echo '<option value="'.$c->ID.'" '.$sel.'>'.esc_html($c->post_title).'</option>';
        }
        echo '</select></label></p>';

        echo '<p><label>Price<br><input type="text" name="_service_price" value="'.esc_attr($price).'" class="widefat"></label></p>';
        echo '<p><label>Order Date<br><input type="date" name="_order_date" value="'.esc_attr($order_date).'" class="widefat"></label></p>';
        echo '<p><label>Domain Expiry<br><input type="date" name="_domain_expiry" value="'.esc_attr($domain_expiry).'" class="widefat"></label></p>';
        echo '<p><label>Hosting Expiry<br><input type="date" name="_hosting_expiry" value="'.esc_attr($hosting_expiry).'" class="widefat"></label></p>';
        echo '<p><label>Service Type<br><input type="text" name="_service_type" value="'.esc_attr($service_type).'" class="widefat"></label></p>';
        echo '<p><label><input type="checkbox" name="_create_invoice" value="1"> Create invoice now</label></p>';
    }

    public function render_invoice_meta($post){
        $pdf_id = get_post_meta($post->ID,'_invoice_pdf_id',true);
        if ($pdf_id){
            $url = wp_get_attachment_url($pdf_id);
            echo '<p><a href="'.esc_url($url).'" target="_blank">Download PDF</a></p>';
        }
        echo '<p>Generate invoice PDF from data or attach existing file.</p>';
    }

    public function save_meta($post_id, $post){
        if (!isset($_POST['csm_meta_nonce']) || !wp_verify_nonce($_POST['csm_meta_nonce'],'csm_save_meta')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        if ($post->post_type=='csm_client'){
            update_post_meta($post_id,'_client_mobile',sanitize_text_field($_POST['_client_mobile']??''));
            update_post_meta($post_id,'_client_email',sanitize_email($_POST['_client_email']??''));
            update_post_meta($post_id,'_client_address',sanitize_textarea_field($_POST['_client_address']??''));
        }

        if ($post->post_type=='csm_service'){
            update_post_meta($post_id,'_client_id',intval($_POST['_client_id']??0));
            update_post_meta($post_id,'_service_price',sanitize_text_field($_POST['_service_price']??''));
            update_post_meta($post_id,'_order_date',sanitize_text_field($_POST['_order_date']??''));
            update_post_meta($post_id,'_domain_expiry',sanitize_text_field($_POST['_domain_expiry']??''));
            update_post_meta($post_id,'_hosting_expiry',sanitize_text_field($_POST['_hosting_expiry']??''));
            if (!empty($_POST['_service_type'])){
                wp_set_post_terms($post_id,[sanitize_text_field($_POST['_service_type'])],'csm_service_type',true);
            }
            if (!empty($_POST['_create_invoice'])){
                do_action('csm_create_invoice',$post_id);
            }
        }
    }
}
