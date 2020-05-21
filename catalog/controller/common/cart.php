<?php
class ControllerCommonCart extends Controller {

    public function index() {
        $data = $this->load->language('common/cart');

        // // Totals
        // $this->load->model('extension/extension');
        //
        // $totals = array();
        // $taxes = $this->cart->getTaxes();
        // $total = 0;
        //
        // // Because __call can not keep var references so we put them into an array.
        // $total_data = array(
        //     'totals' => &$totals,
        //     'taxes'  => &$taxes,
        //     'total'  => &$total
        // );
        //
        // // Display prices
        // if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
        //     $sort_order = array();
        //
        //     $results = $this->model_extension_extension->getExtensions('total');
        //
        //     foreach ($results as $key => $value) {
        //         $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        //     }
        //
        //     array_multisort($sort_order, SORT_ASC, $results);
        //
        //     foreach ($results as $result) {
        //         if ($this->config->get($result['code'] . '_status')) {
        //             $this->load->model('extension/total/' . $result['code']);
        //
        //             // We have to put the totals in an array so that they pass by reference.
        //             $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
        //         }
        //     }
        //
        //     $sort_order = array();
        //
        //     foreach ($totals as $key => $value) {
        //         $sort_order[$key] = $value['sort_order'];
        //     }
        //
        //     array_multisort($sort_order, SORT_ASC, $totals);
        // }

        $data['totals'] = $this->cart->getTotals_azon();
        $total = $this->cart->getTotal();

        $data['text_empty'] = $this->language->get('text_empty');
        $data['text_cart'] = $this->language->get('text_cart');
        $data['text_checkout'] = $this->language->get('text_checkout');
        $data['text_recurring'] = $this->language->get('text_recurring');
        $data['text_items'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers'])
                ? count($this->session->data['vouchers']) : 0),
            $this->currency->format($total, $this->session->data['currency']));
        $data['text_loading'] = $this->language->get('text_loading');

        $data['button_remove'] = $this->language->get('button_remove');
        $data['template_name'] = $this->config->get('theme_default_directory') ? $this->config->get('theme_default_directory') : $this->config->get('config_template');
        $this->load->model('tool/image');
        $this->load->model('tool/upload');

        $data['products'] = $this->cart->getProducts();

        // Gift Voucher
        $data['vouchers'] = array();

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $key => $voucher) {
                $data['vouchers'][] = array(
                    'key'         => $key,
                    'description' => $voucher['description'],
                    'amount'      => $this->currency->format($voucher['amount'], $this->session->data['currency'])
                );
            }
        }

        $data['totals'] = $this->cart->getTotals_azon();

        $data['cart'] = $this->url->link('checkout/cart');
        $data['checkout'] = $this->url->link('checkout/checkout', '', true);
        $data['cart_total_numeric'] = $this->cart->getTotal();
        $data['cart_total'] = $this->currency->format( $data['cart_total_numeric'] , $this->session->data['currency']);


        return $this->load->view('checkout/easy_cart_top', $data);
    }

    public function info() {
        $this->response->setOutput($this->index());
    }

    public function getCartTotal() {
        $this->response->setOutput($this->cart->getCartTotal((int)$this->request->get('formatted')));
    }

}
