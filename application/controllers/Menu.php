<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

	// session percontroller
	public function __construct()
	{
		parent::__construct();
		check_id();
	}

	public function index()
	{
		$data['title'] = 'Menu Management';
		$data['user'] = $this->db->get_where('user', ['email' => 
				$this->session->userdata('email')])->row_array();

		$data['menu'] = $this->db->get('user_menu')->result_array();

		$this->form_validation->set_rules('menu', 'Menu', 'required');

		if ($this->form_validation->run() == false ) {

			$this->load->view('tempelate/header', $data);
			$this->load->view('tempelate/sidebar', $data);
			$this->load->view('tempelate/topbar', $data);
			$this->load->view('menu/index', $data);
			$this->load->view('tempelate/footer');

		} else {

			$this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
													  	New menu added
													  </div>');
			redirect('menu');
		}	 
		
	}

	public function submenu()
	{
		$data['title'] = 'Submenu Management';
		$data['user'] = $this->db->get_where('user', ['email' => 
				$this->session->userdata('email')])->row_array();

		$this->load->model('Menu_model', 'menu');

		$data['subMenu'] = $this->menu->getSubMenu();
		$data['menu'] = $this->db->get('user_menu')->result_array();

		$this->form_validation->set_rules('title', 'title', 'required');
		$this->form_validation->set_rules('menu_id', 'Menu', 'required');
		$this->form_validation->set_rules('url', 'URL', 'required');
		$this->form_validation->set_rules('icon', 'Icon', 'required');

			if($this->form_validation->run() == false) {
				$this->load->view('tempelate/header', $data);
				$this->load->view('tempelate/sidebar', $data);
				$this->load->view('tempelate/topbar', $data);
				$this->load->view('menu/submenu', $data);
				$this->load->view('tempelate/footer');
			} else {
				$data = [
					'title' => $this->input->post('title'),
					'menu_id' => $this->input->post('menu_id'),
					'url' => $this->input->post('url'),
					'icon' => $this->input->post('icon'),
					'is_active' => $this->input->post('is_active')
				];

				$this->db->insert('user_sub_menu', $data );
				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
													  			New Submenu added
													  	 </div>');
				redirect('menu/submenu');

			}
			
	}

}


?>