<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');		
	}

	public function index()
	{
		if($this->session->userdata('email')) {
			redirect('user');
		}


		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');

		if ($this->form_validation->run() == false ) {
			$data['title'] = 'Login Page';
			$this->load->view('tempelate/auth_header', $data);
			$this->load->view('auth/login');
			$this->load->view('tempelate/auth_footer');

			} else {
				// validasi diarahkan ke method _login
				$this->_login();
			}
		}

	private function _login()
	{
		
		$email 		= $this->input->post('email');
		$password 	= $this->input->post('password');

		$user = $this->db->get_where('user', ['email' => $email])->row_array();
		
		// mencari user
		if($user) {
		if($user['is_active'] == 1) {
		if (password_verify($password, $user['password'])) {
				$data = [ 
					'email' 	=> $user['email'],
					'role_id' 	=> $user['role_id']
					];

			$this->session->set_userdata($data);
				if ($user['role_id'] == 1) {
						redirect('admin');
					} else {
						redirect('user');
					}

					} else {
						// password login salah
						$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
														  	Password anda salah </div>');
						redirect('auth');
					}

					} else {
						// email tidak aktif (harus aktivasi)
						$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
														  	harap aktivasi email </div>');
						redirect('auth');
					}
				
					} else {
						// email tidak terdaftar
						$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
														  	Email tidak terdaftar </div>');
						redirect('auth');
					}
				}



	public function registration()
	{
		if($this->session->userdata('email')) {
			redirect('user');
		}

		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]',
			[
				'valid_email'	=> 'Email tidak valid',
				'is_unique'		=> 'Email sudah terdaftar'
			]);
		$this->form_validation->set_rules('password1', 'Password', 'required|trim|matches[password2]|min_length[6]', 
			[
				'matches' 		=> 'password tidak sama',
				'min_length' 	=> 'password min 6 karaker'
			]);
		$this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

		if ($this->form_validation->run() == false ) {
			$data['title'] = 'Registration Page';
			$this->load->view('tempelate/auth_header', $data);
			$this->load->view('auth/registration');
			$this->load->view('tempelate/auth_footer');
			} else {

				$email = $this->input->post('email', true);
				$data_registration = [
					'name' 			=> htmlspecialchars($this->input->post('name', true)),
					'email' 		=> htmlspecialchars($email),
					'image' 		=> 'default.jpg',
					'password' 		=> password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
					'role_id' 		=> 2,
					'is_active' 	=> 0,
					'date_created' 	=> time()
				];

			// siapkan token untuk user baru
			$token = base64_encode(random_bytes(32));
			$user_token = [
				'email' => $email,
				'token' => $token,
				'date_created' => time()
			];

			$this->db->insert('user', $data_registration);
			$this->db->insert('user_token', $user_token);

			$this->_sendEmail($token,'verify');

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
													  	Akun anda sudah terdaftar, silahkan aktivasi </div>');
				redirect('auth');
			}
		}

		// aktivasi akun email
		private function _sendEmail($token, $type)
		{
			$config = [
				'protocol' 	=> 'smtp',
				'smtp_host' => 'ssl://smtp.googlemail.com',
				'smtp_user' => 'riankima88@gmail.com',
				'smtp_pass' => 'korem173pvb',
				'smtp_port' => 465,
				'mailtype' => 'html',
				'charset'	=> 'utf-8',
				'newline'	=> "\r\n"
			];

			$this->load->library('email', $config);
			$this->email->initialize($config);

			$this->email->from('riankima88@gmail.com', 'pace');

			$this->email->to($this->input->post('email'));

				// token verify account
				if ($type == 'verify') {
					$this->email->subject('Account Verification');
					$this->email->message('Click for verification your Account : <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token ). '"> Actived </a>');

				//token reset password 
				} else if($type = 'forgot') {
					$this->email->subject('Reset Password');
					$this->email->message('Click for reset your password : <a href="' . base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token ). '"> Reset Password</a>');
				}
			

				if ($this->email->send()) {
				return true;
				} else {
					echo $this->email->print_debugger();
					die;
				}
			}

		public function verify()
		{
			$email = $this->input->get('email');
			$token = $this->input->get('token');

			$user = $this->db->get_where('user', ['email' => $email])->row_array();

			if ($user) {
				$user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

			if ($user_token) {

			// batas waktu token 
			if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
					$this->db->set('is_active', 1);
					$this->db->where('email', $email);
					$this->db->update('user');

					$this->db->delete('user_token', ['email' => $email]);	

					$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
													  	'. $email .' has been activated! please login </div>');
					redirect('auth');

					} else {

						// hapus user jika token user hangus
						$this->db->delete('user', ['email' => $email ]);
						$this->db->delete('user_token', ['email' => $email ]);

						$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
													  	token hangus </div>');
						redirect('auth');
						}

					} else {
						// gagal aktifasi jika token user salah
						$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
														  	aktifasi gagal ,token salah </div>');
						redirect('auth');
					}

					} else {
						// gagal aktifasi jika email user salah
						$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
															  	aktifasi gagal ,email salah </div>');
						redirect('auth');
					}
				}


		public function forgotPassword()
		{

			$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');

			if ($this->form_validation->run() == false) {
				$data['title'] = 'Forgot Password';
				$this->load->view('tempelate/auth_header', $data);
				$this->load->view('auth/forgot-password');
				$this->load->view('tempelate/auth_footer');
			} else {
				$email = $this->input->post('email');
				$user = $this->db->get_where('user', ['email' => $email, 'is_active' => 1])->row_array();

				if($user) {
					$token = base64_encode(random_bytes(32));
					$user_token = [
						'email' => $email,
						'token' => $token,
						'date_created' => time()
					];

					$this->db->insert('user_token', $user_token);
					$this->_sendEmail($token, 'forgot');

					$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
													  	please check your email to reset your password </div>');
					redirect('auth/forgotpassword');
				} else{
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
													  	email is not registed or not activation </div>');
					redirect('auth/forgotpassword');
				}
			}
		}
		

		public function resetpassword()
		{
			$email = $this->input->get('email');
			$token = $this->input->get('token');

			$user = $this->db->get_where('user', ['email' => $email])->row_array();

			if ($user) {
				$user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

			if ($user_token) {
				$this->session->set_userdata('reset_email', $email);
				$this->changePassword();

				// token password baru salah
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
													  	reset password fail,wrong token </div>');
					redirect('auth');
				}

				//email salah untuk ganti password 
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
													  	reset password fail,wrong email </div>');
				redirect('auth');
			}
		}

		public function changePassword()
		{
			if(!$this->session->userdata('reset_email')) {
				redirect('auth');
			}

			$this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[6]|matches[password2]');
			$this->form_validation->set_rules('password2', 'Password', 'required|trim|min_length[6]|matches[password1]');

			if ($this->form_validation->run() == false) {
				$data['title'] = 'Change Password';
				$this->load->view('tempelate/auth_header', $data);
				$this->load->view('auth/change-password');
				$this->load->view('tempelate/auth_footer');
			} else {
				$password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
				$email = $this->session->userdata('reset_email');

				$this->db->set('password', $password);
				$this->db->where('email', $email);
				$this->db->update('user');

				$this->session->unset_userdata('reset_email');

				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
													  	Password hass been change, please Login </div>');
				redirect('auth');
			}
			
		}


		public function logout()
		{
			$this->session->unset_userdata('email');
			$this->session->unset_userdata('role_id');

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
													  	anda berhasil logout </div>');
			redirect('auth');
		}

		public function blocked() 
		{
			$this->load->view('auth/blocked');
		}
			
}
