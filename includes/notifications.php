<?php
if (!defined('ABSPATH')) exit;

class CSM_Notifications {
    public function __construct() {
        add_action('csm_send_manual_notification', [$this,'send_manual']);
        add_action('csm_check_expiry', [$this,'send_expiry_notifications']);
    }

    public function send_manual($msg) {
        $clients = get_posts(['post_type'=>'csm_client','numberposts'=>-1]);
        foreach($clients as $client){
            $mobile = get_post_meta($client->ID,'_client_mobile',true);
            $email = get_post_meta($client->ID,'_client_email',true);
            if($mobile) $this->send_sms($mobile,$msg);
            if($email) wp_mail($email,'Notification',$msg);
        }
    }

    public function send_expiry_notifications() {
        $services = get_posts(['post_type'=>'csm_service','numberposts'=>-1]);
        foreach($services as $s){
            $client_id = get_post_meta($s->ID,'_client_id',true);
            $client = get_post($client_id);
            if(!$client) continue;
            $msg = '';
            $today = date('Y-m-d');
            $domain_expiry = get_post_meta($s->ID,'_domain_expiry',true);
            $hosting_expiry = get_post_meta($s->ID,'_hosting_expiry',true);

            if($domain_expiry && $domain_expiry <= $today) $msg .= "Your domain has expired.\n";
            if($hosting_expiry && $hosting_expiry <= $today) $msg .= "Your hosting has expired.\n";

            if($msg){
                $mobile = get_post_meta($client->ID,'_client_mobile',true);
                $email = get_post_meta($client->ID,'_client_email',true);
                if($mobile) $this->send_sms($mobile,$msg);
                if($email) wp_mail($email,'Expiry Notification',$msg);
            }
        }
    }

    private function send_sms($number,$message){
        // Placeholder: integrate your SMS gateway API here
    }
}
