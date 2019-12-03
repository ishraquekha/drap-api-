<div class="content-wrapper">
  <div  class="content-heading">
    Dashboard
  </div>
  
  <div style="width: 103%; margin-left: -15px;" class="col-lg-4 col-md-6">        
    <div  class="panel widget bg-green">
      <div  class="row row-table">
        <div style="width: 10%;" class="col-xs-4 text-center bg-green pv-lg p-4">
          <span  class="text-sm" id="month" ></span>
          <br >
          <span  class="h2 mt0" id="date"></span>
        </div>
        <div  class="col-xs-8 pv-lg p-3">
          <span class="text-uppercase" format="dddd" id="day" ></span>
          <br>
          <span class="h2 mt0" id="timer" ></span>
          <span class="text-muted text-sm" id="ampm" format="a"></span>
        </div>
      </div>
    </div>
  </div>
  
  <div  class="row">
      
      <div onclick="location.href='<?php echo base_url();?>index.php/doctors';" style="cursor: pointer;" class="col-lg-4 col-sm-6">
        <div class="panel widget bg-green">
          <div  class="row row-table">
            <div  class="col-xs-4 text-center bg-green pv-lg p-4">
            <i class="fa fa-user-md fa-5x" aria-hidden="true"></i>
            </div>
            <div class="col-xs-8 pv-lg p-3">
                <div  class="h2 mt0"><?php echo $doctors ?></div>
                <div  class="text-uppercase">Doctors</div>
            </div>
          </div>
        </div>
      </div>
      
      <div onclick="location.href='<?php echo base_url();?>index.php/patients';" style="cursor: pointer;" class="col-lg-4 col-sm-6">    
        <div  class="panel widget bg-green">
          <div  class="row row-table">
            <div  class="col-xs-4 text-center bg-green pv-lg p-4">
            <i class="fa fa-heartbeat fa-5x" aria-hidden="true"></i>
            </div>
            <div  class="col-xs-8 pv-lg p-3">
                <div  class="h2 mt0"><?php echo $patient ?></div>
                <div  class="text-uppercase">Patients</div>
            </div>
          </div>
        </div>
      </div>
      
      <div onclick="location.href='<?php echo base_url();?>index.php/transactions';" style="cursor: pointer;" class="col-lg-4 col-md-6 col-sm-12">    
        <div  class="panel widget bg-green">
          <div  class="row row-table">
              <div  class="col-xs-4 text-center bg-green pv-lg p-4">
              <i class="fa fa-money fa-5x" aria-hidden="true"></i>
              </div>
              <div  class="col-xs-8 pv-lg p-3">
                  <div  class="h2 mt0"><?php echo $transaction ?></div>
                  <div  class="text-uppercase">transactions</div>
              </div>
          </div>
        </div>
      </div>

  </div>
  <div  class="row">
      
      <div onclick="location.href='<?php echo base_url();?>index.php/requests';" style="cursor: pointer;" class="col-lg-4 col-sm-6">    
        <div class="panel widget bg-green">
          <div  class="row row-table">
            <div  class="col-xs-4 text-center bg-green pv-lg p-4">
            <i class="fa fa-plus-square fa-5x" aria-hidden="true"></i>
            </div>
            <div class="col-xs-8 pv-lg p-3">
                <div  class="h2 mt0"><?php echo $request ?></div>
                <div style="font-size:14px;margin-left:-5px;"  class="text-uppercase">doctors request</div>
            </div>
          </div>
        </div>
      </div>
      
      <div onclick="location.href='<?php echo base_url();?>index.php/spacializations';" style="cursor: pointer;" class="col-lg-4 col-md-6 col-sm-12">    
        <div class="panel widget bg-green">
          <div  class="row row-table">
              <div class="col-xs-4 text-center bg-green pv-lg p-4">
              <i class="fa fa-medkit fa-5x" aria-hidden="true"></i>
              </div>
              <div  class="col-xs-8 pv-lg p-3">
                  <div  class="h2 mt0"><?php echo $spacial ?></div>
                  <div  class="text-uppercase">specialization</div>
              </div>
          </div>
        </div>
      </div>
  
      <div onclick="location.href='<?php echo base_url();?>index.php/appointments';" style="cursor: pointer;" class="col-lg-4 col-md-6 col-sm-12">  
        <div class="panel widget bg-green">
          <div  class="row row-table">
            <div class="col-xs-4 text-center bg-green pv-lg p-4">
            <i class="fa fa-stethoscope fa-5x" aria-hidden="true"></i>
          </div>
            <div  class="col-xs-8 pv-lg p-3">
                <div  class="h2 mt0"><?php echo $spacial ?></div>
                <div  class="text-uppercase">appointments</div>
            </div>
          </div>
        </div>
      </div>

  </div>
</div>
        
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
    <script type="text/javascript">
    function clockUpdate() {
        var date = new Date();
        var month = date.getUTCMonth(); //months from 1-12
        var dateM = date.getUTCDate();
        var day = date.getDay();
        var year = date.getUTCFullYear();
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        var weekday = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];


        function addZero(x) {
            if (x < 10) {
            return x = '0' + x;
            } else {
            return x;
            }
        }

        function twelveHour(x) {
            if (x > 12) {
                return x = x - 12;
            } else if (x == 0) {
                return x = 12;
            } else {
                return x;
            }
        }

        var h = addZero(twelveHour(date.getHours()));
        var m = addZero(date.getMinutes());
        var s = addZero(date.getSeconds());
        
        $('#timer').text(h + ':' + m + ':' + s);
        $('#date').text(dateM);
        $('#month').text(monthNames[month]);
        $('#day').text(weekday[day]);
        $('#ampm').text(formatAMPM(date));

    }

    function formatAMPM(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0'+minutes : minutes;
        var strTime = ampm;
        return strTime;
    }

      

    $(document).ready(function(){
        clockUpdate();
        setInterval(clockUpdate, 1000);
    });
</script>