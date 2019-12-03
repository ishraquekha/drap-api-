<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Welcome';
$route['patientlogin'] = 'User_api/patientlogin';
$route['patientregister'] = 'User_api/patientsignup';
$route['doctorlogin'] = 'User_api/doctorlogin';
$route['doctorregister'] = 'User_api/doctorsignup';
$route['updatepatientmedicalinfo'] = 'User_api/updatepatientmedicaldetails';
$route['editpatientprofile'] = 'User_api/editPatientProfile';
$route['editdoctorprofile'] = 'User_api/editDoctorProfile';
$route['editdoctorprofilepic'] = 'User_api/editProfilePic';
$route['doctorprofiledetails/(:any)'] = 'User_api/doctorprofiledetails/$1';
$route['patientpreference'] = 'User_api/patientpreference';
$route['sendEmail/(:any)'] = 'User_api/sendEmail/$1';
$route['resetPassword'] = 'User_api/resetPassword';
$route['ResetPassword/(:any)/(:any)/(:any)'] = 'Reset_Controller/changepassword/$1/$2/$3';
$route['Reset'] = 'Reset_Controller/Reset';
$route['addpaymentmethod'] = 'Payment_api/addUserPaymentMethod';
$route['getdoctorsbypreference/(:any)/(:any)/(:any)'] = 'User_api/getdoctorsbypreference/$1/$2/$3';
$route['getspacializationslist'] = 'User_api/getspacializationslist';
// $route['insertappointments'] = 'User_api/insertappointments';
$route['getpatientappointments'] = 'User_api/getpatientappointments';
$route['getdoctorappointments'] = 'User_api/getdoctorappointments';
// $route['getdoctoraccept/(:any)'] = 'User_api/getdoctoraccept/$1';
// $route['getdoctorreject/(:any)'] = 'User_api/getdoctorreject/$1';
// $route['getcancelledbypatient/(:any)'] = 'User_api/getcancelledbypatient/$1';
$route['getdoctorslist'] = 'User_api/getdoctorslist';
$route['updatepatientaddress'] = 'User_api/updatepatientaddress';
$route['patientdetails'] = 'User_api/patientdetails';

$route['patientfav'] = 'User_api/patientfav';

$route['getfavdoctor'] = 'User_api/getfavdoctor';
$route['doctorblock'] = 'User_api/doctorblock';
$route['doctorprefrenceupdate'] = 'User_api/doctorprefrenceupdate';
$route['doctorrating'] = 'User_api/doctorrating';
$route['getdoctorrating'] = 'User_api/getdoctorrating';
$route['patientrating'] = 'User_api/patientrating';
$route['getpatientrating'] = 'User_api/getpatientrating';
$route['getdoctorpastappointment'] = 'User_api/getdoctorpastappointment';
$route['getpatientpastappointment'] = 'User_api/getpatientpastappointment';
$route['getregion'] = 'User_api/getregion';
$route['getcomunas'] = 'User_api/getcomunas';
$route['updatedoctorexp'] = 'User_api/updatedoctorexp';


$route['createCharge'] = 'Payment_api/createCharge';
$route['refund'] = 'Payment_api/refund';
$route['getbank'] = 'Payment_api/getbank';
$route['adddoctorpaymentmethod'] = 'Payment_api/adddoctorPaymentMethod';
$route['paycharge'] = 'Payment_api/payCharge';
$route['retrieve'] = 'Payment_api/retriveCharge';
$route['savecard'] = 'Payment_api/CardSave';
$route['doctorpay'] = 'Payment_api/doctorpay';
$route['refunddoctor'] = 'Payment_api/refundDoc';
$route['transfer'] = 'Payment_api/transfer';
$route['fullappoint'] = 'Payment_api/getdoctorsbypreferenceonly';
$route['doctoraccept/(:any)'] = 'Payment_api/doctoraccept/$1';
$route['doctorreject/(:any)'] = 'Payment_api/doctorreject/$1';
// ----------------------Admin-----------------------------------------
$route['Admin'] = 'Admin/Admin_Controller/login';
$route['dashboard'] = 'Admin/Admin_Controller/dashboard';
$route['dash'] = 'Admin/Admin_Controller/dash';
$route['doctors'] = 'Admin/Admin_Controller/doctorslist';
$route['doctorsdetails/(:any)'] = 'Admin/Admin_Controller/doctorsdetails/$1';
$route['patientsdetails/(:any)'] = 'Admin/Admin_Controller/patientsdetails/$1';
$route['patients'] = 'Admin/Admin_Controller/patientslist';
$route['requests'] = 'Admin/Admin_Controller/requestlist';
$route['transactions'] = 'Admin/Admin_Controller/transactionlist';
$route['updatedoctor'] = 'Admin/Admin_Controller/updatedoctor';
$route['updatepatient'] = 'Admin/Admin_Controller/updatepatient';
$route['deletedoctor/(:any)'] = 'Admin/Admin_Controller/deletedoctor/$1';
$route['deletepatient/(:any)'] = 'Admin/Admin_Controller/deletepatient/$1';
$route['addspacialization'] = 'Admin/Admin_Controller/addspacialization';
$route['spacializations'] = 'Admin/Admin_Controller/spacializations';
$route['spacilizationdetails/(:any)'] = 'Admin/Admin_Controller/spacilizationdetails/$1';
$route['updatespacialization'] = 'Admin/Admin_Controller/updatespacialization';
$route['deletespacialization/(:any)'] = 'Admin/Admin_Controller/deletespacialization/$1';
$route['appointments'] = 'Admin/Admin_Controller/getappointments';
$route['appointmentsdetails/(:any)'] = 'Admin/Admin_Controller/getappointmentsdetails/$1';

$route['logout'] = 'Admin/Admin_Controller/logout';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

