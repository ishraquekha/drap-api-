<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Error or success messages
| -------------------------------------------------------------------
*/


// API Message
$lang['AUTH_FAILED'] =  'Authentication Failed';
$lang['parameter_missing']    = 'Required parameters are missing';
$lang['email_format_wrong']    = 'Entered email is invalid.Please enter valid email';
$lang['signup_success'] = 'Signup successfully';
$lang['email_exist'] = 'Entered email is already exist';
$lang['phone_exist'] = 'Entered mobile number is already exist';
$lang['social_id_exist'] = 'Social id already exist';
$lang['login_success'] = 'Logged In successfully';
$lang['ACCOUNT_DEACTIVATE_MSG'] = 'Your account has been deactivated. Please contact support to activate your account';
$lang['email_not_registered'] = 'Email Address is not registered.';
$lang['phone_not_registered'] = 'Mobile number is not registered.';
$lang['password_sent'] = 'Password has been sent to your registered email id';
$lang['phone_update_success'] = 'Phone no updated successfully.';
$lang['user_detail_fetch_success'] = 'User detail fetch successfully.';
//$lang['social_id_not_exist'] = 'Your Social ID is not registered';
$lang['social_id_not_exist'] = 'Please do Sign-up firstly,Your social id is not registered ';

$lang['username_not_exist'] = 'Username does not exist.';
$lang['password_incorrect'] = 'Entered current password is wrong.';
$lang['genpassword_incorrect'] = 'Entered generated password is wrong.';
$lang['otp_incorrect'] = 'Please enter the correct OTP';
$lang['Otp_Verify'] = 'OTP is successfully verified';
$lang['Otp_Not_Verify'] = 'OTP not verified';
$lang['password_reset_success'] = 'Password has been reset successfully';
$lang['logout_success'] = 'Logged Out successfully';

$lang['setting_update_success'] = 'Notification Settings has been updated successfully';
$lang['notification_fetch_success'] = 'Notification list fetch successfully.';
$lang['otp_expire'] = 'OTP has been expired.';
$lang['otp_sent'] = 'OTP has been sent to your registered mobile no.';
$lang['reset_password_expire'] = 'This password has been expired.';
$lang['profile_update_Success'] = 'Profile has been updated successfully.';

$lang['notification_detail_success'] = 'Notification detail fetch successfully';

$lang['username_password_wrong']    = 'Either email or password is wrong.';
$lang['user_password_wrong']        = 'Either username or password is wrong.';
$lang['account_deactivated']        = 'Your account has been deactivated.'; 
$lang['username_password_required'] = 'Username and Password are required.'; 
$lang['try_again']                  = 'Please try again.';

// User Module
$lang['user_add_success']       = 'User successfully added.'; 
$lang['user_update_success']    = 'User successfully updated.'; 
$lang['username_exist']         = 'Username already exist.';

$lang['user_allocate_success']  = 'User successfully allocated.'; 
$lang['userlist_fetch_Success'] = 'User List fetch successfully.';


// Setting
$lang['password_change_success'] = 'Password has been changed successfully';
$lang['old_password_wrong'] = 'Entered old password is wrong';
$lang['invalid_image_format'] = 'Please select valid image format';
$lang['profile_update_success'] = 'Profile update successfully';

// Event Module
$lang['event_add_Success']      = 'Event successfully added';
$lang['event_update_Success']   = 'Event successfully updated';
$lang['event_exist']            = 'Event already exist';
$lang['event_fetch_Success']    = 'Event list fetched successfully';
$lang['no_data_found']          = 'No Data Found';
$lang['event_not_exist']        = 'Event Id wrong';
$lang['event_follow_update_Success'] = 'Event follow status successfully updated.';
$lang['event_invite_send_Success']   = 'Event invitation send successfully.';
$lang['event_invite_update_Success'] = 'Event invitation status successfully updated.';
$lang['event_delete_Success'] = 'Event successfully deleted.';

$lang['logout_success']          ='Logout successfully';
$lang['profile_fetch_Success']   = 'Data Fetch successfully';
$lang['page_fetch_success']  = 'page content fetch successfully.';

$lang['image_delete_Success'] = 'Image successfully deleted.';
$lang['image_update_Success'] = 'Image update successfully.';
$lang['image_upload_Success'] = 'Images successfully uploaded.';
$lang['account_delete_Success'] = 'User account successfully deleted.';

