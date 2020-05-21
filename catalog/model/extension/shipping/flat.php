<?php
class ModelExtensionShippingFlat extends Model {

    function getQuote($address) {
        $this->load->language('extension/shipping/flat');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('flat_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

        if (!$this->config->get('flat_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        $flat_description = $this->config->get('flat_description');

        $method_data = array();


        if ($this->cart->getTotal() > $this->config->get('flat_free_from')) {
            $cost = 0; // Fre delivery from.
        } else {
            $cost = $this->config->get('flat_cost');
        }

        if ($this->cart->getTotal() < $this->config->get('flat_available_from')) {
            $status = 0;
        }


        if ($status) {
            $quote_data = array();

            $quote_data['flat'] = array(
                'code'         => 'flat.flat',
                'title'        => !empty($flat_description[$this->config->get('config_language_id')]['title'])
                  ? $flat_description[$this->config->get('config_language_id')]['title']
                  : $this->language->get('text_description'),
                'cost'         => $cost,
                'cost_with_tax'=> $this->currency->format($this->tax->calculate($cost, $this->config->get('flat_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'],"",false),
                'tax_class_id' => $this->config->get('flat_tax_class_id'),
                'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('flat_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
            );

            $method_data = array(
                'code'       => 'flat',
                'title'      => $this->language->get('text_title'),
                'quote'      => $quote_data,
                'sort_order' => $this->config->get('flat_sort_order'),
                'error'      => false
            );
        }

        return $method_data;
    }

}
