<?php

class ModelToolPdf extends Model
{
    /**
     * @author Arnis Juraga <arnis.juraga@gmail.com>
     *
     * @param string $from_email
     * @param string $to_email
     * @param string $subject
     * @param array $data
     * @param string $template Loads template from $template path, or sends plain text, if no template is specified.
     * @param int $store_id
     * @param string $store_name taken from store value, if set.
     */
    public function generate($html = '', $filename = '') {


        // $html_message = $this->load->view('mail/order', $data);

        $html_message = empty($html_message) ?  "<h1>Hello, world</h1>" : '' ;


        $mpdf = new \Mpdf\Mpdf();
        $mpdf->writeHTML($html_message);
        if($filename) {
            $mpdf->Output(DIR_LOGS . $filename );
        }
        $mpdf->Output();


    }
}





