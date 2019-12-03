<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    require(APPPATH.'/libraries/REST_Controller.php');

    class Message_api extends REST_Controller
    {
        public function __construct() {
            parent::__construct();
            $this->load->language('common');
            $this->load->model('message_api_model');
            $this->datetime = date('Y-m-d H:i:s');
        }

        public function getAllMessagesForMainHomeScreen_get($userId){
            $result = $this->message_api_model->getAllMessagesForMainHomeScreen($userId);

            if(count($result)>0)
            {
                $this->response(array(
                        'status_code' => 200, 
                        'message' => "Task list!",
                        'result' => $result
                    ));
            }
            else
            {
                $this->response(array('status_code' => 0, 'message' => "No Messages Found!"));
            }
        }

        public function getChat_get($receiverId, $senderId){
            //echo $receiverId .'--'. $senderId;
            $result = $this->message_api_model->getChat($receiverId, $senderId);

            if(count($result)>0)
            {
                $this->response(array(
                        'status_code' => 200, 
                        'message' => "Chat list!",
                        'result' => $result
                    ));
            }
            else
            {
                $this->response(array('status_code' => 0, 'message' => "No Messages Found!"));
            }
        }

        public function sendMessage_post(){
            $postedArr = $this->post();

            $messageDetails = array();
            $messageDetails['ReceiverId'] = $postedArr['receiverId'];
            $messageDetails['SenderId'] = $postedArr['senderId'];
            $messageDetails['taskId'] = $postedArr['taskId'];
            $messageDetails['MessageText'] = $postedArr['messageText'];
            $messageDetails['IsRead'] = 0;

            $result = $this->message_api_model->sendMessage($messageDetails);
            if($result > 0)
            {
                $resultArr = $this->message_api_model->getChat($messageDetails['ReceiverId'], $messageDetails['SenderId']);
                $this->response(array(
                        'status_code' => 200, 
                        'message' => "Message sent succesfully!",
                        'result' => $resultArr
                    ));
            }
            else
            {
                $this->response(array('status_code' => 0, 'message' => "Message couldn't send at this moment, Please try again later!!"));
            }
        }

        public function setMessageReadStatus_post(){

            $postedArr = $this->post();
            $receiverId = $postedArr['receiverId']; 
            $senderId = $postedArr['senderId'];
            $taskId = $postedArr['taskId'];

            //echo $receiverId .'--'. $senderId;
            $result = $this->message_api_model->setMessageReadStatus($receiverId, $senderId, $taskId);

            if($result != null)
            {
                $this->response(array(
                        'status_code' => 200, 
                        'message' => "success",
                        'result' => array()
                    ));
            }
            else
            {
                $this->response(
                    array(
                        'status_code' => 0, 
                        'message' => "No Messages Found!", 
                        'result' => array()
                    )
                );
            }   
        }

        public function getMoreChat_get($receiverId, $senderId, $pageNumber){
            //echo $receiverId .'--'. $senderId;
            $result = $this->message_api_model->getMoreChat($receiverId, $senderId, $pageNumber);

            if(count($result)>0)
            {
                $this->response(array(
                        'status_code' => 200, 
                        'message' => "Chat list!",
                        'result' => $result
                    ));
            }
            else
            {
                $this->response(array('status_code' => 0, 'message' => "No Messages Found!"));
            }
        }
    }
?>