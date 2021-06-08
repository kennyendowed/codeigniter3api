// $params = json_decode(file_get_contents('php://input'), TRUE);

// $user = array(
// 				 'id' => null,
// 				 'email' => $params['email'],
// 				 'password' => $params['password']
// 		 );
// var_dump($user);
// 		 $result = $this->user_model->insert($user);
// 		 if ($result) {
// 				 $this->response($result, 200); // 200 being the HTTP response code
// 		 } else {
// 				 $this->response(NULL, 404);
// 		 }
//

//
//
// $_POST = json_decode(file_get_contents("php://input"), true);
// $this->form_validation->set_rules('id', 'id', 'required|numeric');
// $this->form_validation->set_rules('department', 'department', 'required');
//
// if($this->form_validation->run() == TRUE)
// {
//     $id = $this->input->post('id');
//     $department = html_escape($this->input->post('department'));
//     echo $id."<br>".$department;
// }
// else
// {
//     echo "false";
// }
