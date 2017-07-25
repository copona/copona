<?php
class ControllerProductDownload extends Controller {
    public function __construct($registry)
    {
        parent::__construct($registry);


        $this->load->model('catalog/download');


    }
    public function index(){
        $arr = $this->model_catalog_download->getFreeDownload($this->request->get['download_id']);
        // If we have found FREE download, and its AVAILABLE in filesystem, then return
//        $file_path = '/home/www/castro.lv/www/public_html/storage/private/download/trenina_mk5_rinda.pdf.rzTg8KSLeIlnZoX6qQ3cBXqvqHMvlLNp';
        $file_path = DIR_DOWNLOAD . $arr['filename'];
//prd($arr );

        $file_name = pathinfo($file_path, PATHINFO_FILENAME);

        if(true) {
            $file_size = filesize($file_path);
            //prd($file_size);
            //header("Content-type: application/pdf");
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . ($arr['mask']? $arr['mask'] : $file_name) . '"');
            header('Content-Length: ' . $file_size);
            header_remove('Cache-Control');
            header_remove('Pragma');

            readfile($file_path, 'rb');

            return;
        }
    }

}