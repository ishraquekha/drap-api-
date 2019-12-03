<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// require(APPPATH.'/libraries/stripe.php');
// require(APPPATH.'/libraries/lib/stripe.php');
// require_once(APPPATH.'/libraries/lib/Stripe.php');
require(APPPATH.'/libraries/REST_Controller.php');
require(APPPATH.'/libraries/init.php');
require(APPPATH.'/libraries/Slim/Slim.php');

// for payment intent api
// require FCPATH . 'vendor/autoload.php';

class Payment_api extends REST_Controller
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->database();
        $this->load->language('common');
        $this->load->model('user_api_model');
        //$this->load->library('stripe');
        $this->datetime = date('Y-m-d H:i:s');
    }

    public function addUserPaymentMethod_post()
    {
        $postDataArr = $this->post();
        if ($postDataArr) 
        {
            $this->load->helper('date');
            try 
            {
                $patientid = $postDataArr['patientid'];
                $paymentInfo['accountHolderName'] = $postDataArr['accountHolderName'];
                $paymentInfo['accountHolderRUT'] = $postDataArr['accountHolderRUT'];
                $paymentInfo['Bankid'] = $postDataArr['Bankid'];
                $paymentInfo['accountType'] = $postDataArr['accountType'];
                $paymentInfo['accountHolderEmail'] = $postDataArr['accountHolderEmail'];
                $paymentInfo['accountNumber'] = $postDataArr['accountNumber'];

                $result = $this->user_api_model->checkDetailExists($patientid);
                if(count($result)>0)
                {
                    $paymentInfo['updated_at'] = date('Y-m-d h:i:s');
                    $result = $this->user_api_model->update_single('paymentmethod', $paymentInfo, array(
                        'where' => array(
                            'patientid' => $patientid,
                            'IsActive' => 1
                        )
                    ));

                    if($result)
                    {
                        $this->response(
                            array(
                                'status_code' => 200, 
                                'message' =>"Payment details update Successful.", 
                                "result" => $paymentInfo
                            )
                        );
                    }
                    else
                    {
                        $this->response(array(
                            'status_code' => 200,
                            'message' => 'Payment details update failed',
                            "result" => array()
                        ));
                    }
                }
                else
                {
                    $paymentInfo['patientid'] = $patientid; 
                    $result = $this->user_api_model->addPaymentInfo($paymentInfo);
                    
                    if($result)
                    {
                        $this->response(
                            array(
                                'status_code' => 200, 
                                'message' =>"Payment details added successful.", 
                                "result" => $paymentInfo
                            )
                        );
                    }
                    else
                    {
                        $this->response(array(
                            'status_code' => 200,
                            'message' => 'Payment details add failed',
                            "result" => array()
                        ));
                    }
                }
                
            }
            catch (Exception $e) {
                $msg = $e->getMessage();
                $msg = wordwrap($msg, 70);
                $this->response(array(
                    'status_code' => 0,
                    'message' => $msg
                ));
            }
        }
    }
//////////////////////////////doctor payment method

public function adddoctorPaymentMethod_post()
{
    $postDataArr = $this->post();
    if ($postDataArr) 
    {
        $this->load->helper('date');
        try 
        {
            $doctorid = $postDataArr['doctorid'];
            $paymentInfo['accountHolderName'] = $postDataArr['accountHolderName'];
            $paymentInfo['accountHolderRUT'] = $postDataArr['accountHolderRUT'];
            $paymentInfo['Bankid'] = $postDataArr['Bankid'];
            $paymentInfo['accountType'] = $postDataArr['accountType'];
            $paymentInfo['accountHolderEmail'] = $postDataArr['accountHolderEmail'];
            $paymentInfo['accountNumber'] = $postDataArr['accountNumber'];

            $result = $this->user_api_model->checkDetailExists($doctorid);
            if(count($result)>0)
            {
                $paymentInfo['updated_at'] = date('Y-m-d h:i:s');
                $result = $this->user_api_model->update_single('paymentmethod', $paymentInfo, array(
                    'where' => array(
                        'doctorid' => $doctorid,
                        'IsActive' => 1
                    )
                ));

                if($result)
                {
                    $this->response(
                        array(
                            'status_code' => 200, 
                            'message' =>"Payment details update Successful.", 
                            "result" => $paymentInfo
                        )
                    );
                }
                else
                {
                    $this->response(array(
                        'status_code' => 200,
                        'message' => 'Payment details update failed',
                        "result" => array()
                    ));
                }
            }
            else
            {
                $paymentInfo['doctorid'] = $doctorid; 
                $result = $this->user_api_model->addPaymentInfo($paymentInfo);
                
                if($result)
                {
                    $this->response(
                        array(
                            'status_code' => 200, 
                            'message' =>"Payment details added successful.", 
                            "result" => $paymentInfo
                        )
                    );
                }
                else
                {
                    $this->response(array(
                        'status_code' => 200,
                        'message' => 'Payment details add failed',
                        "result" => array()
                    ));
                }
            }
            
        }
        catch (Exception $e) {
            $msg = $e->getMessage();
            $msg = wordwrap($msg, 70);
            $this->response(array(
                'status_code' => 0,
                'message' => $msg
            ));
        }
    }
}
////////////-----------select bank 

     function getbank_get()
        {
            $data=array();
            $data['result'] = $this->user_api_model->getbank();
            $data['account_type'] = $this->user_api_model->getaccounttype();
            if($data)
            {
                $this->response(
                    array(
                        'status_code' => 200, 
                        'message' =>"bank list.", 
                        "result" => $data
                    )
                );
            }
            else
            {
                $this->response(array(
                    'status_code' => 200,
                    'message' => 'bank not found',
                    "result" => array()
                ));
            }
    } 


