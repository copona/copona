<?php
class ControllerExtensionModuleSimpleform extends Controller {
    private $error = array();
    private $data = array();
    private $post_values = array();

    public function __construct($params) {
        parent::__construct($params);
        $this->load->model('extension/module/simpleform');
        $this->data = array_merge($this->data, $this->language->load('extension/module/simpleform'));
        $this->data['lang'] = $this->config->get('config_language');

        $this->post_values = array(
            'name'      => "1|string|3",
            'surname'   => "1",
            'phone'     => "0",
            'email'     => "1|email",
            'regnumber' => "1",
        );

        // checking and setting default values against their defined
        // if defined and if required and if type
        foreach ($this->post_values as $key => $val) { //foreach defined values
            if (!$this->request->post) { //if it's NOT post
                $this->data[$key] = '';
            } else {

                $args = explode("|", $val);

                isset($args[1]) ? : $args[1] = 'string';
                isset($args[2]) ? : $args[2] = 3; // default MIN length.
                //pr($args);

                if (isset($this->request->post[$key]) && (!$args[0] || $this->validate($this->request->post[$key], $args[1], $args[2]) )) {
                    //if it's SET, must validate, and VALIDATES
                    $this->data[$key] = $this->request->post[$key];
                } elseif (isset($this->request->post[$key])) {
                    //if it's set, then it DOES not validates. So - error.
                    $this->error['error_' . $key] = 'error-' . $this->request->post[$key];
                    $this->data[$key] = $this->request->post[$key];
                } else {
                    // its not set. So, it's empty anyway
                    $this->data[$key] = '';
                }
            }
        }

        //pr($this->error['error_' . $key]);
    }

    public function index($setting) {


        if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
            $this->data['heading_title'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8');
            $this->data['html'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');

            //pr($this->error);
            $this->data += $this->error;
            if ($this->request->post && !$this->error) {
                $res = $this->model_extension_module_simpleform->saveFormData($this->request->post);
                //reset form data arr.
                foreach ($this->post_values as $key => $val) {
                    $this->data[$key] = '';
                }
            }
            return $this->load->view('extension/module/simpleform', $this->data);
        }
    }

    public function post() {

        $this->load->model('catalog/infocategory');
        $this->load->model('tool/image');

        if (isset($this->request->post) && $this->request->post && $this->validate()) {
            $this->load->model('information/infocategory');
            $result = $this->model_information_infocategory->saveFormData($this->request->post);

            if ($result) {
                $this->redirect($this->url->link('information/infocategory/success'));
            }
        }
        $infocategory_info = $this->model_catalog_infocategory->getCategories();

        $this->data['infocategories'] = array();
        foreach ($infocategory_info as $infocategory) {

            if ($infocategory['image']) {
                $image = $this->model_tool_image->resize($infocategory['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
            } else {
                $image = $this->model_tool_image->cropsize('no_image.jpg', $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
            }

            $this->data['infocategories'][] = array(
                'name'  => $infocategory['name'],
                'image' => $image,
                'pdf'   => $infocategory['banner_image'],
                'href'  => $this->url->link('information/infocategory', 'infocategory_id=' . $infocategory['category_id']),
            );
        }
    }

    protected function validate($data = '', $type = 'string', $min = 3) {
        //pr($min);
        switch ($type) {
            case 'string':
                if (utf8_strlen($data) < $min || utf8_strlen($data) > 256) {
                    return false;
                }
                break;
            case 'email':
                if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
                    return false;
                }
                break;
            case 'int':
                if ((int)$data == $data && $data > $min) {
                    return false;
                }
                break;
        }
        return true;
    }

}