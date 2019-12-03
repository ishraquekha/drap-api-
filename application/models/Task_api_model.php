<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Task_api_model extends CI_Model
{
    /**
     * This function used to asdd a new task/jobs.
     */
    public function addNewTask($taskInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_task', $taskInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    /**
     * This function used to get all task/jobs posted
     */
    function getAllTasksInfo($userId, $latitude, $longitude, $distanceRange)
    {
        $this->db->select('tbl_task.*, tbl_users.Name as addedUser, tbl_users.Email addedUserEmail, tbl_users.profilepic as addedUserProfile,
        tbl_users.phone as addedUserPhone, c.Id as CategoryId, c.Title CategoryTitle,
        (SELECT COUNT(1) 
            FROM taskuserrequest 
            WHERE taskuserrequest.taskId = tbl_task.taskId) as requestCount,
        (SELECT (3956 * 2 * ASIN(SQRT( POWER(SIN(('.$latitude.' - latitude) * pi()/180 / 2), 2) + 
            COS('.$latitude.' * pi()/180) * COS(latitude * pi()/180) * POWER(SIN(('.$longitude.' - longitude) * pi()/180 / 2), 2) )))) as distance');
    
        
        $this->db->from('tbl_task');
        $this->db->join('tbl_users', 'tbl_users.userId = tbl_task.userId');
        $this->db->join('tbl_category c', 'tbl_task.categoryId = c.Id');
        $this->db->where('tbl_task.isActive', 1);
        $this->db->where('tbl_task.isDeleted', 0);
        $this->db->having('distance < '.$distanceRange);
        $this->db->order_by("createdDate", "desc");
        $query = $this->db->get();
        //print_r($query->result_array());die;
        return $query->result_array();
    }

    /**
     * This function used to get all task/jobs posted
     */
    function getCustomerTasks($userId)
    {
        $this->db->select('tbl_task.*, c.Id AS CategoryId, c.Title AS CategoryTitle');
    
        $this->db->from('tbl_task');
        $this->db->join('tbl_category c', 'tbl_task.categoryId = c.Id');
        $this->db->where('tbl_task.isActive', 1);
        $this->db->where('tbl_task.isDeleted', 0);
        $this->db->where('tbl_task.userId', $userId);
        $this->db->order_by("tbl_task.createdDate", "desc");
        $query = $this->db->get();
        
        return $query->result_array();
    }
    
        /**
     * This function used to get all task/jobs posted
     */
    function getGetTaskApplications($userId)
    {
        $this->db->select('tbl_users.*');
        $this->db->from('taskuserrequest');
        $this->db->join('tbl_users', 'tbl_users.userId = taskuserrequest.userId');
        $this->db->where('taskuserrequest.isActive', 1);
        $this->db->where('taskuserrequest.isDeleted', 0);
        $this->db->where('tbl_users.isActive', 1);
        $this->db->where('tbl_users.isDeleted', 0);
        $this->db->where('taskuserrequest.userId', $userId);
        $query = $this->db->get();
        
        return $query->result_array();
    }

    function getUserInfo($userId)
    {
        $this->db->select('tbl_users.userId, tbl_users.email, tbl_users.name, 
        tbl_users.phone, tbl_users.profilepic, tbl_users.dob, tbl_users.address,
        latitude, longitude,
        (SELECT AVG(Rating) FROM tbl_reviews WHERE UserId = '.$userId.') AS Rating');
        $this->db->from('tbl_users');
        $this->db->where('tbl_users.isActive', 1);
        $this->db->where('tbl_users.isDeleted', 0);
		$this->db->where('userId', $userId);
        $query = $this->db->get();

        $this->db->select('tbl_task.taskId, tbl_task.title, tbl_task.description, 
        tbl_task.cost, tbl_task.toDate as expiryDate, tbl_task.tools, tbl_task.latitude, 
        tbl_task.longitude, tbl_task.timeToComplete, c.Id AS CategoryId, c.Title AS CategoryTitle');
        $this->db->from('tbl_task');
        $this->db->join('tbl_category c', 'tbl_task.categoryId = c.Id');
        $this->db->join('tbl_taskassigned', 'tbl_taskassigned.taskId = tbl_task.taskId');
        $this->db->where('tbl_task.isActive', 1);
        $this->db->where('tbl_task.isDeleted', 0);
        $this->db->where('tbl_taskassigned.isCompleted', 1);
        $this->db->where('tbl_taskassigned.userId', $userId);
        $queryTask = $this->db->get();

        $userDetails['user'] = $query->row_array();
        //$profilePic = base_url().'assets/upload/user/'.$userDetails['user']['profilepic'];
        //$userDetails['user']['profilepic'] = base_url().'assets/upload/user/'.$row['profilepic'];
        $userDetails['completedtask'] = $queryTask->result_array();
        
        return $userDetails;
    }
    
    function getTaskDetails($taskId)
    {
        $this->db->select('tbl_task.taskId, tbl_task.title, tbl_task.description, 
        tbl_task.cost, tbl_task.toDate as expiryDate, tbl_task.tools, tbl_task.latitude, 
        tbl_task.longitude, tbl_task.timeToComplete, c.Id AS CategoryId, c.Title AS CategoryTitle');
        $this->db->from('tbl_task');
        $this->db->join('tbl_category c', 'tbl_task.categoryId = c.Id');
        $this->db->where('tbl_task.isActive', 1);
        $this->db->where('tbl_task.isDeleted', 0);
        $this->db->where('tbl_task.taskId', $taskId);
        $queryTask = $this->db->get();

        $this->db->select('tbl_users.userId, tbl_users.email, tbl_users.name, 
        tbl_users.phone, tbl_users.profilepic,
        taskuserrequest.createdDate as appliedDateTime');
        $this->db->from('taskuserrequest');
        $this->db->join('tbl_users', 'tbl_users.userId = taskuserrequest.userId');
        $this->db->where('taskuserrequest.isActive', 1);
        $this->db->where('taskuserrequest.isDeleted', 0);
        $this->db->where('tbl_users.isActive', 1);
        $this->db->where('tbl_users.isDeleted', 0);
        $this->db->where('taskuserrequest.taskId', $taskId);
        $queryUser = $this->db->get();
        $applicantsArray = $queryUser->result_array();
        $taskDetails = $queryTask->row_array();
        $taskDetails['applicants'] = array();
        if(count($applicantsArray) > 0){
            foreach($applicantsArray as $row){
                $row['profilepic'] = base_url().'assets/upload/user/'.$row['profilepic'];
                array_push($taskDetails['applicants'],  $row);
            }
        }
        return $taskDetails;
    }

        /**
     * This function used to offer task/jobs to service provider.
     */
    public function offerTask($taskInfo)
    {
        $result = $this->checkIfTaskIsAlreadyAssigned($taskInfo['taskId'], $taskInfo['userId']);
        if(count($result)>0)
        {
            $this->db->set('status',$taskInfo['status']);
            $this->db->where('userId',$taskInfo['userId']);
            $this->db->where('taskId',$taskInfo['taskId']);
            $this->db->update('tbl_tasksssigned');
           
        }
        else
        {
            $this->db->trans_start();
            $this->db->insert('tbl_taskassigned', $taskInfo);
            
            $insert_id = $this->db->insert_id();
            
            $this->db->trans_complete();
            
        }

        $this->db->set('isAssigned', $taskInfo['status']);
        $this->db->where('taskId',$taskInfo['taskId']);
        $this->db->update('tbl_task');
        
        return $insert_id;
    }

    /**
     * This function used to get all task/jobs posted
     */
    function getAssignedAndCompletedTasks($userId)
    {
        $this->db->select('tbl_task.taskId, tbl_task.title, tbl_task.description, 
        tbl_task.cost, tbl_task.toDate as expiryDate, tbl_task.tools, tbl_task.latitude, 
        tbl_task.longitude, tbl_task.timeToComplete, tbl_users.Name as addedUser, 
        tbl_users.Email addedUserEmail, tbl_users.profilepic as addedUserProfile,
        tbl_users.phone as addedUserPhone, c.Id AS CategoryId, c.Title AS CategoryTitle');
    
        $this->db->from('tbl_task');
        $this->db->join('tbl_category c', 'tbl_task.categoryId = c.Id');
        $this->db->join('tbl_users', 'tbl_users.userId = tbl_task.userId');
        $this->db->where('tbl_task.isCompleted', 1);
        $this->db->where('tbl_task.userId', $userId);
        $this->db->order_by("tbl_task.createdDate", "desc");
        $queryCompleted = $this->db->get();

        $this->db->select('tbl_task.taskId, tbl_task.title, tbl_task.description, 
        tbl_task.cost, tbl_task.toDate as expiryDate, tbl_task.tools, tbl_task.latitude, 
        tbl_task.longitude, tbl_task.timeToComplete, tbl_users.Name as addedUser, 
        tbl_users.Email addedUserEmail, tbl_users.profilepic as addedUserProfile,
        tbl_users.phone as addedUserPhone, c.Id AS CategoryId, c.Title AS CategoryTitle');
    
        $this->db->from('tbl_task');
        $this->db->join('tbl_category c', 'tbl_task.categoryId = c.Id');
        $this->db->join('tbl_users', 'tbl_users.userId = tbl_task.userId');
        $this->db->where('tbl_task.isAssigned', 1);
        $this->db->where('tbl_task.isCompleted', 0);
        $this->db->where('tbl_task.userId', $userId);
        $this->db->order_by("tbl_task.createdDate", "desc");
        $queryAssigned = $this->db->get();
        //print_r($query->result_array());die;

        $this->db->select('tbl_task.taskId, tbl_task.title, tbl_task.description, 
        tbl_task.cost, tbl_task.toDate as expiryDate, tbl_task.tools, tbl_task.latitude, 
        tbl_task.longitude, tbl_task.timeToComplete, tbl_users.Name as addedUser, 
        tbl_users.Email addedUserEmail, tbl_users.profilepic as addedUserProfile,
        tbl_users.phone as addedUserPhone, c.Id AS CategoryId, c.Title AS CategoryTitle');
    
        $this->db->from('tbl_task');
        $this->db->join('tbl_category c', 'tbl_task.categoryId = c.Id');
        $this->db->join('tbl_users', 'tbl_users.userId = tbl_task.userId');
        $this->db->join('tbl_taskassigned', 'tbl_taskassigned.taskId = tbl_task.taskId');
        $this->db->where('tbl_taskassigned.isWorking', 1);
        $this->db->where('tbl_taskassigned.isCompleted', 0);
        $this->db->where('tbl_task.userId', $userId);
        $this->db->order_by("tbl_task.createdDate", "desc");
        $queryCurrent = $this->db->get();
        
        $taskList = array();
        $taskList['upcoming'] = array();
        $taskList['current'] = array();
        $taskList['completed'] = array();

        $assignedArray = $queryAssigned->result_array();
        $currentArray = $queryCurrent->result_array();
        $completedArray = $queryCompleted->result_array();
        if(count($assignedArray) > 0){
            foreach($assignedArray as $row){
                $row['profilepic'] = base_url().'assets/upload/user/'.$row['addedUserProfile'];
                array_push($taskList['upcoming'],  $row);
            }
        }

        if(count($completedArray) > 0){
            foreach($completedArray as $row){
                $row['profilepic'] = base_url().'assets/upload/user/'.$row['addedUserProfile'];
                array_push($taskList['completed'],  $row);
            }
        }
        
        if(count($currentArray) > 0){
            foreach($currentArray as $row){
                $row['profilepic'] = base_url().'assets/upload/user/'.$row['addedUserProfile'];
                array_push($taskList['current'],  $row);
            }
        }
        return $taskList;
    }


    /**
     * This function used to get all task/jobs posted
     */
    function getCustomersOngoingAndCompletedTasks($userId)
    {
        $this->db->select('tbl_task.taskId, tbl_task.title, tbl_task.description, 
        tbl_task.cost, tbl_task.toDate as expiryDate, tbl_task.tools, tbl_task.latitude, 
        tbl_task.longitude, tbl_task.timeToComplete, tbl_users.Name as workUser, 
        tbl_users.Email workUserEmail, tbl_users.profilepic as workUserProfile,
        tbl_users.phone as workUserPhone, c.Id AS CategoryId, c.Title AS CategoryTitle');
    
        $this->db->from('tbl_task');
        $this->db->join('tbl_category c', 'tbl_task.categoryId = c.Id');
        $this->db->join('tbl_taskassigned', 'tbl_taskassigned.taskId = tbl_task.taskId');
        $this->db->join('tbl_users', 'tbl_users.userId = tbl_taskassigned.userId');
        $this->db->where('tbl_task.isCompleted', 1);
        $this->db->where('tbl_task.userId', $userId);
        $this->db->order_by("tbl_task.createdDate", "desc");
        $queryCompleted = $this->db->get();

        $this->db->select('tbl_task.taskId, tbl_task.title, tbl_task.description, 
        tbl_task.cost, tbl_task.toDate as expiryDate, tbl_task.tools, tbl_task.latitude, 
        tbl_task.longitude, tbl_task.timeToComplete, tbl_users.Name as workUser, 
        tbl_users.Email workUserEmail, tbl_users.profilepic as workUserProfile,
        tbl_users.phone as workUserPhone, c.Id AS CategoryId, c.Title AS CategoryTitle');
    
        $this->db->from('tbl_task');
        $this->db->join('tbl_category c', 'tbl_task.categoryId = c.Id');
        $this->db->join('tbl_taskassigned', 'tbl_taskassigned.taskId = tbl_task.taskId');
        $this->db->join('tbl_users', 'tbl_users.userId = tbl_taskassigned.userId');
        $this->db->where('tbl_taskassigned.isWorking', 1);
        $this->db->where('tbl_taskassigned.isCompleted', 0);
        $this->db->where('tbl_task.userId', $userId);
        $this->db->order_by("tbl_task.createdDate", "desc");
        $queryCurrent = $this->db->get();
        
        $taskList = array();
        $taskList['ongoing'] = array();
        $taskList['completed'] = array();

        $currentArray = $queryCurrent->result_array();
        $completedArray = $queryCompleted->result_array();
        
        if(count($completedArray) > 0){
            foreach($completedArray as $row){
                $row['profilepic'] = base_url().'assets/upload/user/'.$row['workUserProfile'];
                array_push($taskList['completed'],  $row);
            }
        }
        
        if(count($currentArray) > 0){
            foreach($currentArray as $row){
                $row['profilepic'] = base_url().'assets/upload/user/'.$row['workUserProfile'];
                array_push($taskList['ongoing'],  $row);
            }
        }
        return $taskList;
    }


    /**
     * This function used to get all task/jobs posted
     */
    function searchTasks($userId, $latitude, $longitude, $distanceRange, $searchText)
    {
        $this->db->select('tbl_task.*, tbl_users.Name as addedUser, tbl_users.Email addedUserEmail, tbl_users.profilepic as addedUserProfile,
        tbl_users.phone as addedUserPhone, c.Id AS CategoryId, c.Title AS CategoryTitle,
        (SELECT COUNT(1) 
            FROM taskuserrequest 
            WHERE taskuserrequest.taskId = tbl_task.taskId) as requestCount,
        (SELECT (3956 * 2 * ASIN(SQRT( POWER(SIN(('.$latitude.' - latitude) * pi()/180 / 2), 2) + 
            COS('.$latitude.' * pi()/180) * COS(latitude * pi()/180) * POWER(SIN(('.$longitude.' - longitude) * pi()/180 / 2), 2) )))) as distance');
    
        
        $this->db->from('tbl_task');
        $this->db->join('tbl_category c', 'tbl_task.categoryId = c.Id');
        $this->db->join('tbl_users', 'tbl_users.userId = tbl_task.userId');
        $this->db->where('tbl_task.isActive', 1);
        $this->db->where('tbl_task.isDeleted', 0);
        $this->db->like('tbl_task.title', $searchText);
        $this->db->or_like('result_arraytbl_task.description', $searchText);
        $this->db->having('distance < '.$distanceRange);
        $this->db->order_by("createdDate", "desc");
        $query = $this->db->get();
        //print_r($query->result_array());die;
        return $query->result_array();
    }

            /**
     * This function used to offer task/jobs to service provider.
     */
    public function startAndEndTask($taskInfo)
    {
        $result = $taskInfo['isStart'];
        if($result > 0)
        {
            $this->db->set('isWorking',1);
            $this->db->set('isCompleted', 0);
            $this->db->where('userId',$taskInfo['serviceProviderId']);
            $this->db->where('taskId',$taskInfo['taskId']);
            $this->db->where('status', 1);
            $this->db->update('tbl_taskassigned');

            $this->db->set('isCompleted', 0);
            $this->db->where('taskId',$taskInfo['taskId']);
            $this->db->update('tbl_task');

            return true;
        }
        else
        {
            $this->db->set('isWorking',1);
            $this->db->set('isCompleted', 1);
            $this->db->where('userId',$taskInfo['serviceProviderId']);
            $this->db->where('taskId',$taskInfo['taskId']);
            $this->db->where('status', 1);
            $this->db->update('tbl_taskassigned');

            $this->db->set('isCompleted', 1);
            $this->db->where('taskId',$taskInfo['taskId']);
            $this->db->update('tbl_task');

            return true;
        }
        
        return false;
    }


        /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function checkIfTaskIsAlreadyAssigned($taskId, $userId)
    {
        $this->db->select('*');
        $this->db->from('tbl_taskassigned');
        $this->db->where('userId', $userId);
		$this->db->where('taskId', $taskId);
		$this->db->where('status', 1);
        $query = $this->db->get();
        
        return $query->result();
    }

    function getCategories()
    {
        $this->db->select('Id AS CategoryId, Title AS CategoryTitle');
        $this->db->from('tbl_category');
		$this->db->where('isActive', 1);
		$this->db->where('isDeleted', 0);
        $query = $this->db->get();
        
        return $query->result_array();
    }
}

?>