///////////////////////////////////---














    function createCharge_post() 
    {
        $postDataArr = $this->post();
    //    print_r($postDataArr);die;
        // $appointmentid=$postDataArr['appointmentid'];
        $amount = $postDataArr['amount'];
        $source = $postDataArr['source'];
        $appoint['patientid'] = $postDataArr['patientid'];
        $appoint['doctorid'] = $postDataArr['doctorid'];
        $appoint['consultationreason'] = $postDataArr['consultationreason'];
        $appoint['scheduleddate'] = $postDataArr['scheduleddate'];
        $appoint['timeofarrivel'] = $postDataArr['timeofarrivel'];
        // $appoint['doctorspaciality'] = $postData['doctorspaciality'];
        // $appoint['url'] = $postData['url'];
        $dd=date('Y-m-d');
        $now = new DateTime();
            $now->setTimezone(new DateTimezone('Asia/Kolkata'));
            $dt=$now->format('H:i:s');
            $datetime = $dd . ' ' . $dt;
            $appoint['created_date'] = $datetime;
            // $appoint['created_date'] = date('Y-m-d H:i:s');
        // print_r($appoint['created_date']);die;
        
        try{
             // Set your secret key: remember to change this to your live secret key in production
            // See your keys here: https://dashboard.stripe.com/account/apikeys
            Stripe\Stripe::setVerifySslCerts(false);

            if(MODE == 'test'){
                // echo'hi';
                Stripe\Stripe::setApiKey(STRIPE_API_KEY);
            }
            else{
                Stripe\Stripe::setApiKey(P_STRIPE_KEY);
            }
        
            $charge = Stripe\Charge::create(array(
                "amount" => $amount,
                "currency" => "clp",
                "source" => $source,
                "description" => "Payment for appointments",
                "capture" => false,
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

            
         
           // for saving this datat in database addition code
           $insertid = $this->user_api_model->insertappointments($appoint);
           $appointments = $this->user_api_model->getappointments($insertid);
            $c ['transactionid'] = $charge->id; 
            $c['appointmentsid']=   $appointments['id'];
            $c ['amount']   = $amount;
            $res = $this->user_api_model->createcharge($c);
            $result= array(
            
                'appointmentid' => $appointments['id'],
                'transactionId' => $charge->id,
                'amount' => $amount,
            ); 
            
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Payement success And Appointment insert succesfully", 
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
                    'status_code' => 200, 
                    'message' =>"error and appointment create failed", 
                    "result" => $result
                )
            );
        }
    }   

//     function refund_post(){
//         $postDataArr = $this->post();
//         $appointmentid=$postDataArr['appointmentid'];
//         $appointments = $this->user_api_model->getappointments($appointmentid);
//         $payment = $this->user_api_model->getpaymentdetails($appointmentid);
//         $docpayment = $this->user_api_model->getpaymentdetailsdoc($appointmentid);
        
//         if($payment['isrefund']==0)
//         {
//         $created_date=strtotime($appointments['accept_time']);
//         $date=strtotime(date('Y-m-d H:i:s'));

//     // print_r($appointments['accept_time']);
//     // echo '.....';
//     // print_r( date('Y-m-d H:i:s'));
//     // echo '.....';
//     // print_r($date);
//     // echo '.....';
//     // print_r($created_date);
//     // echo '.....';
//     // print_r($date-$created_date);
//     // echo '.....';
//     // print_r(20*60);
//     // echo '.....';
    
//     try{
//         //// condition for cancellation by patient before 10 min after doctor accpt appointment 100% amount refund 
//         if($date-$created_date < 10 * 60)
//             {
//                 $amount=$payment['amount'];
                
//                 // echo'100% return amount cancel after doctor accpt appointment but before 10 min ago';
//                 Stripe\Stripe::setVerifySslCerts(false);
                
//                 if(MODE == 'test'){
//                         Stripe\Stripe::setApiKey(STRIPE_API_KEY);
//                     }
//                     else{
//                         Stripe\Stripe::setApiKey(P_STRIPE_KEY);
//                     }

//                 $refund = \Stripe\Refund::create([
//                 'charge' => $payment['transactionid'],
//                 'amount' =>$amount
//                 ]);
//                 $docrefund = \Stripe\Refund::create([
//                     'charge' => $docpayment['transactionid'],
//                     'amount' => $docpayment['amount']
//                     ]);
//                 $success = 2;
  
//                 }

//                 //// condition for cancellation by patient after doctor accept appointment 90% amount refund
//                 elseif($date-$created_date >= 10 * 60 && $date-$created_date < 20 * 60)
//                 {
//                 // echo'90% return amount';
//                 $per=(10/100)*$payment['amount'];
//                 $amount=$payment['amount']-$per;
                
//                 Stripe\Stripe::setVerifySslCerts(false);
               
//                 if(MODE == 'test'){
//                         Stripe\Stripe::setApiKey(STRIPE_API_KEY);
//                     }
//                     else{
//                         Stripe\Stripe::setApiKey(P_STRIPE_KEY);
//                     }

                    
//                 // $charge = \Stripe\Charge::retrieve($payment['transactionid']);
//                 // $charge->capture();
               
//                 $refund = \Stripe\Refund::create([
//                 'charge' => $payment['transactionid'],
//                 'amount' =>$amount
        
//                 ]);
//                 $docrefund = \Stripe\Refund::create([
//                 'charge' => $docpayment['transactionid'],
//                 'amount' => $docpayment['amount']
//                 ]);
//                 // print_r($charge->id);
//                 // print_r($refund);
//                 // print_r($docrefund);die;
//                 $success = 2;
  
//                 }
                
//                  //// condition for cancellation by patient after doctor accept appointment after 20 min 80% amount refund
//                 elseif($date-$created_date >= 20 * 60 && $date-$created_date <= 30 * 60)
//                 {
//                 // echo'80% return amount';
//                 $per=(20/100)*$payment['amount'];
//                 $amount=$payment['amount']-$per;
               
//                 Stripe\Stripe::setVerifySslCerts(false);

//                 if(MODE == 'test'){
//                         Stripe\Stripe::setApiKey(STRIPE_API_KEY);
//                     }
//                     else{
//                         Stripe\Stripe::setApiKey(P_STRIPE_KEY);
//                     }

    
//                 // $charge = \Stripe\Charge::retrieve($payment['transactionid']);
//                 // $charge->capture();
//                 $refund = \Stripe\Refund::create([
//                 'charge' => $payment['transactionid'],
//                 'amount' =>$amount
        
