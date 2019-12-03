<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Task_api extends REST_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->language('common');
        $this->load->model('task_api_model');
        $this->datetime = date('Y-m-d H:i:s');
    }
    
    private function Authorized() {
        $request = $this->head();
        if (isset($request['Authtoken']) && !empty($request['Authtoken'])) {
            return $request['Authtoken'];
        } else {
            return "not authenticated";
        }
    }

    function createTask_post()
    {
        $postDataArr = $this->post();
        if ($postDataArr) 
        {
            $this->load->helper('date');

            $taskInfo['title'] = $postDataArr['title'];
            $taskInfo['description'] = $postDataArr['description'];
            $taskInfo['cost'] = $postDataArr['cost'];
            $taskInfo['toDate'] = date('Y-m-d G:i:s', strtotime($postDataArr['expiryDate']));
            $taskInfo['tools'] = $postDataArr['tools'];
            $taskInfo['latitude'] = $postDataArr['latitude'];
            $taskInfo['longitude'] = $postDataArr['longitude'];
            $taskInfo['timeToComplete'] = $postDataArr['timeToComplete'];
            $taskInfo['categoryId'] = $postDataArr['categoryId'];
            $taskInfo['userId'] = $postDataArr['userId'];

            $taskInfo['createdDate'] = date('Y-m-d h:i:s');
            $taskId = $this->task_api_model->addNewTask($taskInfo);
            $result = array('taskId' => $taskId);
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Task added succesfull.", 
                    "result" => $result
                )
            );
        
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

    function getAllTasks_get($latitude, $longitude, $searchRangeInMiles, $userId)
    {
        //echo $latitude;echo  $longitude;echo  $searchRangeInMiles;die;
        if($latitude == null){
            $latitude = 0;
        }
        if($longitude == null){
            $longitude = 0;
        }
        if($searchRangeInMiles == null){
            $searchRangeInMiles =   1000;
        }
        $result = $this->task_api_model->getAllTasksInfo($userId,$latitude, $longitude, $searchRangeInMiles);

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
            $this->response(array('status_code' => 0, 'message' => "No Task Found!"));
        }
    }

    function getCustomerTasks_get($userId)
    {
        $result = $this->task_api_model->getCustomerTasks($userId);

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
            $this->response(array('status_code' => 0, 'message' => "No Task Found!"));
        }
    }

    function getGetTaskApplications_get($userId)
    {
        $result = $this->task_api_model->getGetTaskApplications($userId);

        if(count($result)>0)
        {
            $this->response(array(
                    'status_code' => 200, 
                    'message' => "Applicants list!",
                    'result' => $result
                ));
        }
        else
        {
            $this->response(array('status_code' => 0, 'message' => "No data Found!"));
        }
    }

    function getProviderProfile_get($userId)
    {
        $result = $this->task_api_model->getUserInfo($userId);

        if(count($result)>0)
        {
            $this->response(array(
                    'status_code' => 200, 
                    'message' => "Providers list!",
                    'result' => $result
                ));
        } 
        else
        {
            $this->response(array('status_code' => 0, 'message' => "No data Found!"));
        }
    }

    function getTaskDetails_get($taskId){
        $result = $this->task_api_model->getTaskDetails($taskId);

        if(count($result)>0)
        {
            $this->response(array(
                    'status_code' => 200, 
                    'message' => "Providers list!",
                    'result' => $result
                ));
        }
        else
        {
            $this->response(array('status_code' => 0, 'message' => "No data Found!"));
        }
    }

    function offerTaskToServiceProvider_post()
    {
        $postDataArr = $this->post();
        if ($postDataArr) 
        {
            $taskInfo['taskId'] = $postDataArr['taskId'];
            $taskInfo['userId'] = $postDataArr['userId'];
            $taskInfo['status'] = $postDataArr['status'];

            $this->task_api_model->OfferTask($taskInfo);
            $result = array();
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Task offered succesfully.", 
                    "result" => $result
                )
            );
        }
    }

    function getAssignedAndCompletedTasks_get($userId)
    {
        $result = $this->task_api_model->getAssignedAndCompletedTasks($userId);

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
            $this->response(array('status_code' => 0, 'message' => "No Task Found!"));
        }
    }

    function getCustomersOngoingAndCompletedTasks_get($userId)
    {
        $result = $this->task_api_model->getCustomersOngoingAndCompletedTasks($userId);

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
            $this->response(array('status_code' => 0, 'message' => "No Task Found!"));
        }
    }

    function searchTasks_get($latitude, $longitude, $searchRangeInMiles, $userId, $searchText)
    {
        //echo $latitude;echo  $longitude;echo  $searchRangeInMiles;die;
        if($latitude == null){
            $latitude = 0;
        }
        if($longitude == null){
            $longitude = 0;
        }
        if($searchRangeInMiles == null){
            $searchRangeInMiles =   1000;
        }
        if($searchText == null){
            $searchText = "";
        }

        $result = $this->task_api_model->searchTasks($userId, $latitude, $longitude, $searchRangeInMiles, $searchText);

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
            $this->response(array('status_code' => 0, 'message' => "No Task Found!"));
        }
    }

    function startAndEndTask_post(){
        $postDataArr = $this->post();
        if ($postDataArr) 
        {
            $taskInfo['taskId'] = $postDataArr['taskId'];
            $taskInfo['isStart'] = $postDataArr['isStart'];
            $taskInfo['serviceProviderId'] = $postDataArr['serviceProviderId'];

            $this->task_api_model->startAndEndTask($taskInfo);
            $result = array();

            if($postDataArr['isStart'] > 0){
                $this->response(
                    array(
                        'status_code' => 200, 
                        'message' =>"Task started succesfully.", 
                        "result" => $result
                    )
                );
            }
            else {
                $this->response(
                    array(
                        'status_code' => 200, 
                        'message' =>"Task ended succesfully.", 
                        "result" => $result
                    )
                );
            }
        }
    }

    function getCategories_get()
    {
        $result = $this->task_api_model->getCategories();

        if(count($result)>0)
        {
            $this->response(array(
                    'status_code' => 200, 
                    'message' => "Category list!",
                    'result' => $result
                ));
        }
        else
        {
            $this->response(array('status_code' => 0, 'message' => "No data found!"));
        }
    }

    function setPrefferedSettings_post(){
        $postDataArr = $this->post();
        if ($postDataArr) 
        {
            $taskInfo['userId'] = $postDataArr['userId'];
            $taskInfo['price'] = $postDataArr['price'];
            $taskInfo['distance'] = $postDataArr['distanceInMiles'];
            $taskInfo['categoryIds'] = $postDataArr['categoryIds'];

            $this->task_api_model->startAndEndTask($taskInfo);
            $result = array();

            if($postDataArr['isStart'] > 0){
                $this->response(
                    array(
                        'status_code' => 200, 
                        'message' =>"Task started succesfully.", 
                        "result" => $result
                    )
                );
            }
            else {
                $this->response(
                    array(
                        'status_code' => 200, 
                        'message' =>"Task ended succesfully.", 
                        "result" => $result
                    )
                );
            }
        }
    }
}

?>