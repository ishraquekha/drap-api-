<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Model extends CI_Model
{
    
    public function __construct() 
    {
          parent::__construct(); 
          $this->load->database();
    }


    function admincheak($email, $password)
    {
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where('userName', $email);
		$this->db->where('password', $password);
        $query = $this->db->get();
    
        return $query->row_array();
    }

    function getdoctorsdata()
    {
        $this->db->select('*');
        $this->db->from('doctor');
        $this->db->where('isverified', 1);
        $this->db->where('isactive', 1);
        $this->db->order_by('updated_at', 'desc');
        $query = $this->db->get();

        return $query->result_array();
    }

    function getdoctorbyid($id)
    {
        $this->db->select('*');
        $this->db->from('doctor');
        // $this->db->where('isverified', 1);
        $this->db->where('id', $id);
        // $this->db->order_by('updated_at', 'desc');
        $query = $this->db->get();

        return $query->row_array();
    }

    function getupdatedrequest($id)
    {
        $this->db->select('*');
        $this->db->from('doctoredithistory');
        $this->db->where('dr_id', $id);
        $query = $this->db->get();

        return $query->row_array();
    }

    function getdoctorsrequest()
    {
        $this->db->select('*');
        $this->db->from('doctor');
        $this->db->where('isverified', 0);
        $this->db->where('isactive', 1);
        $this->db->order_by('updated_at', 'desc');
        $query = $this->db->get();

        return $query->result_array();
    }

    function gettransactionsdata()
    {
        $this->db->select('*');
        $this->db->from('transaction');
        $this->db->where('isactive', 1);
        $this->db->order_by('updated_at', 'desc');
        $query = $this->db->get();

        return $query->result_array();
    }
    
    function getpatientsdata()
    {
        $this->db->select('*');
        $this->db->from('patient');
        $this->db->where('IsActive', 1);
        $this->db->order_by('updated_at', 'desc');
        $query = $this->db->get();

        return $query->result_array();
    }

    function getpatientsmedicaldata()
    {
        $this->db->select('*');
        $this->db->from('patientmedicalinformation');
        $query = $this->db->get();

        return $query->result_array();
    }
    
    function getdoctordetails($id)
    {
        $this->db->select('*,D.id,DS.spacialistid,sp.spacialist');
        $this->db->where('D.id', $id);
        $this->db->where('D.isactive', 1);
        $this->db->from('doctor as D');
        $this->db->join('doctorspacializations as DS','DS.doctorsid = D.id','left');
        $this->db->join('spacializations as sp','sp.id=DS.spacialistid','left');
        $query = $this->db->get();

        return $query->row_array();
    }

    function getpatientdetails($id)
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->where('isactive', 1);
        $this->db->from('patient');
        $query = $this->db->get();

        return $query->row_array();
    }

    function getpatientmedicaldetails($id)
    {
        $this->db->select('*');
        $this->db->where('patientid', $id);
        $this->db->where('isactive', 1);
        $this->db->from('patientmedicalinformation');
        $query = $this->db->get();

        return $query->row_array();
    }

    function updatedoctor($updatedoctor, $id, $date)
    {
        $this->db->set($updatedoctor);
        $this->db->set('updated_at', $date);
        $this->db->set('updaterequest', 0);
        $this->db->where('id',$id);
        $query = $this->db->update('doctor');

        return $query;
    }
    ////////////
    function checkspecial($id)
    {
        $this->db->select('*');
        $this->db->where('doctorsid', $id);
        $this->db->from('doctorspacializations');
        $query = $this->db->get();

        return $query->row_array();   
    }

    function updatespeciality($id,$specialid)
    {
        // print_r($specialid['spacialistid']);die;
        $this->db->set('spacialistid',$specialid['spacialistid']);
        $this->db->where('doctorsid',$id);
        $query = $this->db->update('doctorspacializations');
        return $query;
    }
    function insertspeciality($arr)
    {
        $query=$this->db->insert('doctorspacializations', $arr);
        
        return $query;
        // $insert_id = $this->db->insert_id();
    }
    /////////

    function deletedoctor($id)
    {
        $this->db->set('isactive', 0);
        $this->db->set('isdeleted', 1);
        $this->db->where('id', $id);
        $query = $this->db->update('doctor');

        return $query;
    }

    function deletepatient($id)
    {
        $this->db->set('isactive', 0);
        $this->db->set('isdeleted', 1);
        $this->db->where('id', $id);
        $query = $this->db->update('patient');

        return $query;
    }

    function deactivate($id)
    {
        $this->db->set('isverified', 0);
        $this->db->where('id', $id);
        $query = $this->db->update('doctor');

        return $query;
    }

    function updatepatient($updatepatient, $id)
    {
        $this->db->set($updatepatient);
        $this->db->where('id', $id);
        $query = $this->db->update('patient');

        return $query;
    }

    function updatepatientmedical($updatepatientmedical, $id)
    {
        $this->db->set($updatepatientmedical);
        $this->db->where('patientid', $id);
        $query = $this->db->update('patientmedicalinformation');

        return $query;
    }
    function activate($id)
    {
        $this->db->set('isverified', 1);
        $this->db->where('id', $id);
        $query = $this->db->update('doctor');

        return $query;
    }

    function getspacializations()
    {
        $this->db->select('*');
        $this->db->from('spacializations');
        $this->db->where('isactive', 1);
        $this->db->where('isdeleted', 0);
        $query = $this->db->get();

        return $query->result_array();
    }
    function addspacialization($sp)
    {
        $query = $this->db->insert('spacializations', $sp);
        return $query;
    }
    function getspacilizationdetails($id)
    {
        $this->db->select('*');
        $this->db->from('spacializations');
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row_array();
    }

    function updatespacialization($id, $sp)
    {
        $this->db->set('spacialist', $sp);
        $this->db->where('id', $id);
        $query = $this->db->update('spacializations');

        return $query;
    }

    function deletespacialization($id)
    {
        $this->db->set('isactive', 0);
        $this->db->set('isdeleted', 1);
        $this->db->where('id', $id);
        $query = $this->db->update('spacializations');

        return $query;
    }

    function getappointments()
    {
        $this->db->select('ap.id,pt.firstname as ptname,pt.middlename as ptmname,pt.lastname as ptlname,
        dr.firstname as drname,dr.lastname as drlname,dr.middlename as drmname,ap.scheduleddate,
        ap.timeofarrivel,ap.isaccepted,ap.isrejected,ap.iscancelledbypatient,pi.amount,pi.patient_refund,CONCAT_WS( " ", ap.scheduleddate, ap.timeofarrivel ) AS dat ', FALSE);
        $this->db->from('appointments as ap');
        $this->db->join('doctor as dr', 'ap.doctorid = dr.id');
        $this->db->join('patient as pt', 'ap.patientid = pt.id');
        $this->db->join('paymentinfo as pi', 'ap.id = pi.appointmentsid');
        $this->db->order_by('created_date', 'desc');
        $query = $this->db->get();

        return $query->result_array();
    }

    // function getappointmentsdetailsbyid($id)
    // {
    //     $this->db->select('ap.id,pt.firstname,pt.middlename,pt.lastname,pt.address,ap.timeofarrivel,
    //     ap.isaccepted,ap.iscancelledbypatient,ap.scheduleddate');
    //     $this->db->from('appointments as ap');
    //     $this->db->join('patient as pt', 'ap.patientid = pt.id');
    //     $this->db->where('ap.id', $id);
    //     $query = $this->db->get();

    //     return $query->row_array();
    // }
   
}