//                 ]);
//                 $docrefund = \Stripe\Refund::create([
//                 'charge' => $docpayment['transactionid'],
//                 'amount' => $docpayment['amount']
//                 ]);
                   
//                 $success = 2;
  
//                 }
//                 //// condition for cancellation by patient before doctor accept appointment 100% amount retrun
//                 elseif($created_date== null && $payment['isrefund']==0)
//                 {
//                 $amount=$payment['amount'];
//                 // echo '100% amount return doctor not acceptes'; 
//                 Stripe\Stripe::setVerifySslCerts(false);

//                 if(MODE == 'test'){
//                         Stripe\Stripe::setApiKey(STRIPE_API_KEY);
//                     }
//                     else{
//                         Stripe\Stripe::setApiKey(P_STRIPE_KEY);
//                     }

//                 $refund = \Stripe\Refund::create([
//                 'charge' => $payment['transactionid'],
//                 'amount' =>$amount
        
//                 ]);

//                 $success = 1;
  
//                 }
//             else{
//                 // echo'hey';
//                 $success = 0;
//             }
            
//         }
        
//             catch (Stripe_InvalidRequestError $e) {
//                 // Invalid parameters were supplied to Stripe's API
//                 $success = 0;
//                 $error = $e->getMessage();
//             } catch (Stripe_AuthenticationError $e) {
//                 // Authentication with Stripe's API failed
//                 // (maybe you changed API keys recently)
//                 $success = 0;
//                 $error = $e->getMessage();
//             } catch (Stripe_ApiConnectionError $e) {
//                 // Network communication with Stripe failed
//                 $success = 0;
//                 $error = $e->getMessage();
//             } catch (Stripe_Error $e) {
//                 // Display a very generic error to the user, and maybe send
//                 $success = 0;
//                 $error = $e->getMessage();
//                 // yourself an email
//             } catch (Exception $e) {
//                 // Something else happened, completely unrelated to Stripe
//                 $success = 0;
//                 $error = $e->getMessage();
//             }
            
//             if($success == 1){
//                 $isrefund = 1;
//                     $refundupdate = $this->user_api_model->updatePayment($isrefund,$appointments['id']);
                    
//                 $result= array(
//                     'appointmentid' => $appointments['id'],
//                     'transactionId' => $refund,
//                     'amount' => $amount,
            
//                 ); 
                
//                 $this->response(
//                     array(
//                         'status_code' => 200, 
//                         'message' =>"Payment has been refunded", 
//                         "result" => $result
//                     ));
//             }
//             elseif($success == 2)
//             {
//                 $isrefund = 1;
//                     $refundupdate = $this->user_api_model->updatePayment($isrefund,$appointments['id']);
//                     $docrefundupdate = $this->user_api_model->updatePaymentdoc($isrefund,$appointments['id']);
//                 $result= array(
//                     'appointmentid' => $appointments['id'],
//                     'transactionId' => $refund,
//                     'amount' => $amount,
//                     'docrefund'=> $docrefund,
//                 ); 
                
//                 $this->response(
//                     array(
//                         'status_code' => 200, 
//                         'message' =>"Payment has been refunded", 
//                         "result" => $result
//                     ));
//             }
//             else
//             {
//                 $this->response(
//                     array(
//                         'status_code' => 400, 
//                         'message' =>"payment cut 100%", 
//                         "result" => $payment
//                     ));

//             }
           
//     }
//     else
//     {
//         $this->response(
//             array(
//                 'status_code' => 400, 
//                 'message' =>"payment already refunded", 
//                 "result" => $payment
//             )
//         );
//     }
// }
//////////refund patient api
function refund_post(){
    $postDataArr = $this->post();
    $appointmentid=$postDataArr['appointmentid'];
    $appointments = $this->user_api_model->getappointments($appointmentid);
    $payment = $this->user_api_model->getpaymentdetails($appointmentid);
    $docpayment = $this->user_api_model->getpaymentdetailsdoc($appointmentid);
    // print_r($appointments);die;
    if($payment['isrefund']==0)
    {
        $dd=date('Y-m-d');
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Asia/Kolkata'));
        $dt=$now->format('H:i:s');
        $datetime = $dd . ' ' . $dt;
    $created_date=strtotime($appointments['accept_time']);
    $date=strtotime($datetime);
    $sh=strtotime($appointments['dat']);
//     print_r($sh);
//     echo'.....';
//     print_r($appointments);
//     print_r($sh-60*60);die;
// print_r($appointments['accept_time']);
// echo '.....';
// print_r( date('Y-m-d H:i:s'));
// echo '.....';
// print_r($date);
// echo '.....';
// print_r($created_date);
// echo '.....';
// print_r($date-$created_date);
// echo '.....';
// print_r(10*60);
// echo '.....';

try{
     //// condition for cancellation by patient before doctor accept appointment 100% amount retrun
        if($created_date == null && $payment['isrefund']==0)
            {
            $amount=$payment['amount'];
            // echo '100% amount return doctor not acceptes'; 
            Stripe\Stripe::setVerifySslCerts(false);

            if(MODE == 'test'){
                    Stripe\Stripe::setApiKey(STRIPE_API_KEY);
                }
                else{
                    Stripe\Stripe::setApiKey(P_STRIPE_KEY);
                }

            $refund = \Stripe\Refund::create([
            'charge' => $payment['transactionid'],
            'amount' =>$amount

            ]);
                // print_r($refund);die;
            $success = 1;

            }
    //// condition for cancellation by patient before 10 min after doctor accpt appointment 100% amount refund 
   elseif($date > $sh-60*60 && $appointments['isaccepted']==1)
   {
    $success = 2;
   }
    elseif($date-$created_date < 10 * 60)
        {
            $amount=$payment['amount'];
            
            // echo'100% return amount cancel after doctor accpt appointment but before 10 min ago';
            Stripe\Stripe::setVerifySslCerts(false);
            
            if(MODE == 'test'){
                    Stripe\Stripe::setApiKey(STRIPE_API_KEY);
                }
                else{
                    Stripe\Stripe::setApiKey(P_STRIPE_KEY);
                }

            $refund = \Stripe\Refund::create([
            'charge' => $payment['transactionid'],
            'amount' =>$amount
            ]);
            
            $success = 1;

            }

            //// condition for cancellation by patient after doctor accept appointment 90% amount refund
            elseif($date-$created_date > 10 * 60)
            {
            // echo'90% return amount';die;
            $per=(10/100)*$payment['amount'];
            $amount=$payment['amount']-$per;
            // $net=(6.5/100)*$payment['amount'];
            
            Stripe\Stripe::setVerifySslCerts(false);
           
            if(MODE == 'test'){
                    Stripe\Stripe::setApiKey(STRIPE_API_KEY);
                }
                else{
                    Stripe\Stripe::setApiKey(P_STRIPE_KEY);
                }

                // print_r($payment['transactionid']);die;
            // $charge = \Stripe\Charge::retrieve($payment['transactionid']);
            // $charge->capture();
            // print_r($amount);die;
            $refund = \Stripe\Refund::create([
                'charge' => $payment['transactionid'],
                'amount' =>$amount
                ]);
                // print_r($amount);die;
            $success = 1;

            }
            
             //// condition for cancellation by patient after doctor accept appointment after 20 min 80% amount refund
           
           
            
        else{
            echo'hey';
            $success = 0;
        }
        
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
            $isrefund = 1;
            // echo'hello';
                $refundupdate = $this->user_api_model->updatePayment($isrefund,$appointments['id'],$amount);
                
            $result= array(
                'appointmentid' => $appointments['id'],
                'transactionId' => $refund,
                'amount' => $amount,
        
            ); 
            
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Appointment cancelled successfully and amount has been refunded", 
                    "result" => $result
                ));
        }elseif($success == 2){
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Appointment could not be cancelled before 1 hour to scheduled time" 
                
                ));
        }
       
        else
        {
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Appointment cancel failed", 
                    "result" => $payment
                ));

        }
       
}
else
{
    $this->response(
        array(
            'status_code' => 200, 
            'message' =>"Appointment already cancelled by doctor", 
            "result" => $payment
        )
    );
}
}

