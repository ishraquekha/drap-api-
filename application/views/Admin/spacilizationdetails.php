<hr>
<form class="form" action="<?php echo base_url()?>index.php/updatespacialization" method="post" id="registrationForm">
<div class="container bootstrap snippet">
    <div style="height: 100px;" class="row">
          
    <div class="row">
  		<div style="margin-top: -50px;" class="col-sm-3"><!--left col-->
          
      <br>


        </div><!--/col-3-->
    	<div class="col-sm-9" style="width: 65%;">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
              </ul>

              
          <div class="tab-content">
            <div class="tab-pane active" id="home">
                <hr>
                      <div class="form-group">
                          <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                          <div class="col-xs-6">
                              <label for="first_name"><h4>Name</h4></label>
                              <input type="text" class="form-control" name="spname" id="first_name" placeholder="first name" value="<?php echo $data['spacialist'];?>" title="enter your first name if any.">
                          </div>
                      </div>
                      <div class="form-group">
                           <div class="col-xs-12">
                                <br>
                              	<button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                                <form method="post">
                                <input type="hidden" name="delete" value="<?php echo $data['id']; ?>">
                                 <button class="btn btn-danger" style="padding: 12px;" formaction="<?php echo base_url()?>index.php/deletespacialization/<?php echo $data['id'];?>" type="delete"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                 </form>
                            </div>
                      </div>
        
              <hr>
              
             </div>
                         
               
              </div>
          </div>

        </div>
    </div>
    </form>                                      