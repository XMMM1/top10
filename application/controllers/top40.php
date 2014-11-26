<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Top40 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('top40_model');
    }

    public function index()
    {
        $this->top();
    }

    public function top()
    {
        $this->load->view('template/header');
        $data['top40']=$this->top40_model->get_top('singles');
        $this->load->view('top40/top',$data);
        $this->load->view('template/footer');
    }
}