$lang['event_image_delete_Success'] = 'Event Image successfully deleted.';
$lang['list_fetch_Success']   = 'Data Fetch successfully';
$lang['incorrect_mobile']   = 'Your mobile no. is incorrect.';
$lang['password_reset_social'] = 'This is your social account Email Address. You can either reset the password or login through your social account.';
$lang['venue_mark_as_favourite_Success'] = 'Venue marked as a favourite successfully.';
$lang['venue_mark_as_unfavourite_Success'] = 'Venue unmarked as a favourite successfully.';
$lang['venue_list_fetch_Success'] = 'Venue list fetched successfully.';
$lang['checkin_already_venue'] = 'User already checked-in venue.';
$lang['checkin_venue_success'] = 'User successfully check-in venue.';
$lang['favourite_user_fetch_Success'] = 'Favourite users list fetch successfully.';
$lang['venue_rating_update_Success'] = 'Venue rating updated successfully.';
$lang['user_rating_fetch_Success'] = 'User rating detail fetch successfully.';
$lang['venue_reviews_fetch_Success'] = 'Venue reviews fetch successfully.';
$lang['venue_detail_fetch_Success'] = 'Venue detail fetch successfully.';
$lang['venue_guest_fetch_Success'] = 'Guest users list fetch successfully.';
$lang['friend_request_sent_Success'] = 'Friend request sent successfully.';
$lang['friend_status_update_Success'] = 'Friend status updated successfully.';
$lang['fav_venue_list_fetch_Success'] = 'Your Favourite Venue list fetched successfully.';
$lang['friend_added_planvisit_success'] = 'Your friend is added to plan visit successfully.';
$lang['friend_already_added_planvisit'] = 'Your friend is added to plan visit already.';
$lang['event_add_Success']      = 'Venue successfully added';
$lang['follow_user_Success']      = 'Follow successfully';
$lang['follow record insert']      = 'Follow record inserted successfully';
$lang['unfollow record_Success']      = 'Unfollow successfully';
$lang['request_add']      = 'Request sent successfully';
$lang['event_attend']      = 'Event attend successfully';
$lang['request_not_accept']      = 'Request not accepted';
$lang['event_not_attend']      = 'Event not attended successfully';
$lang['attend_confirm']      = 'Event attend may be or not successfully';
$lang['already_rejected']      = 'You have already rejected';
$lang['already_accepted']      = 'You have already accepted';

//Gift Module
$lang['gift_add_Success']      = 'Gift added successfully';
$lang['gift_exist']            = 'Gift already exist';
$lang['gift_fetch_Success']    = 'Gift list fetched successfully';
$lang['gift_fetch_Success1']    = 'Gift details fetched successfully';
$lang['no_data_found']          = 'No Data Found';

$lang['action_missing']          = 'Action Missing';
$lang['event_guest_fetch_Success']          = 'event_guest_fetch_Success';
$lang['invitation_already_send'] = 'Invitation already send';
$lang['already_follow'] = 'You have already follow';
$lang['already_unfollow'] = 'You have already unfollow';
$lang['event_guest_fetch_Success'] = 'Event guestlist fetch Successfully';
$lang['module_add_success']      = 'Module successfully added.'; 
$lang['module_update_success']   = 'Module successfully updated.'; 
$lang['module_name_exist']       = 'Module name already exist.';
$lang['MOBILE_EXIST']       = 'Entered mobile number is already exist';
$lang['EMAIL_EXIST']       = 'Entered Email is already exist';
$lang['SIGN_UP']       = 'Signup successfully';
$lang['LOGIN']       = 'Login successfully';
$lang['LOGIN_FAILURE']       = 'Login failed';
$lang['SIGNUP_FAILURE']       = 'Signup failed.';
$lang['ACCOUNT_DEACTIVATE']       = 'Account is not active.';
$lang['PARAMETER_ERROR']       = 'Required parameter missing.';
$lang['INVALID_ERROR']       = 'Invalid access.';
$lang['USER_EXIST']       = 'User Exist.';
$lang['UPDATE_PROFILE']       = 'Profile updated successfully';
$lang['USER_NOT_EXIST']       = 'User Not Exist.';
$lang['METHOD_NOT_EXIST']       = 'Method Not Found.';
$lang['NOT_UPDATE_PROFILE']       = 'Nothing To Update';
$lang['PASSWORD_CHANGE']       = 'Password changed Successfully';
$lang['PASSWORD_NOT_CHANGE']       = 'Unable to change Password';
$lang['PASSWORD_OLD']       = 'Old password  does not match';
$lang['PASSWORD_NEW']       = 'Confirm password does not match';
$lang['PASSWORD_OLD_EXIST']       = 'Old password does not exist';
$lang['CONTACT_SYNC']       = 'Contact synchronize';
$lang['CONTACT_ERROR']       = 'Contact Not Found';
$lang['CONTACT_NAME_ERROR']       = 'Contact Name Not Found';
$lang['AUTH_ERROR']       = 'Not authenticated';
$lang['PASSWORD_CHANGE_LINK']       = 'Password change Link Successfully SEND To you Email';
$lang['VENUE_ERROR']       = 'No Venue Found';
$lang['VENUE_EXIST']       = 'Venue Exists';
$lang['USERNAME_NOT_EXIST'] = 'User not Exists';
$lang['FRIENDLIST_TO_INVITE'] = 'Friend list fetch successfully';

