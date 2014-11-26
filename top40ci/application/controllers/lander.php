<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lander extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('template/header');
        $this->load->view('lander');
        $this->load->view('template/footer');
    }


}
