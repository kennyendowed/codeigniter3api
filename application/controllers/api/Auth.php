
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
use \Firebase\JWT\JWT;
use Carbon\Carbon;


class Auth extends RestController {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 function __construct()
 	{
 			// Construct the parent class
 			parent::__construct();
			 $this->load->model(['user_model']);
		 $this->load->helper(['my_helper','string']);
		 $_POST = json_decode(file_get_contents("php://input"), true);

 	}

	public function authenticate_post()
	{
		// get the local secret key
$secretkey = env('SECRET');
$publicKey=env('PUBLICKEY');

		$this->form_validation->set_rules('email', 'email', 'required');
		$this->form_validation->set_rules('password', 'password', 'required');

		if ($this->form_validation->run() == FALSE) {
			return $this->response(['errors' => $this->validation_errors_array()], RestController::HTTP_UNPROCESSABLE_ENTITY);
		}

		$user = $this->user_model->getWhere(array('email' => $this->input->post('email')));
		if (empty($user) || !password_verify(html_escape($this->input->post('password')), $user[0]->password)) {
			return $this->response(['message' => 'Email Or Password is incorrect.'], RestController::HTTP_BAD_REQUEST);
		}

		      	$issuedat_claim = new DateTimeImmutable() ; // issued at
	        $notbefore_claim = $issuedat_claim->getTimestamp() + 20; //not before in seconds
	        $expire_claim = $issuedat_claim->getTimestamp() + 3600; // expire time in seconds
		$token = JWT::encode([
			'iss' => base_url(),
			'aud' => base_url(),
			"iat" => 1356999524,
	        "nbf" => $notbefore_claim,
			'exp' => $expire_claim,
			'data' => [
				'id' => $user[0]->id,
				'fullname' => $user[0]->name,
				'email' => $user[0]->email,
				'is_permission'=>$user[0]->is_permission
			],
		], $secretkey,'RS256');
	return $this->response(['token' => $token,'expireAt' => $expire_claim]);
	}

	public function store_post()
	{
		$this->form_validation->set_rules('first_name', 'First Name', 'required|max_length[50]');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|max_length[50]');
				$this->form_validation->set_rules('phone', 'Phone Number ', 'required|min_length[10]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
$password=$this->input->post('password');
		// Validate password strength
		$uppercase = preg_match('@[A-Z]@', $password);
		$lowercase = preg_match('@[a-z]@', $password);
		$number    = preg_match('@[0-9]@', $password);
		$specialChars = preg_match('@[^\w]@', $password);

		if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
				return $this->response(['errors' => 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.'], RestController::HTTP_UNPROCESSABLE_ENTITY);

		}
		if(!validate_mobile($this->input->post('phone'))){
		   	return $this->response(['errors' => 'invalid.'], RestController::HTTP_UNPROCESSABLE_ENTITY);
		}
		if ($this->form_validation->run() == FALSE) {
			return $this->response(['errors' => $this->validation_errors_array()], 422);
		}
		$email_code =bin2hex(random_bytes(2)); // strtoupper(random_string('alnum',20));
		 $email_time = time();
 $fullname=$this->input->post('first_name').' '.$this->input->post('last_name');
		$user = $this->user_model->create([
			'name' => $fullname,
			'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
			'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
			'ip_address' =>  $_SERVER['REMOTE_ADDR'],
      'status' => '0',
      'is_permission' =>'3',
			'email_code'=>$email_code,
		 'email_time' => $email_time,
		 'email_verify'=>0,
		]);

		return $this->response([
			'message' => 'Registered successfully.',
			'data' => $user
		], RestController::HTTP_CREATED);
	}



	 public function users_get()
	{
			// Users from a data store e.g. database
			$users = [
				 ['id' => 0, 'name' => 'John', 'email' => 'john@example.com'],
				 ['id' => 1, 'name' => 'Jim', 'email' => 'jim@example.com'],
		 ];

		 $id = $this->get( 'id' );
			if ( $id === null )
			{
					// Check if the users data store contains users
					if ( $users )
					{
							// Set the response and exit
							$this->response( $users, 200 );
					}
					else
					{
							// Set the response and exit
							$this->response( [
									'status' => false,
									'message' => 'No users were found'
							], 404 );
					}
			}
			else
			{
					if ( array_key_exists( $id, $users ) )
					{
							$this->response( $users[$id], 200 );
					}
					else
					{
							$this->response( [
									'status' => false,
									'message' => 'No such user found'
							], 404 );
					}
			}
	}

}
