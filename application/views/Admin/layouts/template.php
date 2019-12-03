<!DOCTYPE html>
<html>
<head>
<title>DoctorsAPP</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?php echo base_url()?>assets/images/drapp.jpg" type="image/icon type">
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
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/app.css" id="maincss">
<link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/css/style.css" rel="stylesheet">

	<!-- Mainly scripts -->
	<!-- <script src="../assets/js/jquery-2.1.1.js"></script> -->
	<!-- <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script> -->
	<!-- <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script> -->

	<!-- Custom and plugin javascript -->
	<!-- <script src="../assets/js/inspinia.js"></script> -->
	<!-- <script src="../assets/js/plugins/pace/pace.min.js"></script> -->

	<!-- <script src="../assets/js/common.js"></script> -->

</head>
<style>
body {
  font-family: Arial, Helvetica, sans-serif;
}
.dataTables_wrapper .dataTables_filter input {

border-radius: 25px;

}

#example td, #example th {
  border: 1px solid #ddd;
  padding: 8px;
}


#count{
padding: 22px 25px;
margin-top: -49px;
margin-left: 650px;
background-color: #1ed5a5;
font-size: large;
border-radius: 50%;
}
</style>
<body>

<div id="wrapper">

    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
		
            <ul class="nav metismenu" id="side-menu">
			
                <li class="nav-header">
				
                    <div class="dropdown profile-element">
							
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <center><img src="<?php echo base_url()?>assets/images/drapp.jpg" style="max-width:170px;height: 100px;" /></center></a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="<?php echo base_url()?>index.php/logout">Cerrar sesión</a></li>
                            </ul>
							
                    </div>
                    <div class="logo-element">
                        MS+
                    </div>
                </li>
               
<?php

function activeClass($vcat="")
{
  $arr = explode(",", $vcat);
  $cat = "";
  if(isset($_GET['cat']) && $_GET['cat'] != "") $cat = $_GET['cat'];
  
  if(in_array($cat, $arr)) return " class='active'";
}

?>
      
      <li <?=activeClass("")?>>
                  <a href="<?php echo base_url()?>index.php/dash"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
              </li>
              
                <li <?=activeClass("pacientes,ficha_medica")?>>
                    <a href="#"><i class="fa fa-stethoscope"></i> <span class="nav-label">Pacientes</span></a>
                    <ul class="nav nav-second-level">
	                    <li <?=activeClass("pacientes")?>><a href="<?php echo base_url()?>index.php/patients">Pacientes</a></li>
                    </ul>
                    
                </li>
                <li <?=activeClass("medicos,especialidades")?>>
                    <a href="#"><i class="fa fa-user-md"></i> <span class="nav-label">Médicos</span></a>
                    <ul class="nav nav-second-level">
                      <li <?=activeClass("medicos")?>><a href="<?php echo base_url()?>index.php/doctors">Médico</a></li>
                      <li <?=activeClass("medicos")?>><a href="<?php echo base_url()?>index.php/requests">Peticiones del doctor</a></li>
                    </ul>
                    
                </li>
                <li <?=activeClass("enfermedades,farmacos,precios,logs,usuarios")?>>
                    <a href="#"><i class="fa fa-cogs"></i> <span class="nav-label">Administración</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
	                    <li <?=activeClass("enfermedades")?>><a href="<?php echo base_url()?>index.php/transactions">Actas</a></li>
                        <li <?=activeClass("enfermedades")?>><a href="<?php echo base_url()?>index.php/spacializations">especialización</a></li>
                        <li <?=activeClass("enfermedades")?>><a href="<?php echo base_url()?>index.php/appointments">Equipo</a></li>
                        <li <?=activeClass("logs")?>><a href="<?php echo base_url()?>index.php/logout">Log out</a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top " role="navigation" style="margin-bottom: 0">
                <!-- <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-success" href="#"><i class="fa fa-bars"></i> </a>
                </div> -->
                <ul class="nav navbar-top-links navbar-right">
					<li>
						<span class="m-r-sm text-muted welcome-message">Bienvenido/a a <strong>Dr App Backend</strong>!</span>
					</li>
                    <li>
                        <a href="<?php echo base_url()?>index.php/logout">
                            <i title="Logout" class="fa fa-sign-out"></i> 
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div id="main">


<div class="w3-container">
<div id="contents" style="width:100%;float:left;" ><?php echo $contents ?></div>
</div>

</div>
        
<div  class="footer">
    <div class="pull-right">
        Desarrollado por <strong><a href="#">Drapp</a></strong>
    </div>
</div>

    </div>
</div>




<!-- Custom and plugin javascript -->
<!-- <script src="../assets/js/inspinia.js"></script> -->
<!-- <script src="../assets/js/plugins/pace/pace.min.js"></script> -->

<script>

</script>
<script>

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

</body>

</html>
