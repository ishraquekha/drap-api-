<!DOCTYPE html>
<html>
<head>
<title>DoctorsAPP</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="../assets/images/doctor.jpg" type="image/icon type">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>



</head>
<style>
body {
  font-family: Arial, Helvetica, sans-serif;
}

.notification {
  background-color: #b5afaf;
  color: black;
  text-decoration: none;
  /* padding: 15px 26px; */
  position: relative;
  /* display: inline-block; */
  border-radius: 2px;
}
#dash {
  background-color: #b5afaf;
  color: black;
}

#dash:hover {
  background: #009688;
}

.notification:hover {
  background: #009688;
}
#count{
padding: 22px 25px;
margin-top: -49px;
margin-left: 650px;
background-color: #009688;
font-size: large;
border-radius: 50%;
}
/* .notification .badge {
  position: absolute;
  top: -15px;
  right: 1125px;
  padding: 10px 15px;
  border-radius: 50%;
  background-color: #009688;
  color: white;
  font-size: large;
} */
</style>
<body>

<div class="w3-sidebar w3-bar-block w3-card w3-animate-left" style="display:none" id="mySidebar">
  <button style="background-color: #009688;" class="w3-bar-item w3-button w3-large"
  onclick="w3_close()"> &#9776;</button><br><br>
  <a href="<?php echo base_url()?>index.php/dash" class="w3-bar-item w3-button">Dashboard</a><br>
  <a href="<?php echo base_url()?>index.php/doctors" class="w3-bar-item w3-button">Doctors</a><br>
  <a href="<?php echo base_url()?>index.php/patients" class="w3-bar-item w3-button">Patients</a><br>
  <a href="<?php echo base_url()?>index.php/requests" class="w3-bar-item w3-button">Doctors Requests</a><br>
  <a href="<?php echo base_url()?>index.php/transactions" class="w3-bar-item w3-button">Transactions</a><br>
  <a href="<?php echo base_url()?>index.php/logout" class="w3-bar-item w3-button">Logout</a>
</div>

<div id="main">

<div class="w3-teal">
  <button id="openNav" class="w3-button w3-teal w3-xlarge" onclick="w3_open()">&#9776;</button>
</div>

<div class="w3-container">
<div id="contents" style="width:100%;float:left;" ><?php echo $contents ?></div>
</div>

</div>

<script>
function w3_open() {
  document.getElementById("main").style.marginLeft = "20%";
  document.getElementById("mySidebar").style.width = "18%";
  document.getElementById("mySidebar").style.display = "block";
  document.getElementById("openNav").style.display = 'none';
}
function w3_close() {
  document.getElementById("main").style.marginLeft = "0%";
  document.getElementById("mySidebar").style.display = "none";
  document.getElementById("openNav").style.display = "inline-block";
}

$(document).ready(function() {
    $('#example').DataTable({
      paging: false,
      filter:true
    });
} );

</script>
<script>
   $(document).ready(function($) {
    $(".table-row").click(function() {
        window.document.location = $(this).data("href");
    });
});
</script>
<script>
   $(document).ready(function() {

    
var readURL = function(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.avatar').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}


$(".file-upload").on('change', function(){
    readURL(this);
});
});
</script>
<script type="text/javascript">


<?php if($this->session->flashdata('success')){ ?>

    toastr.success("<?php echo $this->session->flashdata('success'); ?>");

<?php }else if($this->session->flashdata('error')){  ?>

    toastr.error("<?php echo $this->session->flashdata('error'); ?>");

<?php }else if($this->session->flashdata('warning')){  ?>

    toastr.warning("<?php echo $this->session->flashdata('warning'); ?>");

<?php }else if($this->session->flashdata('info')){  ?>

    toastr.info("<?php echo $this->session->flashdata('info'); ?>");

<?php } ?>


</script>
</body>

</html>
