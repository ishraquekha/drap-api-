<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');
require(APPPATH.'/libraries/init.php');
require(APPPATH.'/libraries/Slim/Slim.php');
require(APPPATH."/libraries/PHPMailer/src/PHPMailer.php");
require(APPPATH."/libraries/PHPMailer/src/OAuth.php");
require(APPPATH."/libraries/PHPMailer/src/SMTP.php");
require(APPPATH."/libraries/PHPMailer/src/POP3.php");
require(APPPATH."/libraries/PHPMailer/src/Exception.php");

class User_api extends REST_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->language('common');
        $this->load->model('userModel_API');
        $this->datetime = date('Y-m-d H:i:s');
    }

    function login_post()
    {
        $postDataArr = $this->post();
        $email = $postDataArr['email'];
        $password= md5($postDataArr['password']);

        $result = $this->userModel_API->getUserInfo($email, $password);

        if($result != null )
        {
            $this->response(
                array(
                    'status_code' => 200,
                    'message' => 
                    "Login Successful.",
                    "result" => $result
                )
            );
        }
        else
        {
            $this->response(
                array(
                    'status_code' => 0, 
                    'message' => 
                    "Invalid email or password"
                )
            );
        }
    }

    function signup_post()
    {
            $postDataArr = $this->post();
            if ($postDataArr) 
            {
                $email = $postDataArr["email"];

                $result = $this->userModel_API->checkEmailExists($email);
                if(count($result)>0)
                {
                   $this->response(
                       array(
                           'status_code' => 0, 
                           'message' => "Email address already exist!",
                           'result' => array()
                        )
                    );
                }
                else
                {
                    $userInfo['firstname'] = $postDataArr['firstname'];
                    $userInfo['middlename'] = $postDataArr['middlename'];
                    $userInfo['lastname'] = $postDataArr['lastname'];
                    $userInfo['email'] = $postDataArr['email'];
                    $userInfo['password'] = md5($postDataArr['password']);
                    $userInfo['phone'] = $postDataArr['phon'];
                    $userInfo['address'] = $postDataArr['address'];
                    $userInfo['gender'] = $postDataArr['gender'];
                    $userInfo['dateofbirth'] = $postDataArr['dob'];
                    
                    $userId = $this->userModel_API->addNewUser($userInfo);
                    $customerData = array();
                    if($userId > 0){
                        $userData = $this->userModel_API->getUserDetails($userId);
                    }

                    $this->response(
                        array(
                            'status_code' => 200, 
                            'message' =>"Sign Up Successful.", 
                            "result" => $userData
                        )
                    );
                }
            } 
            else 
            {
                $this->response(
                array(
                    'status_code' => MISSING_PARAMETER,
                    'message' => $this->lang->line('parameter_missing')
                ));
            }  
    }

    function placeOrder_post()
    {
        $url = "";
        $back_url = "";
        $postDataArr = $this->post();
        if (!empty($_FILES['image'])) 
        {
            // next we pass the upload path for the images
            $config['upload_path']   = './uploads/';
            
            // also, we make sure we allow only certain type of images
            $config['allowed_types'] = 'gif|jpg|png';

            $new_name                            = time() . $_FILES['image']['name'];
            $_FILES['uploadedimage']['name']     = $new_name;
            $_FILES['uploadedimage']['type']     = $_FILES['image']['type'];
            $_FILES['uploadedimage']['tmp_name'] = $_FILES['image']['tmp_name'];
            $_FILES['uploadedimage']['error']    = $_FILES['image']['error'];
            $_FILES['uploadedimage']['size']     = $_FILES['image']['size'];
            $config['file_name']                 = $new_name;
            
            //now we initialize the upload library
            $this->load->library('upload',$config);
            
            //echo $new_name;die;
            if ($this->upload->do_upload('uploadedimage')) {
                $url = 'http://getyardsignapp.com/printapp/uploads/'.$new_name;
            } 
            
            $config = array();
             // next we pass the upload path for the images
             $config['upload_path']   = './uploads/';
            
             // also, we make sure we allow only certain type of images
             $config['allowed_types'] = 'gif|jpg|png';

            //Back Image            
            $new_back_name                            = time() . '_back_' . $_FILES['back_image']['name'];
            $_FILES['uploadedbackimage']['name']     = $new_back_name;
            $_FILES['uploadedbackimage']['type']     = $_FILES['back_image']['type'];
            $_FILES['uploadedbackimage']['tmp_name'] = $_FILES['back_image']['tmp_name'];
            $_FILES['uploadedbackimage']['error']    = $_FILES['back_image']['error'];
            $_FILES['uploadedbackimage']['size']     = $_FILES['back_image']['size'];
            $config['file_name']                 = $new_back_name;

           //now we initialize the upload library
           $this->upload->initialize($config);

            //echo $new_name;die;
            if ($this->upload->do_upload('uploadedbackimage')) {
                $back_url = 'http://getyardsignapp.com/printapp/uploads/'.$new_back_name;
            } 

            $orderInfo['userId'] = $postDataArr['userId'];
            $orderInfo['fullName'] = $postDataArr['fullName'];
            $orderInfo['email'] = $postDataArr['email'];
            $orderInfo['mobileNumber'] = $postDataArr['mobileNumber'];
            $orderInfo['shippingAddress'] = $postDataArr['shippingAddress'];
            $orderInfo['quantity'] = $postDataArr['quantity'];
            $orderInfo['isRoundCorner'] = $postDataArr['isRoundCorner'];
            $orderInfo['glossyOrMatte'] = $postDataArr['glossyOrMatte'];
            $orderInfo['cost'] = $postDataArr['cost'];
            $orderInfo['transactionId'] = $postDataArr['transactionId'];
            $orderInfo['file_url']  = $url;
            $orderInfo['back_file_url'] = $back_url;

            $orderId = $this->userModel_API->addNewOrder($orderInfo);
            
            if($orderId > 0){
                $message = '<html><body>';
                $message .= '<p>Hello,<strong>rlamoy</strong><br/><br/>';
                $message = "<strong>You have received an order, Please find below the details: </strong><br/>";

                $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
                //echo $message;die;

                $message .= "<tr style='background: #eee;'><td><strong>Name of Customer: ". $orderInfo['fullName'] ."</strong> </td></tr>";
                $message .= "<tr style='background: #eee;'><td><strong>Email: ". $orderInfo['email'] ." </strong> </td></tr>";
                $message .= "<tr style='background: #eee;'><td><strong>Quantity: ". $orderInfo['quantity'] ." </strong> </td></tr>";
                $message .= "<tr style='background: #eee;'><td><strong>Cost: ". $orderInfo['cost'] ." </strong> </td></tr>";
                $message .= "<tr style='background: #eee;'><td><strong>Address: ".$orderInfo['shippingAddress']." </strong> </td></tr>";
                $message .= "<tr style='background: #eee;'><td><strong>Phone: ". $orderInfo['mobileNumber'] ." </strong> </td></tr>";
                
                $message .= "<tr style='background: #eee;'><td><strong> Order preview: </strong> ";
                $message .= '<img src="'.$url.'" alt="Front View, Order placed notification" />';
                $message .= '</br></br><img src="'.$back_url.'" alt="Back View, Order placed notification" />';
                $message .= "</td></tr>";
                $message .= "</table>";
                $message .= "<p>Payment of Rs. ".$orderInfo['cost']." including taxes has been successfully made and the transaction id is: <strong>". $orderInfo['transactionId'] ."</strong></p>";
                $message .= "<p>Thanks, </br> Support Team</p>";

                
                $message .= "</body></html>";

                try{
                    $to_email = "toshim.shaikh2229@gmail.com";
                    $subject = 'Order Request';
                   
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    
                    // Create email headers
                    $headers .= 'From: support@alpha737.com'."\r\n".
                        'Reply-To: support@alpha737.com'."\r\n" .
                        'X-Mailer: PHP/' . phpversion();
                    mail($to_email,$subject,$message,$headers);

                    $this->response(
                        array(
                            'status_code' => 200, 
                            'message' => "success",
                            'result' => array()
                        )
                    );
                }
                catch(Exception $ex){
                    $this->response(
                        array(
                            'status_code' => 0, 
                            'message' => $ex->getMessage(),
                            'result' => array()
                        )
                    );
                    
                }

                $this->response(
                    array(
                        'status_code' => 200, 
                        'message' => "success",
                        'result' => array()
                    )
                );
            }
            else{
                $this->response(
                    array(
                        'status_code' => 0, 
                        'message' => "error",
                        'result' => array()
                    )
                );
            }
        }   
    }

    function createCharges_post() 
    {
        $postDataArr = $this->post();

        $amount = $postDataArr['amount'];
        $source = $postDataArr['source'];
        
        try{
            // Set your secret key: remember to change this to your live secret key in production
            // See your keys here: https://dashboard.stripe.com/account/apikeys
            Stripe\Stripe::setVerifySslCerts(false);
            Stripe\Stripe::setApiKey(STRIPE_API_KEY);
        
            $charge = Stripe\Charge::create(array(
                "amount" => $amount,
                "currency" => "usd",
                "source" => $source,
                "description" => "Payment for order"
            ));
            $success = 1;
        }
        catch (Stripe_InvalidRequestError $e) {
            // Invalid parameters were supplied to Stripe's API
            $success = 0;
            $error = $e->getMessage();
        } catch (Stripe_AuthenticationError $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $success = 0;
            $error = $e->getMessage();
        } catch (Stripe_ApiConnectionError $e) {
            // Network communication with Stripe failed
            $success = 0;
            $error = $e->getMessage();
        } catch (Stripe_Error $e) {
            // Display a very generic error to the user, and maybe send
            $success = 0;
            $error = $e->getMessage();
            // yourself an email
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $success = 0;
            $error = $e->getMessage();
        }

        if($success == 1){
            $result= array(
                'transactionId' => $charge->id,
            ); 
            
            $this->response(
            array(
                "status_code" => 200, 
                "message" =>"success", 
                "result" => $result
            ));
        }
        else
        {
            $result= array(
                'error' => $error
            ); 
            
            $this->response(
                array(
                    'status_code' => 400, 
                    'message' =>"error", 
                    "result" => $result
                )
            );
        }
    }    

    function getAllOrders_get($userId)
    {
        $result = $this->userModel_API->getAllOrders($userId);
        if(count($result)>0)
        {
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' => "orders data",
                    'result' => $result
                )
            );
        }
        else
        {
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' => "no data found",
                    'result' => array()
                )
            );

        }
    }

    function deleteOldOrders_post(){
        $postDataArr = $this->post();
        if ($postDataArr) 
        {
            $id = $postDataArr["id"];

            $result = $this->userModel_API->deleteOrder($id);
            return $this->response($result); 
        } 
    }
}
?>