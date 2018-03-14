<?php
class ControllerExtensionModuleAccount extends Controller {

    public function index() {
        $data = $this->load->language('extension/module/account');
        $data['heading_title'] = $this->language->get('heading_title');
        $this->load->model('account/wishlist');

        $modules_any = [
            'wishlist',
            'return',
        ];

        $modules_guest = [
            'register',
            'login',
            'forgotten',
        ];

        $modules_logged = [
            'account',
            'edit',
            'password',
            'address',
            'order',
            'download',
            'reward',
            'transaction',
            'newsletter',
            'recurring',
        ];

        if($this->customer->isLogged()) {
            foreach($modules_logged as $module) {
                $data['links'][$module] = [
                    'href' => $this->url->link('account/' . $module),
                    'name'  => $data["text_" . $module],
                    'status'  => true, // set to "false" in Hook, if you need to turn output off.
                ];
            }
        } else {
            foreach($modules_guest as $module) {
                $data['links'][$module] = [
                    'href' => $this->url->link('account/' . $module),
                    'name'  => $data["text_" . $module],
                    'status'  => true, // set to "false" in Hook, if you need to turn output off.
                ];
            }
        }

        foreach($modules_any as $module) {
            $data['links'][$module] = [
                'href' => $this->url->link('account/' . $module),
                'name'  => ($module === 'wishlist')? sprintf($data["text_" . $module], $this->model_account_wishlist->getTotalWishlist()) : $data["text_" . $module],
                'status'  => true, // set to "false" in Hook, if you need to turn output off.
            ];
        }

        $module = 'logout';
        if($this->customer->isLogged()) {
            $data['links'][$module] = [
                'href'   => $this->url->link('account/' . $module),
                'name'   => $data["text_" . $module],
                'status' => true, // set to "false" in Hook, if you need to turn output off.
            ];
        }


        $this->hook->getHook('extension/module/account/after', $data);

        return $this->load->view('extension/module/account', $data);
    }

}