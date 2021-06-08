
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
use \Firebase\JWT\JWT;
use Carbon\Carbon;


class Customer extends RestController {
var $data =array();
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
      	 $this->data['token']= $this->input->get_request_header('token');
		    validateAuth($this->data['token']);
		 $this->load->model(['user_model']);
		 $this->load->helper(['my_helper','string']);
		 $_POST = json_decode(file_get_contents("php://input"), true);

 	}

      public function user_get()
      {
        	$jwt=decode_jwt_token($this->data['token']);
        $users = $this->user_model->find($jwt->data->id);
        return $this->response(['users' => $users]);
      }

      public function me_get()
    	 {

    		$jwt=decode_jwt_token($this->data['token']);
     if ($jwt) {
    			$this->response($jwt, RestController::HTTP_OK);
    	} else {
    			$this->response(['status' => FALSE, 'message' => 'Not Found'], RestController::HTTP_NOT_FOUND);
    	}

    	 }


}
