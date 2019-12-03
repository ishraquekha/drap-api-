<hr>
<form class="form" action="<?php echo base_url()?>index.php/updatedoctor" enctype="multipart/form-data" method="post" id="registrationForm">
<div class="container bootstrap snippet">
    <div style="height: 100px;" class="row">
  		<div style="width: 40%; margin-top: -30px;" class="col-sm-10"><h1>User name</h1></div>
          <div style="width: 30%; margin-left: 600px;" class="panel panel-default">
            <div class="panel-heading">Verification <i class="fa fa-link fa-1x"></i></div>
            <div class="panel-body"><?php if($data['isverified']==0){ ?>
              <input type="checkbox" name="verify" value="0"> Admin Verification
              <?php  }
              else{ ?>
                  <input type="checkbox" name="verify" value="1" checked> Admin Verification
                  <?php }?></div>
          </div>
    	<!-- <div class="col-sm-2"><a href="" class="pull-right"><img title="profile image" style="margin-top: -18px;" class="img-circle img-responsive" src="<?php echo base_url()?>assets/images/doctor.jpg"></a></div> -->
    </div>
    <div class="row">
  		<div style="margin-top: -50px;" class="col-sm-3"><!--left col-->
          
      <div class="text-center">
        <img src="<?php echo $data['profilepic'];?>" style="height: 205px; width: 76%; margin-left: -90px;" class="avatar img-circle img-thumbnail" alt="avatar">
        <h6>Upload a different photo...</h6>
        <input type="file" name="image" class="text-center center-block file-upload">
      </div>
      <br>


        </div><!--/col-3-->
    	<div class="col-sm-9" style="width: 65%;">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
                <li><a data-toggle="tab" href="#messages">Personal Details</a></li>
                <li><a data-toggle="tab" href="#settings">Documentations</a></li>
              </ul>

              
          <div class="tab-content">
            <div class="tab-pane active" id="home">
                <hr>
                      <div class="form-group">
                          <input type="hidden" name="id" value="<?php echo $data['dr_id'];?>">
                          <div class="col-xs-6">
                              <label for="first_name"><h4>First name</h4></label>
                              <input type="text" class="form-control" name="first_name" id="first_name" placeholder="first name" value="<?php echo $data['firstname'];?>" title="enter your first name if any.">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                            <label for="last_name"><h4>Middle name</h4></label>
                            <input type="text" class="form-control" name="middle_name" id="middle_name" placeholder="middle name" value="<?php echo $data['middlename'];?>" title="enter your middle name if any.">
                        </div>
                      </div>
          
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="phone"><h4>Last name</h4></label>
                              <input type="text" class="form-control" name="last_name" id="last_name" placeholder="last name" value="<?php echo $data['lastname'];?>" title="enter your last name if any.">
                          </div>
                      </div>
          
                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="mobile"><h4>Mobile</h4></label>
                             <input type="text" class="form-control" name="phone" id="phone" placeholder="enter phone" value="<?php echo $data['phone'];?>" title="enter your phone number if any.">

                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="email"><h4>Email</h4></label>
                              <input type="email" class="form-control" name="email" id="email" placeholder="you@email.com" value="<?php echo $data['email'];?>" title="enter your email.">

                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="email"><h4>Location</h4></label>
                              <input type="text" class="form-control" name="location" id="location" placeholder="somewhere" value="<?php echo $data['address'];?>" title="enter a location">

                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="first_name"><h4>Title</h4></label>
                              <input type="text" class="form-control" name="title" id="title" placeholder="enter your title" value="<?php echo $data['title'];?>" title="enter your title.">
                            </div>
                      </div>
                      
                      <div class="form-group">
                           <div class="col-xs-12">
                                <br>
                              	<button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                                <form method="post">
                                <input type="hidden" name="delete" value="<?php echo $data['id']; ?>">
                                 <button class="btn btn-danger" style="padding: 12px;" formaction="<?php echo base_url()?>index.php/deletedoctor/<?php echo $data['id'];?>" type="delete"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                 </form>
                            </div>
                      </div>
              	
              
              <hr>
              
             </div><!--/tab-pane-->
             <div class="tab-pane" id="messages">
               
               <h2></h2>
               
               <hr>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="first_name"><h4>Gender</h4></label>
                              <input type="text" class="form-control" name="gender" id="gender" placeholder="select your gender" value="<?php echo $data['gender'];?>" title="enter your gender.">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                            <label for="last_name"><h4>Date of Birth</h4></label>
                            <input type="text" class="form-control" name="dateofbirth" id="dateofbirth" placeholder="select your date of birth" value="<?php echo $data['dateofbirth'];?>" title="enter your date of birth.">
                        </div>
                      </div>
          
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="phone"><h4>Colleague Number</h4></label>
                              <input type="text" class="form-control" name="colleaguenumber" id="colleaguenumber" placeholder="select your colleague number" value="<?php echo $data['colleaguenumber'];?>" title="enter your colleague number.">
                            </div>
                      </div>
          
                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="mobile"><h4>Experience</h4></label>
                             <input type="text" class="form-control" name="experience" id="experience" placeholder="enter your experience" value="<?php echo $data['experience'];?>" title="enter your experience.">
                            </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="email"><h4>Experience Details</h4></label>
                              <input type="text" class="form-control" name="experiencedetails" id="experiencedetails" placeholder="enter your experience details" value="<?php echo $data['experiencedetails'];?>" title="enter your experience details.">
                            </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="email"><h4>Consultation Rate</h4></label>
                              <input type="text" class="form-control" name="consultationrate" id="consultationrate" placeholder="select your consultation rate" value="<?php echo $data['consultationrate'];?>" title="enter your consultation rate.">
                            </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="password"><h4>Consultation Rate Unit</h4></label>
                              <input type="text" class="form-control" name="consultationrateunit" id="consultationrateunit" placeholder="enter your consultation rate unit" value="<?php echo $data['consultationrateunit'];?>" title="enter your consultation rate unit.">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                            <label for="password2"><h4>Consultation Time</h4></label>
                            <input type="text" class="form-control" name="consultationtime" id="consultationtime" placeholder="enter your consultation time" value="<?php echo $data['consultationtime'];?>" title="enter your consultation time.">
                        </div>
                      </div>
                      <div class="form-group">
                           <div class="col-xs-12">
                                <br>
                              	<button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                                <form method="post">
                                <input type="hidden" name="delete" value="<?php echo $data['id']; ?>">
                                 <button class="btn btn-danger" style="padding: 12px; margin-left: 100px; margin-top: -67px;" formaction="<?php echo base_url()?>index.php/deletedoctor/<?php echo $data['id'];?>" type="delete"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                 </form>
                            </div>
                      </div>
              	
               
             </div><!--/tab-pane-->
             <div class="tab-pane" id="settings">
            		
               	
                  <hr>
                  <!-- <form class="form" action="<?php echo base_url()?>index.php/updatedoctor" method="post" id="registrationForm"> -->
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="first_name"><h4>Identity Document</h4></label>
                              <a class="form-control" src="<?php echo $data['identitydocument'];?>" title="<?php echo $data['firstname'].'-identity document';?>"></a>
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                            <label for="last_name"><h4>PG Document</h4></label>
                            <a class="form-control" src="<?php echo $data['pgdocument'];?>" title="<?php echo $data['firstname'].'-post graduate document';?>"></a>                     
                        </div>
                      </div>
          
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="phone"><h4>Legal Document</h4></label>
                              <a class="form-control" src="<?php echo $data['legaldocument'];?>" title="<?php echo $data['firstname'].'-legal document';?>"></a>                       
                            </div>
                      </div>
          
                                           
                      <div class="form-group">
                           <div class="col-xs-12">
                                <br>
                              	<!-- <button class="btn btn-lg btn-success pull-right" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button> -->
                               	<!--<button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button>-->
                            </div>
                      </div>
              	<!-- </form> -->
              </div>
               
              </div><!--/tab-pane-->
          </div><!--/tab-content-->

        </div><!--/col-9-->
    </div><!--/row-->
    </form>                                      