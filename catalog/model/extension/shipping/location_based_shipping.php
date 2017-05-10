<?php
class ModelExtensionShippingLocationBasedShipping extends Model {

    function getQuote($address) {
        $shipping_costs = $this->getCosts($address['country_id'], $address['zone_id']);
        $this->language->load('shipping/location_based_shipping');

        $method_data = array();
        $quote_data = array();
        foreach ($shipping_costs as $costs) {
            $cost = $costs['cost'];
            // Check if $costs is an array
            if (is_array($costs)) {
                $items = 0;

                foreach ($this->cart->getProducts() as $product) {
                    if ($product['shipping'])
                        $items += $product['quantity'];
                }
                $cart_total = $this->cart->getTotal();
                $rates = explode(',', $costs['cost']);

                foreach ($rates as $rate) {
                    $data = explode(':', $rate);

                    if ($cart_total <= $data[0]) {
                        if (isset($data[1])) {
                            $cost = $data[1];
                        }
                        break;
                    }
                }

                $quote_data[$costs['group']] = array(
                    'code'         => 'location_based_shipping.' . $costs['group'],
                    'title'        => $costs['title'][$this->config->get('config_language_id')],
                    'cost'         => $cost,
                    'tax_class_id' => $costs['tax_class_id'],
                    'text'         => $this->currency->format($this->tax->calculate($cost, $costs['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
                );
            }
        }

        $method_data = array(
            'code'       => 'location_based_shipping',
            'title'      => $this->language->get('text_title'),
            'quote'      => $quote_data,
            'sort_order' => $this->config->get('location_based_shipping_sort_order'),
            'error'      => false
        );

        return $method_data;
    }

    protected function getCosts($country_id, $zone_id) {
        $location_based_shipping_cost = $this->config->get('location_based_shipping_cost');
        $shipping = array();

        $result = array();
        foreach ($location_based_shipping_cost as $ship) {

            foreach ($ship as $cost) {
                if ($cost['country_id'] == $country_id && ($cost['zone_id'] == $zone_id || $cost['zone_id'] == 0)) {
                    $result[$cost['group']] = $cost;
                    break;
                }
            }
        }

        return $result;
    }

}
?>