public function doctoraccept_get($appointmentid)
    {
        // $postDataArr = $this->post();
        // $appointmentid=$postDataArr['appointmentid'];
        $appointments = $this->user_api_model->getappointmentdoc($appointmentid);
        // print_r($appointments);die;
        if($appointments['iscancelledbypatient']==0)
        {

        
        if($appointments['isaccepted']==0)
        { 
            Stripe\Stripe::setVerifySslCerts(false);
           
            if(MODE == 'test'){
                    Stripe\Stripe::setApiKey(STRIPE_API_KEY);
                }
                else{
                    Stripe\Stripe::setApiKey(P_STRIPE_KEY);
                }
            $payment = $this->user_api_model->getpaymentdetails($appointmentid);
            // print_r($payment['transactionid']);die;
            $pcharge = \Stripe\Charge::retrieve($payment['transactionid']);
            $pcharge->capture();
        $result = $this->user_api_model->getacceptedbydoctor($appointmentid);

        if($result)
        {

            $this->response(
                array(
                    'status_code' => 200, 
                    'Appointmentid'=>$appointmentid,
                    'message' =>"Appointment Accepted ",
                    "result" => $result
                )
            );
        }
        else{
            $this->response(array(
                'status_code' => 200,
                'message' => "Appointment Acceptance failed",
                "result" => array()
            ));
        }
    }


    else{
        $this->response(
            array(
                'status_code' => 200, 
                'message' =>"Already accepted appointment", 
                "result" => 'error'
            )
        );
    }
}
else{
    $this->response(
        array(
            'status_code' => 200, 
            'message' =>"patient already cancelled appointment", 
            "result" => 'error'
        )
    );

}
    }

    function doctorreject_get($appointmentid){
        
        $appointments = $this->user_api_model->getappointments($appointmentid);
        $payment = $this->user_api_model->getpaymentdetailsdoc($appointmentid);
        $patientpayment = $this->user_api_model->getpaymentdetails($appointmentid);
        // print_r($appointments);
        // print_r($payment);
        // print_r($patientpayment['amount']);
        // print_r($appointments['id']);die;
        $dd=date('Y-m-d');
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Asia/Kolkata'));
        $dt=$now->format('H:i:s');
        $datetime = $dd . ' ' . $dt;
        $date=strtotime($datetime);
    $sh=strtotime($appointments['dat']);

//     print_r($dt);
// echo'...';
//     print_r($appointments['dat']);die;
// print_r($appointments['isaccepted']);
    if($date < $sh-60*60 || $appointments['isaccepted'] == 0)
    {
        if($appointments['isrefund'] == 0)
        {
        Stripe\Stripe::setVerifySslCerts(false);
           
            if(MODE == 'test'){
                    Stripe\Stripe::setApiKey(STRIPE_API_KEY);
                }
                else{
                    Stripe\Stripe::setApiKey(P_STRIPE_KEY);
                }
            $refund = \Stripe\Refund::create([
            'charge' => $patientpayment['transactionid'],
            'amount' =>$patientpayment['amount']
            ]);
            $success=1;
        
        if($success == 1){
            $isrefund = 1;
            // print_r($patientpayment['amount']);die;
            $refundupdate = $this->user_api_model->updatePaymentdoc($isrefund,$appointments['id'],$patientpayment['amount']);
                
            // $result= array(
            //     'appointmentid' => $appointments['id'],
            //     'transactionId' => $refund,
            //     'amount' => $amount,
            //     'patient'=>$prefund,
            // ); 
            
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Appointment Rejected", 
                    "result" => $refundupdate
                ));
        }
        else
        {
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"appointment rejection failed", 
                    "result" => $refundupdate
                ));

        }
        }
    else{
        $this->response(
            array(
                'status_code' => 200, 
                'message' =>"appointment already cancelled by patient", 
                "result" => 'error'
            )
        );
    
    }
}
   {
        $this->response(
            array(
                'status_code' => 200, 
                'message' =>"Appointment could not be cancelled before 1 hour to scheduled time", 
                "result" => 'error'
            )
        );
    }
  }



