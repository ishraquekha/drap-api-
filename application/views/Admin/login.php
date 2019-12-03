<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Drapp :: Backend</title>

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, "", window.location.href);
        };
    });
    </script> -->
</head>

<body style="background-color: #ffffff;">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
				<img src="../assets/images/drapp.jpg" style="max-width:300px;"/>
			</div>
            <h3></h3>
            </p>
			<br /><br />
            <h3 style="color:#ffffff;">Backend</h3>
			<br />
			<?php if (isset($_GET['error'])) { ?>
			<div class="alert alert-danger alert-dismissable">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                Usuario o Clave <b>incorrectos!</b>
			</div>
			<?php } ?>
            <form class="m-t" role="form" method="post" action="<?php echo base_url()?>index.php/dashboard">
                <div class="form-group">
                    <input type="text" id="usuario" name="username" class="form-control" placeholder="Usuario" required>
                </div>
                <div class="form-group">
                    <input type="password" id="clave" name="pass" class="form-control" placeholder="Clave" required>
                </div>
                <button type="submit" class="btn btn-success block full-width m-b">Entrar</button>
            </form>
        </div>
    </div>
    
    <script src="../assets/js/jquery-2.1.1.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

</body>

</html>
