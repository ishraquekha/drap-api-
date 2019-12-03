<hr>
<form class="form" action="<?php echo base_url()?>index.php/updatepatient" enctype="multipart/form-data" method="post" id="registrationForm">
<div class="container bootstrap snippet">
    <div style="height: 100px;" class="row">
  		<div class="col-sm-10"><h1>User name</h1></div>
    	<div class="col-sm-2"><a href="/users" class="pull-right"><img title="profile image" style="margin-top: -18px;" class="img-circle img-responsive" src="<?php echo base_url()?>assets/images/doctor.jpg"></a></div>
    </div>
    <div class="row">
  		<div style="margin-top: -50px;" class="col-sm-3"><!--left col-->
          
      <div class="text-center">
        <img src="<?php echo $data['profilepic'];?>" style="height: 205px; width: 76%; margin-left: -90px;" class="avatar img-circle img-thumbnail" alt="avatar">
        <h6>Upload a different photo...</h6>
        <input type="file" name="image" class="text-center center-block file-upload">
      </div></hr><br>

               
          <div class="panel panel-default">
            <div class="panel-heading">Verification <i class="fa fa-link fa-1x"></i></div>
            <div class="panel-body"><?php if($data['IsActive']==0){ ?>
              <input type="checkbox" name="verify" value="0"> Doctor Verification
              <?php  }
              else{ ?>
                  <input type="checkbox" name="verify" value="1" checked> Doctor Verification
                  <?php }?></div>
          </div>

        </div><!--/col-3-->
    	<div class="col-sm-9">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
                <li><a data-toggle="tab" href="#messages">Persional Details</a></li>
                <li><a data-toggle="tab" href="#settings">Medical Informations</a></li>
              </ul>

              
          <div class="tab-content">
            <div class="tab-pane active" id="home">
                <hr>
                      <div class="form-group">
                          <input type="hidden" name="id" value="<?php echo $data['id'];?>">
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
                              <label for="address"><h4>Location</h4></label>
                              <input type="text" class="form-control" name="location" id="location" placeholder="somewhere" value="<?php echo $data['address'];?>" title="enter a location">
                          </div>
                      </div>
                      
                      
                      <div class="form-group">
                           <div class="col-xs-12">
                                <br>
                              	<button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                                <form method="post">
                                <input type="hidden" name="delete" value="<?php echo $data['id']; ?>">
                                   <button class="btn btn-danger" style="padding: 12px;" formaction="<?php echo base_url()?>index.php/deletepatient/<?php echo $data['id'];?>" type="submit"><i class="glyphicon glyphicon-trash"></i> Delete</button>
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
                              <label for="phone"><h4>Medical history</h4></label>
                              <input type="text" class="form-control" name="medicalhistory" id="medicalhistory" placeholder="select your colleague number" value="<?php echo $medical['medicalhistory'];?>" title="enter your colleague number.">
                            </div>
                      </div>
          
                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="mobile"><h4>Surgeries</h4></label>
                             <input type="text" class="form-control" name="surgeries" id="surgeries" placeholder="enter your experience" value="<?php echo $medical['surgeries'];?>" title="enter your experience.">
                            </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="email"><h4>Drugtaker</h4></label>
                              <input type="text" class="form-control" name="drugtaker" id="drugtaker" placeholder="enter your experience details" value="<?php echo $medical['drugtaker'];?>" title="enter your experience details.">
                            </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="email"><h4>Is Allergic to Medications</h4></label>
                              <input type="text" class="form-control" name="isallergictomedications" id="isallergictomedications" placeholder="select your consultation rate" value="<?php echo $medical['isallergictomedications'];?>" title="enter your consultation rate.">
                            </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="password"><h4>Allergic to Medications</h4></label>
                              <input type="text" class="form-control" name="allergictomedications" id="consultationrateunit" placeholder="enter your consultation rate unit" value="<?php echo $medical['allergictomedications'];?>" title="enter your consultation rate unit.">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                            <label for="password2"><h4>Family Background</h4></label>
                            <input type="text" class="form-control" name="familybackground" id="familybackground" placeholder="enter your consultation time" value="<?php echo $medical['familybackground'];?>" title="enter your consultation time.">
                        </div>
                      </div>
                      <div class="form-group">
                           <div class="col-xs-12">
                                <br>
                              	<button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                                <form method="post">
                                <input type="hidden" name="delete" value="<?php echo $data['id']; ?>">
                                   <button class="btn btn-danger" style="padding: 12px;margin-left: 100px;margin-top: -67px;" formaction="<?php echo base_url()?>index.php/deletepatient/<?php echo $data['id'];?>" type="delete"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                </form>
                            </div>
                      </div>
              	
               
             </div><!--/tab-pane-->
             <div class="tab-pane" id="settings">
            		
               	
                  <hr>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="first_name"><h4>Is Tobacco</h4></label>
                              <p><?php echo $medical['istobacco'];?></p>
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                            <label for="last_name"><h4>Tobacco Rating</h4></label>
                            <p><?php echo $medical['tobaccorating'];?></p>                          
                        </div>
                      </div>
          
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="phone"><h4>Is Alcoholic</h4></label>
                              <p><?php if($medical['isalcohol']==0){
                                  echo "NO"; }
                                  else{ echo "YES";}?></p>                          
                            </div>
                      </div>
                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="mobile"><h4>Alcohol Rating</h4></label>
                             <p><?php echo $medical['alcoholrating'];?></p>                          
                            </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="phone"><h4>Is Drugs</h4></label>
                              <p><?php if($medical['isdrugs']==0){
                                  echo "NO"; }
                                  else{ echo "YES";}?></p>                          
                            </div>
                      </div>
                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="mobile"><h4>Drug Details</h4></label>
                             <p><?php echo $medical['drugsdetails'];?></p>                          
                            </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="phone"><h4>Is Physical Activity</h4></label>
                              <p><?php if($medical['isphysicalactivity']==0){
                                  echo "NO"; }
                                  else{ echo "YES";}?></p>                          
                            </div>
                      </div>
                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="mobile"><h4>Physical Activity Details</h4></label>
                             <p><?php echo $medical['physicalactivitydetails'];?></p>                          
                            </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="phone"><h4>Is Alcoholic</h4></label>
                              <p><?php if($medical['ispragnancy']==0){
                                  echo "NO"; }
                                  else{ echo "YES";}?></p>                          
                            </div>
                      </div>
                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="mobile"><h4>Pragnancy Details</h4></label>
                             <p><?php echo $medical['pragnancydetails'];?></p>                          
                            </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="phone"><h4>Is Alcoholic</h4></label>
                              <p><?php if($medical['isbirth']==0){
                                  echo "NO"; }
                                  else{ echo "YES";}?></p>                          
                            </div>
                      </div>
                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="mobile"><h4>Birth Details</h4></label>
                             <p><?php echo $medical['birthdetails'];?></p>                          
                            </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="phone"><h4>Is Alcoholic</h4></label>
                              <p><?php if($medical['isabortions']==0){
                                  echo "NO"; }
                                  else{ echo "YES";}?></p>                          
                            </div>
                      </div>
                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="mobile"><h4>Abortion Details</h4></label>
                             <p><?php echo $medical['abortiondetails'];?></p>                          
                            </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="phone"><h4>Is Alcoholic</h4></label>
                              <p><?php if($medical['iscontraceptives']==0){
                                  echo "NO"; }
                                  else{ echo "YES";}?></p>                          
                            </div>
                      </div>
                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="mobile"><h4>Contraceptives Details</h4></label>
                             <p><?php echo $medical['contraceptivesdetails'];?></p>                          
                            </div>
                      </div>
                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="mobile"><h4>Last Mansturation Date</h4></label>
                             <p><?php echo $medical['lastmansturationdate'];?></p>                          
                            </div>
                      </div>
                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="mobile"><h4>Physical Activity Rating</h4></label>
                             <p><?php echo $medical['physicalactivityrating'];?></p>                          
                            </div>
                      </div>
                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="mobile"><h4>Drug Rating</h4></label>
                             <p><?php echo $medical['drugrating'];?></p>                          
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