////paymentiintend api create charge
    // function payCharge_post() 
    // {
    //     $postDataArr = $this->post();
    //     // $appointmentid=$postDataArr['appointmentid'];
    //     // $doctorid = $postDataArr['doctorid'];
    //     // $patientid =  $postDataArr['patientid'];
    //     $amount = $postDataArr['amount'];
    //     // $source = $postDataArr['source'];

    //     try{
    //          // Set your secret key: remember to change this to your live secret key in production
    //         // See your keys here: https://dashboard.stripe.com/account/apikeys
    //         Stripe\Stripe::setVerifySslCerts(false);

    //         \Stripe\Stripe::setApiKey(STRIPE_API_KEY);

    //        $charge= \Stripe\PaymentIntent::create([
    //             'amount' => $amount,
    //             'currency' => 'usd',
    //             'payment_method_types' => ['card'],
    //         ]);
    //         $success = 1;
    //     }
    //     catch (Stripe_InvalidRequestError $e) {
            
    //         // Invalid parameters were supplied to Stripe's API
    //         $success = 0;
    //         $error = $e->getMessage();
    //     } catch (Stripe_AuthenticationError $e) {
    //         // Authentication with Stripe's API failed
    //         // (maybe you changed API keys recently)
    //         $success = 0;
    //         $error = $e->getMessage();
    //     } catch (Stripe_ApiConnectionError $e) {
    //         // Network communication with Stripe failed
    //         $success = 0;
    //         $error = $e->getMessage();
    //     } catch (Stripe_Error $e) {
    //         // Display a very generic error to the user, and maybe send
    //         $success = 0;
    //         $error = $e->getMessage();
    //         // yourself an email
    //     } catch (Exception $e) {
    //         // Something else happened, completely unrelated to Stripe
    //         $success = 0;
    //         $error = $e->getMessage();
    //     }

    //     if($success == 1){
           
    //         // print_r($appointments['id']);die;
    //         // // for saving this datat in database addition code
    //         // $c ['transactionid'] = $charge->id;
    //         // // $c ['doctorid'] = $doctorid;
    //         // $c['appointmentsid']=   $appointmentid;
    //         // // $c ['patientid'] = $patientid; 
    //         // $c ['amount']   = $amount;
    //         // $res = $this->user_api_model->createcharge($c);

    //          //////////////////////
    //         $result= array(
    //             // 'patientId' => $patientid,
    //             // 'doctorId' => $doctorid,
                
    //             'transactionId' => $charge,
    //             'amount' => $amount,
            
    //         ); 
            
    //         $this->response(
    //             array(
    //                 'status_code' => 200, 
    //                 'message' =>"success", 
    //                 "result" => $result
    //             ));
    //     }
    //     else
    //     {
            
    //         $c['amount']=$amount;
    //         $c['error']=$error;
    //         // $res = $this->user_api_model->createcharge($c);
    //         $result= array(
    //             'error' => $error

    //         ); 
            
    //         $this->response(
    //             array(
    //                 'status_code' => 400, 
    //                 'message' =>"error", 
    //                 "result" => $result
    //             )
    //         );
    //     }
    // }   




    //////for payment intent retrive
    // function retriveCharge_post() 
    // {
    //     $postDataArr = $this->post();
    //     // $appointmentid=$postDataArr['appointmentid'];
    //     // $doctorid = $postDataArr['doctorid'];
    //     // $patientid =  $postDataArr['patientid'];
    //     $id = $postDataArr['id'];
    //     // $source = $postDataArr['source'];

    //     try{
    //          // Set your secret key: remember to change this to your live secret key in production
    //         // See your keys here: https://dashboard.stripe.com/account/apikeys
    //         Stripe\Stripe::setVerifySslCerts(false);

    //         \Stripe\Stripe::setApiKey('sk_test_uebAAJRcfLkAW2u8HvMbtt9400akPVN4ry');

    //         $intent = \Stripe\PaymentIntent::retrieve($id);
    //         $charges = $intent->charges->data;
    //         $success = 1;
    //     }
    //     catch (Stripe_InvalidRequestError $e) {
    //         // Invalid parameters were supplied to Stripe's API
    //         $success = 0;
    //         $error = $e->getMessage();
    //     } catch (Stripe_AuthenticationError $e) {
    //         // Authentication with Stripe's API failed
    //         // (maybe you changed API keys recently)
    //         $success = 0;
    //         $error = $e->getMessage();
    //     } catch (Stripe_ApiConnectionError $e) {
    //         // Network communication with Stripe failed
    //         $success = 0;
    //         $error = $e->getMessage();
    //     } catch (Stripe_Error $e) {
    //         // Display a very generic error to the user, and maybe send
    //         $success = 0;
    //         $error = $e->getMessage();
    //         // yourself an email
    //     } catch (Exception $e) {
    //         // Something else happened, completely unrelated to Stripe
    //         $success = 0;
    //         $error = $e->getMessage();
    //     }

    //     if($success == 1){
           
    //         // print_r($appointments['id']);die;
    //         // // for saving this datat in database addition code
    //         // $c ['transactionid'] = $charge->id;
    //         // // $c ['doctorid'] = $doctorid;
    //         // $c['appointmentsid']=   $appointmentid;
    //         // // $c ['patientid'] = $patientid; 
    //         // $c ['amount']   = $amount;
    //         // $res = $this->user_api_model->createcharge($c);

    //          //////////////////////
    //         $result= array(
    //             // 'patientId' => $patientid,
    //             // 'doctorId' => $doctorid,
                
    //             'transactionId' => $charges,
             
            
    //         ); 
            
    //         $this->response(
    //             array(
    //                 'status_code' => 200, 
    //                 'message' =>"success", 
    //                 "result" => $result
    //             ));
    //     }
    //     else
    //     {
            
           
    //         $c['error']=$error;
    //         // $res = $this->user_api_model->createcharge($c);
    //         $result= array(
    //             'error' => $error

    //         ); 
            
    //         $this->response(
    //             array(
    //                 'status_code' => 400, 
    //                 'message' =>"error", 
    //                 "result" => $result
    //             )
    //         );
    //     }
    // }   


 
    

    // public function createCharge_post()
    // {
    //     $postDataArr = $this->post();
    //     if ($postDataArr) 
    //     {
    //         $this->load->helper('date');
    //         $amount = $postDataArr['amount'];
    //         $source = $postDataArr['source'];
    //         $appointmentid = $postDataArr['taskid'];

    //         $charge = array (
    //             "amount" => $amount,
    //             "source" => $source,
    //             "description" => "test order from ios",
    //             "currency" => "usd",
    //         );

    //         $result = $this->stripe->addCharge($charge);

    //         if($result['status'] == 1)
    //         {
    //             //To be parsing charge response and save into database
    //             $this->response(
    //                 array(
    //                     'status_code' => 200, 
    //                     'message' =>"success", 
    //                     "result" => $result['result']
    //                 )
    //             ); 
    //         }
    //         else
    //         {
    //             $this->response(
    //                 array(
    //                     'status_code' => 400, 
    //                     'message' =>"failed", 
    //                     "result" => $result['result']
    //                 )
    //             );
    //         }

           
    //     }
    //     else 
    //     {
    //         $this->response(
    //         array(
    //             'status_code' => MISSING_PARAMETER,
    //             'message' => $this->lang->line('parameter_missing')
    //         ));
    //     }    
    // }
    function CardSave_post()
    {
        $postDataArr = $this->post();
        $amount = $postDataArr['amount'];
        $source = $postDataArr['source'];
        $appointmentid = $postDataArr['appointmentid'];
        $appointments = $this->user_api_model->getappointmentdoc($appointmentid);
        $id = $appointments['doctorid'];
        $email = $appointments['email'];
        try{
             // Set your secret key: remember to change this to your live secret key in production
            // See your keys here: https://dashboard.stripe.com/account/apikeys
            Stripe\Stripe::setVerifySslCerts(false);
    
            if(MODE == 'test'){
                // echo'hi';
                Stripe\Stripe::setApiKey(STRIPE_API_KEY);
            }
            else{
                Stripe\Stripe::setApiKey(P_STRIPE_KEY);
            }
        
            $customer = \Stripe\Customer::create([
                'source' => $source,
                'email' => $email,
            ]);
            $charge = \Stripe\Charge::create([
                'amount' => $amount,
                'currency' => 'clp',
                'customer' => $customer->id,
            ]);
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
    
           
            $card = $this->user_api_model->updatecard($id,$customer->id);
            $appoint = $this->user_api_model->getacceptedbydoctor($appointmentid);
           
            
            $c ['transactionid'] = $charge->id;
            $c ['appointmentid'] = $appointmentid;
            $c ['amount']   = $amount;
            
            $result= array(
                // 'patientId' => $patientid,
                // 'doctorId' => $doctorid,
                'appointmentid'=>$appointmentid,
                'customerid' => $customer->id,
                'transactionId' => $charge->id,
                'amount' => $amount,
            ); 
            
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Payement success And Appointment accept succesfully", 
                    "result" => $result
                ));
        }
        else
        {
            $c['amount']=$amount;
            $c['error']=$error;
       
            $result= array(
                'error' => $error
    
            ); 
            
            $this->response(
                array(
                    'status_code' => 400, 
                    'message' =>"error and appointment create failed", 
                    "result" => $result
                )
            );
        }
    
    }
    



    function doctorpay_post()
    {
        $postDataArr = $this->post();
        $appointmentid = $postDataArr['appointmentid'];
        $amount = $postDataArr['amount'];
        // $savecard = $postDataArr['savecard'];
        // print_r($appointmentid);die;
        $appointments = $this->user_api_model->getappointmentdoc($appointmentid);
        // print_r($appointments);die;
        if($appointments['isaccepted']==0){
        if( $postDataArr['savecard'] == 1 )
        { 
            // echo'hello';die;
            $id = $appointments['doctorid'];
            $email = $appointments['email'];
            $source = $postDataArr['source'];
            try{
                // Set your secret key: remember to change this to your live secret key in production
                // See your keys here: https://dashboard.stripe.com/account/apikeys
                Stripe\Stripe::setVerifySslCerts(false);
        
                if(MODE == 'test'){
                    // echo'hi';
                    Stripe\Stripe::setApiKey(STRIPE_API_KEY);
                }
                else{
                    Stripe\Stripe::setApiKey(P_STRIPE_KEY);
                }

            
                $customer = \Stripe\Customer::create([
                    'source' => $source,
                    'email' => $email,
                ]);
                $charge = \Stripe\Charge::create([
                    'amount' => $amount,
                    'currency' => 'clp',
                    'customer' => $customer->id,
                ]);
              
           $account= \Stripe\Account::create([
                    'type' => 'custom',
                    'country' => 'US',
                    'email' => $email,
                    'requested_capabilities' => [
                        'card_payments',
                        'transfers',
                    ],
                    ]);
                    // print_r($account);die;
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
                $payment = $this->user_api_model->getpaymentdetails($appointmentid);
                $pcharge = \Stripe\Charge::retrieve($payment['transactionid']);
                $pcharge->capture();
               
                $card = $this->user_api_model->updatecard($id,$customer->id,$account->id);
                $appoint = $this->user_api_model->getacceptedbydoctor($appointmentid);
               
                // $c ['capture'] = $pcharge;
                $c ['transactionid'] = $charge->id;
                $c ['appointmentsid'] = $appointmentid;
                $c ['amount']   = $amount;
                $c ['is_type']=1;
                $res = $this->user_api_model->createcharge($c);
                
                $result= array(
                    // 'patientId' => $patientid,
                    // 'doctorId' => $doctorid,
                    'appointmentid'=>$appointmentid,
                    'customerid' => $customer->id,
                    'transactionId' => $charge->id,
                    'amount' => $amount,
                    'account'=>$account->id,
                    // 'capture'=> $pcharge,
                ); 
                
                $this->response(
                    array(
                        'status_code' => 200, 
                        'message' =>"Payement and card save success And Appointment accept succesfully", 
                        "result" => $result
                    ));
            }
            else
            {
                // $c['amount']=$amount;
                // $c['error']=$error;
                // $c['is_type']=1;
                // $res = $this->user_api_model->createcharge($c);
                $result= array(
                    'error' => $error
        
                ); 
                
                $this->response(
                    array(
                        'status_code' => 400, 
                        'message' =>"error and appointment create failed", 
                        "result" => $result
                    )
                );
            }

        }
        elseif($postDataArr['savecard'] == 0)
        {
             
                // echo'hello';
                $id = $appointments['doctorid'];
                $email = $appointments['email'];
                $source = $postDataArr['source'];
                try{
                    // Set your secret key: remember to change this to your live secret key in production
                    // See your keys here: https://dashboard.stripe.com/account/apikeys
                    Stripe\Stripe::setVerifySslCerts(false);
            
                    if(MODE == 'test'){
                        // echo'hi';
                        Stripe\Stripe::setApiKey(STRIPE_API_KEY);
                    }
                    else{
                        Stripe\Stripe::setApiKey(P_STRIPE_KEY);
                    }
                
                    $charge = \Stripe\Charge::create([
                        'amount' => $amount,
                        'currency' => 'clp',
                        'source' => $source
                    ]);
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
                    $payment = $this->user_api_model->getpaymentdetails($appointmentid);
                    $pcharge = \Stripe\Charge::retrieve($payment['transactionid']);
                    $pcharge->capture();
                   
                    $appoint = $this->user_api_model->getacceptedbydoctor($appointmentid);
                   
                    // $c ['capture'] = $pcharge;
                    $c ['transactionid'] = $charge->id;
                    $c ['appointmentsid'] = $appointmentid;
                    $c ['amount']   = $amount;
                    $c ['is_type']=1;
                    $res = $this->user_api_model->createcharge($c);
                    
                    $result= array(
                        // 'patientId' => $patientid,
                        // 'doctorId' => $doctorid,
                        'appointmentid'=>$appointmentid,
                        'transactionId' => $charge->id,
                        'amount' => $amount,
                        // 'capture'=> $pcharge,
                    ); 
                    
                    $this->response(
                        array(
                            'status_code' => 200, 
                            'message' =>"Payment success And Appointment accept succesfully", 
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
                            'message' =>"error and appointment accept failed", 
                            "result" => $result
                        )
                    );
                }
    
            
        }
        else{
        try{
            // Set your secret key: remember to change this to your live secret key in production
           // See your keys here: https://dashboard.stripe.com/account/apikeys
           Stripe\Stripe::setVerifySslCerts(false);
    
           if(MODE == 'test'){
               // echo'hi';
               Stripe\Stripe::setApiKey(STRIPE_API_KEY);
           }
           else{
               Stripe\Stripe::setApiKey(P_STRIPE_KEY);
           }
       
           $charge = \Stripe\Charge::create([
            'amount' => $amount,
            'currency' => 'clp',
            'customer' => $appointments['stripe_id'],
        ]);
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
            $payment = $this->user_api_model->getpaymentdetails($appointmentid);
            $pcharge = \Stripe\Charge::retrieve($payment['transactionid']);
            $pcharge->capture();
           $appoint = $this->user_api_model->getacceptedbydoctor($appointmentid);
          
        //    $c['capture'] = $pcharge;
           $c ['transactionid'] = $charge->id;
           $c ['appointmentsid'] = $appointmentid;
           $c ['amount']   = $amount;
           $c['is_type']=1;
           $res = $this->user_api_model->createcharge($c);
           $result= array(
               // 'patientId' => $patientid,
               // 'doctorId' => $doctorid,
               'appointmentid'=>$appointmentid,
               'customerid' => $appointments['stripe_id'],
               'transactionId' => $charge->id,
               'amount' => $amount,
            //    'capture'=> $pcharge,
           ); 
           
           $this->response(
               array(
                   'status_code' => 200, 
                   'message' =>"Payement success And Appointment accept succesfully", 
                   "result" => $result
               ));
       }
       else
       {
        //    $c['amount']=$amount;
        //    $c['error']=$error;
        //    $c['is_type']=1;
        //    $res = $this->user_api_model->createcharge($c);
           $result= array(
               'error' => $error
    
           ); 
           
           $this->response(
               array(
                   'status_code' => 400, 
                   'message' =>"error in transaction and appointment accept failed", 
                   "result" => $result
               )
           );
       }
    }
}
    else{
        $this->response(
            array(
                'status_code' => 400, 
                'message' =>"already accepted appointment", 
                "result" => 'error'
            )
        );
    }
    }

    // Charge the Customer instead of the card:
        // $charge = \Stripe\Charge::create([
        //     'amount' => $amount,
        //     'currency' => 'usd',
        //     'customer' => $appointments['stripe_id'],
        // ]);
        
        // YOUR CODE: Save the customer ID and other info in a database for later.

        // When it's time to charge the customer again, retrieve the customer ID.
        // $charge = \Stripe\Charge::create([
        //     'amount' => 1500, // $15.00 this time
        //     'currency' => 'usd',
        //     'customer' => $customer_id, // Previously stored, then retrieved
        // ]);
    
        function refundDoc_post(){
            $postDataArr = $this->post();
            $appointmentid=$postDataArr['appointmentid'];
            $appointments = $this->user_api_model->getappointments($appointmentid);
            $payment = $this->user_api_model->getpaymentdetailsdoc($appointmentid);
            $patientpayment = $this->user_api_model->getpaymentdetails($appointmentid);
            // print_r($payment);die;
            if($payment['isrefund_doc']==0 && $payment['is_type'] ==1 && $patientpayment['isrefund']==0 )
            {
        try{

                    $amount=$payment['amount'];
                    Stripe\Stripe::setVerifySslCerts(false);
                    
                    if(MODE == 'test'){
                            Stripe\Stripe::setApiKey(STRIPE_API_KEY);
                        }
                        else{
                            Stripe\Stripe::setApiKey(P_STRIPE_KEY);
                        }
    
                    $refund = \Stripe\Refund::create([
                    'charge' => $payment['transactionid'],
                    'amount' =>$amount
            
                        ]);
                    $prefund = \Stripe\Refund::create([
                    'charge' => $patientpayment['transactionid'],
                    'amount' => $patientpayment['amount']+10
                    
                    ]);
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
                    $isrefund = 1;
                    $patientrefundupdate = $this->user_api_model->updatePayment($isrefund,$appointments['id']);
                        $refundupdate = $this->user_api_model->updatePaymentdoc($isrefund,$appointments['id']);
                        
                    $result= array(
                        'appointmentid' => $appointments['id'],
                        'transactionId' => $refund,
                        'amount' => $amount,
                        'patient'=>$prefund,
                    ); 
                    
                    $this->response(
                        array(
                            'status_code' => 200, 
                            'message' =>"Payment has been refunded", 
                            "result" => $result
                        ));
                }
                else
                {
                    $this->response(
                        array(
                            'status_code' => 400, 
                            'message' =>"Error in refund", 
                            "result" => $error
                        ));
    
                }
               
        }
        else
        {
            $this->response(
                array(
                    'status_code' => 400, 
                    'message' =>"payment already refunded", 
                    "result" => $payment
                )
            );
        }
    }
    function transfer_post()
    {
        $postDataArr = $this->post();
        $appointmentid=$postDataArr['appointmentid'];
        $appointments = $this->user_api_model->getappointmentdoc($appointmentid);
        // print_r($appointments);die;

    try{
        Stripe\Stripe::setVerifySslCerts(false);
                    
        if(MODE == 'test'){
                Stripe\Stripe::setApiKey(STRIPE_API_KEY);
            }
            else{
                Stripe\Stripe::setApiKey(P_STRIPE_KEY);
            }

        $transfer = \Stripe\Transfer::create([
            "amount" => 7000,
            "currency" => "clp",
            "destination" => $appointments['account'] ,
            // "transfer_group" => "{ORDER10}",
          ]);
          $success=1;
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
            'appointmentid' => $appointments['id'],
            'transactionId' => $transfer,
            'amount' => 7000,
            
        ); 
        
        $this->response(
            array(
                'status_code' => 200, 
                'message' =>"Payment has been funded", 
                "result" => $result
            ));
    }
    else
    {
        $this->response(
            array(
                'status_code' => 400, 
                'message' =>"Error in refund", 
                "result" => $error
            ));

    }
    }

    /////get automatic appointment and create charge
    public function getdoctorsbypreferenceonly_post()
    {
        // echo"hello0";
        $postDataArr = $this->post();
        $patient=$postDataArr['patientid'];
        $lat=$postDataArr['lat'];
        $long=$postDataArr['long'];
        $getdoctor = $this->user_api_model->getpreferencedoctorsonly($patient,$lat,$long);
        print_r($getdoctor);die;
        $amount=$getdoctor['consultationrate'];
        
        if($getdoctor != null)
        {
            
            $source = $postDataArr['source'];
            $appoint['patientid'] = $postDataArr['patientid'];
            $appoint['doctorid'] = $getdoctor['id'];
            $appoint['consultationreason'] = $postDataArr['consultationreason'];
            $appoint['scheduleddate'] = $postDataArr['scheduleddate'];
            $appoint['timeofarrivel'] = $postDataArr['timeofarrivel'];
            // $appoint['doctorspaciality'] = $postData['doctorspaciality'];
            // $appoint['url'] = $postData['url'];
            $dd=date('Y-m-d');
            $now = new DateTime();
            $now->setTimezone(new DateTimezone('Asia/Kolkata'));
            $dt=$now->format('H:i:s');
            $datetime = $dd . ' ' . $dt;
            $appoint['created_date'] = $datetime;
            $appoint['accept_time'] = $datetime;
            // $appoint['created_date'] = date('Y-m-d H:i:s');
            $appoint['isaccepted'] = 1;
            $appoint['isurgent'] = 1;
            try{
                // Set your secret key: remember to change this to your live secret key in production
               // See your keys here: https://dashboard.stripe.com/account/apikeys
               Stripe\Stripe::setVerifySslCerts(false);
   
               if(MODE == 'test'){
                   // echo'hi';
                   Stripe\Stripe::setApiKey(STRIPE_API_KEY);
               }
               else{
                   Stripe\Stripe::setApiKey(P_STRIPE_KEY);
               }
           
               $charge = Stripe\Charge::create(array(
                   "amount" => $amount,
                   "currency" => "clp",
                   "source" => $source,
                   "description" => "Payment for appointments",
                //    "capture" => false,
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
   
               
            
              // for saving this datat in database addition code
              $insertid = $this->user_api_model->insertappointments($appoint);
              $appointments = $this->user_api_model->getappointments($insertid);
               $c ['transactionid'] = $charge->id; 
               $c['appointmentsid']=   $appointments['id'];
               $c ['amount']   = $amount;
               $res = $this->user_api_model->createcharge($c);
               $result= array(
               
                   'appointmentid' => $appointments,
                   'transactionId' => $charge->id,
                   'amount' => $amount,
                   'doctor details' => $getdoctor,

               ); 
               
               $this->response(
                   array(
                       'status_code' => 200, 
                       'message' =>"Payement success And Appointment insert succesfully", 
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
                            'message' =>"error and appointment create failed", 
                            "result" => $result
                        )
                    );
                }

        }
        else{
            $this->response(
                array(
                    'status_code' => 400, 
                    'message' =>"doctor not found", 
                    "result" => $getdoctor
                )
            );

        }

    }
    
}