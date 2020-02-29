<?php
class ControllerApiPdf extends Controller {



    public function index() {
      $this->load->model('tool/pdf') ;
      $this->model_tool_pdf->generate();


    }

}