<?php
class ModelExtensionShippingLocationBasedShipping extends Model {

    function getQuote($address) {
        $shipping_costs = $this->getCosts($address['country_id'], $address['zone_id']); // This is deceptive it's
        // geozone_id no country_id
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
                $rates = explode(',', $costs['rates']);

                foreach ($rates as $rate) {
                    $data = explode(':', $rate);

                    if ($cart_total >= $data[0]) {
                        if (isset($data[1])) {
                            $cost = $data[1];
                        }
                        // break;
                    }
                }
                $quote_data[$costs['group']] = array(
                    'code'         => 'location_based_shipping.' . $costs['group'],
                    'title'        => $costs['title'][$this->config->get('config_language_id')],
                    'cost'         => $cost,
                    'tax_class_id' => $costs['tax_class_id'],
                    'cost_with_tax'=> $this->currency->format($this->tax->calculate($cost, $costs['tax_class_id'],
                                      $this->config->get('config_tax')), $this->session->data['currency'],'',false),
                    'text'         => $this->currency->format($this->tax->calculate($cost, $costs['tax_class_id'],
                                      $this->config->get('config_tax')), $this->session->data['currency']),
                    'show_address' => (isset($costs['show_address']) && $costs['show_address'] ? 1 : 0),
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
        $result = array();
        foreach ($location_based_shipping_cost as $ship) {
            foreach ($ship as $cost) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" .
                    (int)$cost['geo_zone'] . "' 
                AND country_id = '" . (int)$country_id . "' AND (zone_id = '" . (int)$zone_id . "' OR zone_id = '0')");
                if(!empty($query->row)){
                    $result[] = array_merge($query->row,$cost);
                }
            }
         }
        return $result;
    }

}
?>