$lang['VENUE_ADD']='Venue added successfully';
$lang['VENUE_NOTEXIST']='Venue does not exist';
$lang['VENUE_NO_REG']='Venue not added with me';
$lang['ADD_TABLE']='Add Table Successfully';
$lang['TABLE_FAILED']='Add Table Failed';
$lang['TABLE_NOTEXIST']='Table_no does not exist';
$lang['TABLE_BOOK']='Table booked successfully';
$lang['FAVOURITE']='Favorite venue selected successfully';
$lang['UNFAVOURITE']='Venue unfavourite successfully!!';
$lang['GET_LIST']='Get list Successfully';
$lang['GET_TABLE']='Get table successfully';
$lang['VENUE_OWN_EXIST']='Venue allready exist';
$lang['VENUE_RATING']='Your rating added successfully';
$lang['VENUE_LIST']='Get Venue list Successfully';
$lang['TABLE_BOOKED']='Your table has been booked successfully';
$lang['TABLE_NOT_BOOKED']=' No Table booked';
$lang['TABLE_NOT_AVAILABLE']=' No Table Available';
$lang['TABLE_AVAILABLE']='Table Available';
$lang['PAYMENT_NOT_VERIFIED']='Payment not verified';
$lang['TABLE_ALREADY_BOOKED']='Table already booked';
$lang['BEST_DAY']='Best day list Successfully';
$lang['SUB_CAT']='Subcategory find Successfully';
$lang['EMAIL_INVALID']='Please enter valid email id';
$lang['ADD_CATEGORY']='Category added successfully';
$lang['VENUE_NOT_ADDED']='Venue not added with me';
$lang['CAT_NOTEXIST']='Category not exist';
$lang['ADD_SUBCATEGORY']='Sub_category added successfully';
$lang['SUB_NOTEXIST']='Sub category not Exist';
$lang['REC_DELETE']='Record has been deleted successfully';
$lang['SUB_UPDATE']='Subcategory has been updated Successfully';
$lang['TABLE_INFO']='Get Table list Successfully';
$lang['PLANNER_INFO']='Table Planner Successfully Inserted';

