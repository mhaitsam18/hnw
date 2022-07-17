<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent:: __construct();
		$this->load->library('form_validation');
		date_default_timezone_set('Asia/Jakarta');
	}
	public function index()
	{
		$data['link'] = 'Auth';
		$data['title'] = 'Beranda';
		if ($this->session->userdata('email')) {
			$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		}
		$this->load->view('templates/header', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('auth/index', $data);
		$this->load->view('templates/footer', $data);
	}

	public function login($url ='')
	{
		if ($this->input->get('url')) {
			$data['url'] = $this->input->get('url');
		} else{
			$data['url'] = '';
		}
		if ($this->session->userdata('email')) {
			$this->session->set_flashdata('message', 'Anda sudah login');
			redirect('Auth/');
		}
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		if ($this->form_validation->run() == false) {
			$data['title'] = 'User Log In';
			$data['link'] = 'Auth';
			if ($this->session->userdata('email')) {
				$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
			}
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/login', $data);
			$this->load->view('templates/auth_footer', $data);
		} else{
			$this->_login($url);
		}
	}

	private function _login($url='')
	{
		if ($this->session->userdata('email')) {
			redirect('Home');
		}
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$user = $this->db->get_where('user', ['email' => $email])->row_array();
		if ($user) {
			if ($user['is_active'] ==  1) {
				if (password_verify($password, $user['password'])) {
					$data = [
						'email' => $user['email'],
						'role_id' => $user['role_id']
					];
					$this->session->set_userdata($data);
					if ($url !='') {
						redirect(str_replace('___', '/', $url));
					}
					redirect($_SERVER['HTTP_REFERER']);
					// redirect('Home');
				} else{
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
						Kata Sandi Anda salah!
						</div>');
					redirect('Auth/login');
				}
			} else{
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
					Email ini belum diaktivasi!
					</div>');
				redirect('auth/login');
			}
		} else{
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
				Email tidak terdaftar!
				</div>');
			redirect('auth/login');
		}
	}

	public function registration()
	{
		if ($this->session->userdata('email')) {
			$this->session->set_flashdata('message', 'Anda sudah login');
			redirect('Auth/');
		}
		$this->form_validation->set_rules('name','Name', 'required|trim');
		$this->form_validation->set_rules('email','Email', 'required|trim|valid_email|is_unique[user.email]',[
			'is_unique' => 'this email has already registered!'
		]);
		$this->form_validation->set_rules('password1','Password', 'required|trim|min_length[3]', [
			'matches' => 'password dont match!',
			'min_length' => 'password too short!'
		]);
		$this->form_validation->set_rules('gender','Gander', 'required|trim');
		$this->form_validation->set_rules('place_of_birth','Place of Birth', 'required|trim');
		$this->form_validation->set_rules('birthday','Birth Day', 'required|trim');
		$this->form_validation->set_rules('phone_number','Phone Number', 'required|trim');
		$this->form_validation->set_rules('address','Address', 'required|trim');
		$this->form_validation->set_rules('religion_id','Religion', 'required|trim');
		// 
		$this->form_validation->set_rules('password2','Confrim Password', 'required|trim|matches[password1]');
		$data['agama'] = $this->db->get('agama')->result_array();
		if ($this->form_validation->run() == false) {
			$data['title'] = 'User Registration';
			if ($this->session->userdata('email')) {
				$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
			}
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/registration', $data);
			$this->load->view('templates/auth_footer');
		} else{
			$data =[
				'name' => htmlspecialchars($this->input->post('name', true)),
				'email' => htmlspecialchars($this->input->post('email', true)),
				'gender' => htmlspecialchars($this->input->post('gender', true)),
				'place_of_birth' => htmlspecialchars($this->input->post('place_of_birth', true)),
				'birthday' => htmlspecialchars($this->input->post('birthday', true)),
				'phone_number' => htmlspecialchars($this->input->post('phone_number', true)),
				'address' => htmlspecialchars($this->input->post('address', true)),
				'religion_id' => htmlspecialchars($this->input->post('religion_id', true)),
				'image' => 'default.svg',
				'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
				'role_id' => 2,
				'is_active' => 0,
				'date_created' => time(),
			];

			$token = base64_encode(random_bytes(32));
			$user_token = [
				'email' => $this->input->post('email', true),
				'token' => $token,
				'date_created' => time()
			];

			$this->db->insert('user', $data);
			$this->db->insert('user_token', $user_token);

			$this->_sendEmail($token, 'verify');

			$user = $this->db->get_where('user', ['email' => $this->input->post('email', true)])->row_array();
		
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
				Selamat! akun telah dibuat, cek email anda untuk melakukan aktivasi!
				</div>');
			redirect('Auth/');
		}
	}

	private function _sendEmail($token, $type)
	{
		$config = [
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_user' => 'mhaitsam18@gmail.com',
			'smtp_pass' => 'mirainikki193880098',
			'smtp_port' => 465,
			'mailtype' => 'html',
			'chatset' => 'utf-8',
			'newline' => "\r\n"
		];

		$this->load->library('email', $config);
		$this->email->initialize($config); 
		$this->email->from('mhaitsam18@gmail.com', 'Muhammad Haitsam');
		$this->email->to($this->input->post('email'));
		if ($type== 'verify') {
			$this->email->subject('Account Verification');
			$this->email->message('Click this link to verify your account : <a href="'.base_url('auth/verify?email=').$this->input->post('email').'&token='.urlencode($token).'">Activate</a>');
		} elseif($type== 'forgot'){
			$this->email->subject('Reset Password');
			$this->email->message('Click this link to reset your password : <a href="'.base_url('auth/resetPassword?email=').$this->input->post('email').'&token='.urlencode($token).'">Reset Password</a>');
		}
		if ($this->email->send()) {
			return true;
		} else{
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
			if ($user['is_active']!=1) {
				$user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
				if ($user_token) {
					if (time() - $user_token['date_created'] < (60*60*24)) {
						$this->db->set('is_active', 1);
						$this->db->where('email', $email);
						$this->db->update('user');
						$this->db->delete('user_token',['email' => $email]);
						$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
						'.$email.' Telah teraktivasi, silahkan login!
						</div>');
						redirect('auth');
					} else{
						$this->db->delete('user',['email' => $email]);
						$this->db->delete('user_token',['email' => $email]);
						$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
						Aktivasi gagal: Token kadaluarsa!
						</div>');
						redirect('auth');
					}
				} else{
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
					Aktivasi gagal: Token tidak valid!
					</div>');
					redirect('auth');
				}
			} else{
				$this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">
				Akun Anda telah teraktivasi!
				</div>');
				redirect('auth');
			}
		} else{
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
			Aktivasi gagal: Email tidak terdaftar!
			</div>');
			redirect('auth');
		}
	}

	public function forgotPassword()
	{
		if ($this->session->userdata('email')) {
			$this->session->set_flashdata('message', 'Anda telah Login');
			redirect('Auth/');
		}
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		if ($this->form_validation->run() == false) {
			$data['title'] = 'Forgot Password';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/forgot-password');
			$this->load->view('templates/auth_footer');
		} else{
			$email = $this->input->post('email');
			$user = $this->db->get_where('user', ['email' => $email, 'is_active' => 1])->row_array();
			if ($user) {
				$token = base64_encode(random_bytes(32));
				$user_token = [
					'email' => $email,
					'token' => $token,
					'date_created' => time()
				];
				$this->db->insert('user_token', $user_token);
				$this->_sendEmail($token, 'forgot');
				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
					Silahkan cek emailmu untuk mereset katasandi!
					</div>');
					redirect('auth/forgotPassword');
			} else{
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
					Email tidak terdaftar!
					</div>');
					redirect('auth/forgotPassword');
			}
		}
	}

	public function resetPassword()
	{
		$email = $this->input->get('email');
		$token = $this->input->get('token');

		$user = $this->db->get_where('user', ['email' => $email])->row_array();
		if ($user) {
			$user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
			if ($user_token) {
				if (time() - $user_token['date_created'] < (60*60*24)) {
					$this->session->set_userdata('reset_email', $email);
					$this->changePassword();
				} else{
					$this->db->delete('user_token',['email' => $email]);
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
					mengatur ulang kata sandi gagal: Token Kadaluarsa!
					</div>');
					redirect('auth');
				}
			} else{
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
				mengatur ulang kata sandi gagal: Token Tidak Valid
				</div>');
				redirect('auth');
			}
		} else{
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
			mengatur ulang kata sandi gagal: Email Salah!
			</div>');
			redirect('auth');
		}
	}

	public function changePassword()
	{
		if (!$this->session->userdata('reset_email')) {
			redirect('auth/login');
		}
		$this->form_validation->set_rules('password1','Password', 'required|trim|min_length[3]');
		$this->form_validation->set_rules('password2','Confrim Password', 'required|trim|matches[password1]');
		if ($this->form_validation->run() == false) {
			$data['title'] = 'change Password';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/change-password');
			$this->load->view('templates/auth_footer');
		} else{
			$password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
			$email = $this->session->userdata('reset_email');

			$this->db->set('password', $password);
			$this->db->where('email', $email);
			$this->db->update('user');
			$this->session->unset_userdata('reset_email');
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
			Katasandi telah diubah! Silahkan login!
			</div>');
			redirect('auth');
		}
	}

	public function forgotPassword2()
	{
		if ($this->session->userdata('email')) {
			redirect('user');
		}
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		if ($this->form_validation->run() == false) {
			$data['title'] = 'Forgot Password';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/forgot-password-2');
			$this->load->view('templates/auth_footer');
		} else{
			$email = $this->input->post('email');
			$user = $this->db->get_where('user', ['email' => $email, 'is_active' => 1])->row_array();
			if ($user) {
				$pertanyaan_keamanan = $this->db->get_where('pertanyaan_keamanan', ['id_user' => $user['id']])->row_array();
				if ($pertanyaan_keamanan) {
					redirect('auth/question/'.$pertanyaan_keamanan['id']);
				} else{
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
						Maaf, Anda tidak memiliki pertanyaan keamanan!
						</div>');	
					redirect('auth/forgotPassword2');
				}
			} else{
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
					Email tidak terdaftar atau tidak terverifikasi!
					</div>');
					redirect('auth/forgotPassword2');
			}
		}
	}

	public function question($id='')
	{
		$this->db->select('*, pertanyaan_1.pertanyaan AS p1, pertanyaan_2.pertanyaan AS p2, pertanyaan_keamanan.id AS pid');
		$this->db->join('pertanyaan_1', 'pertanyaan_1.id = pertanyaan_keamanan.id_pertanyaan_1');
		$this->db->join('pertanyaan_2', 'pertanyaan_2.id = pertanyaan_keamanan.id_pertanyaan_2');
		$data['pertanyaan_keamanan'] = $this->db->get_where('pertanyaan_keamanan', ['pertanyaan_keamanan.id' => $id])->row_array();
		$data['user'] = $this->db->get_where('user', ['id' => $data['pertanyaan_keamanan']['id_user']])->row_array();
		$this->form_validation->set_rules('jawaban_1', 'Answer 1', 'trim|required');
		$this->form_validation->set_rules('jawaban_2', 'Answer 2', 'trim|required');
		if ($this->session->userdata('email')) {
			$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		}
		if ($this->form_validation->run() == false) {
			$data['title'] = 'Forgot Password';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/question', $data);
			$this->load->view('templates/auth_footer', $data);
		} else{
			$check_answer = $this->db->get_where('pertanyaan_keamanan', [
				'id' => $id,
				'jawaban_1' => $this->input->post('jawaban_1'),
				'jawaban_2' => $this->input->post('jawaban_2'),
			])->num_rows();
			if ($check_answer>0) {
				$this->session->set_userdata('reset_email', $data['user']['email']);
				redirect('auth/changePassword');
			} else{
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
					Maaf, Jawaban Anda tidak sesuai
					</div>');	
				redirect('auth/question/'.$id);
			}

		}

	}

	public function logout()
	{
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('role_id');
		$this->session->set_flashdata('message', 'Anda sudah logout');
		redirect('Auth/');
	}
}
