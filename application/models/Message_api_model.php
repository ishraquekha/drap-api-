<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Message_api_model extends CI_Model
{
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
        /**
     * This function used to get message list 
     */
    function getAllMessagesForMainHomeScreen($userId)
    {
        $query = $this->db->query('SELECT m.Id, m.SenderId, m.ReceiverId, m.MessageText, m.ConversationId,
                                     m.CreatedDate, CASE WHEN u2.userId = '.$userId.' THEN u1.name
                                                            ELSE u2.name
                                                            END as ParticipantName,
                                                            CASE WHEN u2.userId = '.$userId.' THEN u1.ProfilePic
                                                            ELSE u2.ProfilePic
                                                            END as ParticipantProfilePic,
                                                            CASE WHEN u2.userId = '.$userId.' THEN u1.userId
                                                            ELSE u2.userId
                                                            END as ParticipantUserId,
                                                            m.IsRead,
                                                            t.title as TaskName,
                                                            t.taskId
                        FROM tbl_messages m
                        JOIN tbl_users u1 ON m.SenderId=u1.userId
                        JOIN tbl_users u2 ON m.ReceiverId=u2.userId
                        RIGHT JOIN tbl_task t on t.taskId = m.taskId
                        WHERE m.Id IN (
                        SELECT MAX(id)
                        FROM tbl_messages
                        WHERE SenderId = '.$userId.' OR ReceiverId = '.$userId.'
                        GROUP BY ConversationId, t.taskId) ORDER BY m.CreatedDate DESC');

        $messageArray = $query->result_array();
       
        return $messageArray;
    }


    function getChat($receiverId, $senderId) {
        $limit = 25;
        $start = 0;
        $this->db->select('rUser.userId AS ReceiverId, rUser.name AS ReceiverName, rUser.ProfilePic AS ReceiverProfilePic');
        $this->db->from('tbl_users rUser');
        $this->db->where('rUser.userId',$receiverId);
        $queryR = $this->db->get();

        $this->db->select('sUser.userId AS SenderId, sUser.name AS SenderName, sUser.ProfilePic AS SenderProfilePic');
        $this->db->from('tbl_users sUser');
        $this->db->where('sUser.userId',$senderId);
        $queryS = $this->db->get();

        $this->db->select('msg.Id, msg.MessageText, msg.CreatedDate, 0 AS IsSend, msg.ConversationId');
        $this->db->from('tbl_messages msg');
        $this->db->where('msg.receiverId',$receiverId);
        $this->db->where('msg.senderId',$senderId);
        $queryMessageR = $this->db->get_compiled_select(); 
        
        $this->db->select('msg.Id, msg.MessageText, msg.CreatedDate, 1 AS IsSend, msg.ConversationId');
        $this->db->from('tbl_messages msg');
        $this->db->where('msg.receiverId',$senderId);
        $this->db->where('msg.senderId',$receiverId);
        $queryMessageS = $this->db->get_compiled_select(); 

        $query = $this->db->query($queryMessageR." UNION ".$queryMessageS. "ORDER BY CreatedDate LIMIT ".$start.",".$limit);

        $messageDetails = array();
        $messageDetails['ReceiverDetails'] = $queryR->row_array();

        $profilePic = $messageDetails['ReceiverDetails']['ReceiverProfilePic'];
        if($profilePic != null){
            $messageDetails['ReceiverDetails']['ReceiverProfilePic'] = base_url().'assets/upload/user/'.$profilePic;    
        }

        $messageDetails['SenderDetails'] = $queryS->row_array();
        $profilePic = $messageDetails['SenderDetails']['SenderProfilePic'];
        if($profilePic != null){
            $messageDetails['SenderDetails']['SenderProfilePic'] = base_url().'assets/upload/user/'.$profilePic;    
        }
        
        $messageDetails['Messages'] = array();
        $messageDetails['Messages'] = $query->result_array();
        
        return $messageDetails;
    }

    function sendMessage($messageDetails){
        $this->db->trans_start();
        $conversation = '';
        $conversation = $this->getConversationId($messageDetails['ReceiverId'],$messageDetails['SenderId'],$messageDetails['taskId']);
        
        if($conversation['ConversationId'] != null || $conversation['ConversationId'] != '')
        {
            $messageDetails['ConversationId']  = $conversation['ConversationId'];
            $this->db->insert('tbl_messages', $messageDetails);
            $insert_id = $this->db->insert_id();
        } 
        else {
            $sql = "INSERT INTO tbl_messages (MessageText, ReceiverId, SenderId, taskId, ConversationId) 
                    VALUES ('".$messageDetails['MessageText']."', ".$messageDetails['ReceiverId'].",".$messageDetails['SenderId'].",".$messageDetails['taskId'].",(SELECT LEFT(UUID(), 8)))";
            $this->db->query($sql);
            $insert_id = $this->db->insert_id();
        }
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function setMessageReadStatus($receiverId, $senderId, $taskId){
        $this->db->trans_start();
        $this->db->set('IsRead', 1);
        $this->db->where('SenderId', $senderId);
        $this->db->where('ReceiverId', $receiverId);
        $this->db->where('taskId', $taskId);
        $this->db->where('IsActive', 1);
        $status = $this->db->update('tbl_messages');
        
        $this->db->trans_complete();
        
        return $status;
    }

    function getMoreChat($receiverId, $senderId, $pageNumber){
        $limit = 25;
        $start = $limit * $pageNumber;
       
        $this->db->select('msg.Id, msg.MessageText, msg.CreatedDate, 0 AS IsSend, msg.ConversationId');
        $this->db->from('tbl_messages msg');
        $this->db->where('msg.receiverId',$receiverId);
        $this->db->where('msg.senderId',$senderId);
        $queryMessageR = $this->db->get_compiled_select(); 
        
        $this->db->select('msg.Id, msg.MessageText, msg.CreatedDate, 1 AS IsSend, msg.ConversationId');
        $this->db->from('tbl_messages msg');
        $this->db->where('msg.receiverId',$senderId);
        $this->db->where('msg.senderId',$receiverId);
        $queryMessageS = $this->db->get_compiled_select(); 

        $query = $this->db->query($queryMessageR." UNION ".$queryMessageS. "ORDER BY CreatedDate LIMIT ".$start.",".$limit);

        $messageDetails = array();
       
        $messageDetails['Messages'] = array();
        $messageDetails['Messages'] = $query->result_array();
        
        return $messageDetails;
    }

    function getConversationId($receiverId, $senderId, $taskId){
        $this->db->select('msg.ConversationId');
        $this->db->from('tbl_messages msg');
        $this->db->where('msg.receiverId',$receiverId);
        $this->db->where('msg.senderId',$senderId);
        $this->db->where('msg.taskId',$taskId);
        $query = $this->db->get();
        return $query->row_array();
    }
}
