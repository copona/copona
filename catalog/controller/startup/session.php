<?php
class ControllerStartupSession extends Controller {
    public function index() {
        if (isset($this->request->get['token']) && isset($this->request->get['route']) && substr($this->request->get['route'], 0, 4) == 'api/') {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "api_session` WHERE TIMESTAMPADD(HOUR, 1, date_modified) < NOW()");

            $query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "api` `a` LEFT JOIN `" . DB_PREFIX . "api_session` `as` ON (a.api_id = as.api_id) LEFT JOIN " . DB_PREFIX . "api_ip `ai` ON (as.api_id = ai.api_id) WHERE a.status = '1' AND as.token = '" . $this->db->escape($this->request->get['token']) . "' AND ai.ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

            if ($query->num_rows) {
                $this->session->start('api', $query->row['session_id']);
                // keep the session alive
                $this->db->query("UPDATE `" . DB_PREFIX . "api_session` SET date_modified = NOW() WHERE api_session_id = '" . (int)$query->row['api_session_id'] . "'");
            }
        } else {

            // // "default", because it's a default value, and is passed only for clearance here.
            // $session_name = 'default';
            // $this->session->start($session_name);
            //
            // // 2h by default
            //
            // setcookie( $session_name, $this->session->getId(), time() + Config::get('session.session_timeout'), ini_get('session.cookie_path'), ini_get('session.cookie_domain'));
            //
            // if( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
            //     $IParray=array_values(array_filter(explode(',',$_SERVER['HTTP_X_FORWARDED_FOR'])));
            //     $ip_address = end($IParray);
            // } else if(!empty($_SERVER['REMOTE_ADDR'])) {
            //     $ip_address = $_SERVER['REMOTE_ADDR'];
            // } else {
            //     $ip_address = '';
            // }
            //
            // $this->session->data['ip_address'] = $ip_address;
        }
    }
}