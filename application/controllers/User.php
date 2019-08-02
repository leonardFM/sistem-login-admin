<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	// session percontroller
	public function __construct()
	{
		parent::__construct();
		check_id();
	}

	public function index()
	{
		$data['title'] = 'My Profile';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		
		$this->load->view('tempelate/header', $data);
		$this->load->view('tempelate/sidebar', $data);
		$this->load->view('tempelate/topbar', $data);
		$this->load->view('user/index', $data);
		$this->load->view('tempelate/footer');
	}

	public function edit()
	{
		$data['title'] = 'Edit Profile';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

		$this->form_validation->set_rules('name', 'Full Name', 'required|trim');

		if($this->form_validation->run() == false ) {

			$this->load->view('tempelate/header', $data);
			$this->load->view('tempelate/sidebar', $data);
			$this->load->view('tempelate/topbar', $data);
			$this->load->view('user/edit', $data);
			$this->load->view('tempelate/footer');
		} else {

			$name = $this->input->post('name');
			$email = $this->input->post('email');

			// pengecekkan jika ada gambar yang di upload

			$upload_image = $_FILES['image']['name'];

			if($upload_image) {
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']     = '2048';
				$config['upload_path'] = './assets/img/profile/';

				$this->load->library('upload', $config);

				if($this->upload->do_upload('image')) {
					$old_image = $data['user']['image'];
					if($old_image != 'default.jpg') {
						unlink(FCPATH . 'assets/img/profile/' . $old_image);
					}



					$new_image = $this->upload->data('file_name');
					$this->db->set('image', $new_image);
				} else {
					echo $this->upload->display_errors();
				}
			}

			$this->db->set('name', $name);
			$this->db->where('email', $email);
			$this->db->update('user');

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
													  	your profil has been updated
													  </div>');
					redirect('user');
		}
	}

	public function changePassword()
	{
		$data['title'] = 'Change Password';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

		$this->form_validation->set_rules('current_password', 'Change Password', 'required|trim');
		$this->form_validation->set_rules('new_password1', 'New Password', 'required|trim|min_length[5]|matches[new_password2]');
		$this->form_validation->set_rules('new_password2', 'Confirm Password', 'required|trim|min_length[5]|matches[new_password1]');
		
		if($this->form_validation->run() == false) {
			$this->load->view('tempelate/header', $data);
			$this->load->view('tempelate/sidebar', $data);
			$this->load->view('tempelate/topbar', $data);
			$this->load->view('user/changepassword', $data);
			$this->load->view('tempelate/footer');
		} else {

				// jika input password lama salah (errors)
			$current_password = $this->input->post('current_password');
			$new_password = $this->input->post('new_password1');
			if(!password_verify($current_password, $data['user']['password'])) {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
													  	Wrong Current Password
													  </div>');
					redirect('user/changepassword');

			} else {

				// jika password lama dan baru sama (errors)
				if($current_password == $new_password) {
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
													  	new password cannot be the same as current password
													  </div>');
					redirect('user/changepassword');

				} else {
					// jika password oke bikin password baru (success)  
					$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

					$this->db->set('password', $password_hash);
					$this->db->where('email', $this->session->userdata('email'));
					$this->db->update('user');

					$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
													  	Password Change!
													  </div>');
					redirect('user/changepassword');
				}
			}
		}
		
	}

}

?>