$lang['ADD_COUPAN']='Coupon added successfully';
$lang['MANAGE_NOTIFY']='Notification updated successfully';
$lang['DATA_EXIST']='This item name already exist';
$lang['CODE_EXIST']='Coupan code already exist';
$lang['MUSIC_GET']='Get Music list Successfully';
$lang['MUSIC_NOT']='Music not exist';
$lang['CAT_GET']='Get category list successfully';
$lang['CAT_NOT']='Category not exist';
$lang['PENDING_LIST']='Pending list get successfully';
$lang['PAST_LIST']='Order details fetched successfully';
$lang['NO_PASTORDER_FOUND']='No past order found';
$lang['ORDER_NOTEXIST']='Order not exist';
$lang['ORDER_EXIST'] = 'Order has been already Placed with this Order Details.';
$lang['ORDER_MY'] = 'My Order Listed Successfully.';
$lang['ORDER_PLACED'] = 'Table Booked Successfully.';
$lang['POLICY_GET'] = 'Privacy Policy Fetched Successfully.';
$lang['POLICY_ERROR'] = 'Privacy Policy is Unavailbale..';
$lang['TERMS_GET'] = 'Terms and Condition Fetched Successfully.';
$lang['TERMS_ERROR'] = 'Terms and Condition is Unavailbale..';
$lang['FEEDBACK_SAVE']='Your feedback has been Saved Successfully';
$lang['GET_VENUE']='Get Venue Successfully';
$lang['OTP_SUCCESS']='Entered OTP is verified';
$lang['OTP_FAILURE']='Entered OTP is incorrect';
$lang['OTP_RESEND']='OTP is resent to this mobile number';
$lang['TABLE_UPDATE']='Table Coplementry and Price updated successfully';
$lang['INVALID_PERMISSION']       = 'You are not allowed to perform this action';
$lang['IMAGE_DELETE_SUCCESS']       = 'Image deleted successfully';
$lang['IMAGE_UPLOAD_SUCCESS']       = 'Image uploaded successfully';
$lang['IMAGE_ERROR']       = 'Image deleted successfully';
$lang['IMAGE_REQUIRED']       = 'Image required';
$lang['VENUE_NOT_EXIST'] = 'Venue does not exists';
$lang['NOTIFICATION_FETCH_SUCCESS'] = 'Notification fetched successfully';
$lang['NO_NOTIFICATION'] = 'No notification';


// coupan string

$lang['COUPAN_NOT_EXISTS'] = 'Entered coupon does not exists';
$lang['COUPAN_IS_VALID'] = 'coupon is valid';
$lang['COUPAN_EXPIRES'] = 'coupon has been expired';
$lang['COUPAN_FETCH_SUCCESS'] = 'coupon fetched successfully';
$lang['VENUE_USER_FETCH_SUCCESS'] = 'Users fetched successfully';
$lang['NO_VENUE_USER_FOUND'] = 'No users found';
$lang['COUPAN_ALREADY_EXISTS'] = "coupon is already exists";
$lang['COUPAN_SAVED_SUCCESSFULLY'] = "coupon saved successfully";
$lang['FROM_DATE_INVALID'] = 'From date must be greater than today';
$lang['TO_DATE_INVALID'] = 'To date must be greater than from date';


// card string
$lang['CARD_SAVE_SUCCESS'] = "Card saved successfully";
$lang['NO_CARD_ADDED'] = "No card added";
$lang['CARD_FETCH_SUCCESS'] = "Card fetched successfully";
$lang['ORDER_DOES_NOT_EXISTS'] = "Order does not exists";
$lang['ORDER_OWNER_WRONG'] = "You are not allowed to perform the action";
$lang['TRANSCATION_FAIL'] = 'Transaction failed. Please try again.';
$lang['PAYMENT_SUCCESS'] = "Payment has been done";
$lang['PAYMENT_ALREADY_MADE'] = "Payment is already done";
$lang['NOT_INVITED'] = 'You are not invited';
$lang['CHECKIN_SUCCESS'] = "You checked in successfully";
$lang['CHECKOUT_SUCCESS'] = "You checked out successfully";
$lang['AMOUNT_REQUEST_SENT'] = "Your request has been send to admin";
$lang['CARD_DELETE_SUCCESS'] = "Card deleted successfully";

//payment
$lang['NO_ORDER_FOUND'] = "No order found";
$lang['ORDER_FETCH'] = "Order detail fetched successfully";

$lang['FRIEND_AND_AMOUNT_COUNT_NOT_SAME'] = "Friends and contribution amount count does not match";
$lang['SPLIT_PAYMENT_AMOUNT_DOES_NOT_MATCH_TOTAL'] = "Sum of split amount does not match total amount";
$lang['ORDER_UPDATED'] = "Order updated successfully";

//Venue Module
$lang['floorplan_create_Success']      = 'Floorplan created successfully';
$lang['empty_floor_plan']      = 'There is no any floor plan';
$lang['schedule_save_successfully']      = 'Schedule save successfully';
$lang['venue_id_missing_in_db']      = 'Venue id missing in database';
$lang['no_close_schedule']      = 'There is no close schedule for this venue';



