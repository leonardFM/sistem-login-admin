<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	// session percontroller
	public function __construct()
	{
		parent::__construct();
		check_id();
	}

	public function index()
	{
		$data['title'] = 'Dashboard';
		$data['user'] = $this->db->get_where('user', ['email' => 
				$this->session->userdata('email')])->row_array();
		
		$this->load->view('tempelate/header', $data);
		$this->load->view('tempelate/sidebar', $data);
		$this->load->view('tempelate/topbar', $data);
		$this->load->view('admin/index', $data);
		$this->load->view('tempelate/footer');
	}

	public function role()
	{
		$data['title'] = 'Role';
		$data['user'] = $this->db->get_where('user', ['email' => 
				$this->session->userdata('email')])->row_array();

		$data['role'] = $this->db->get('user_role')->result_array();
		
		$this->load->view('tempelate/header', $data);
		$this->load->view('tempelate/sidebar', $data);
		$this->load->view('tempelate/topbar', $data);
		$this->load->view('admin/role', $data);
		$this->load->view('tempelate/footer');
	}

	public function roleAccess($role_id)
	{
		$data['title'] = 'Role Access';
		$data['user'] = $this->db->get_where('user', ['email' => 
				$this->session->userdata('email')])->row_array();

		$data['role'] = $this->db->get_where('user_role',['id' => $role_id])->row_array();

		$this->db->where('id !=', 1);
		$data['menu'] = $this->db->get('user_menu')->result_array();
		
		$this->load->view('tempelate/header', $data);
		$this->load->view('tempelate/sidebar', $data);
		$this->load->view('tempelate/topbar', $data);
		$this->load->view('admin/roleaccess', $data);
		$this->load->view('tempelate/footer');
	}

	public function changeAccess()
	{
		$menu_id = $this->input->post('menuId');
		$role_id = $this->input->post('roleId');

		$data = [
			'role_id' => $role_id,
			'menu_id' => $menu_id
		];

		$result = $this->db->get_where('user_access_menu', $data);

		if($result->num_rows() < 1) {
			$this->db->insert('user_access_menu', $data);
		} else {
			$this->db->delete('user_access_menu', $data);
		}

		$this->session->set_flashdata('message', 
			'<div class="alert alert-success" role="alert">
				Access change 
			</div>');
	}



}

?>