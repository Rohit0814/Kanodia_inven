<?php
session_start();
  	if(isset($_SESSION['user'])){
		$user=$_SESSION['user'];
		$role=$_SESSION['role'];
		$shop=$_SESSION['shop'];
		$cookie_name = 'userdata';
		$cookie_value = $user.",".$role.",".$shop;
		if((isset($_SESSION['check']) && $_SESSION['check']==1) && !isset($_COOKIE[$cookie_name])){
			if(setcookie($cookie_name, $cookie_value, time() + (86400 * 30), '/')) // 86400 = 1 day
			{	
				unset($_SESSION['check']);
			}
		}
	}
	else{
   		header("Location:index.php");
   		echo "<script>location='index.php'</script>"; 
	}
  	include_once "action/config.php";
  	$obj=new database();   
	$getshop=$obj->get_details("`shop`","*","`id`='$shop'");$_SESSION['shop_name']=$getshop['name'];
    $job_process=$obj->get_rows("`job_process`","*","`status`=1");
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- Bootstrap Core CSS -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom Fonts -->
        <link href="bootstrap/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- Custom CSS -->
        <link href="css/style.css" rel="stylesheet">
        <!-- Bootstrap Core JavaScript -->
        <script src="bootstrap/js/jquery-3.1.1.min.js"></script>
    	<title>Home</title>
    </head>
    <body>
    	<?php include("demoheader.php");
        //include("header.php");
        ?>
    	<div class="container-fluid">
        	<div class="row">
            	<div class="col-md-12">
                    <center><br />
                    <?php if($_SESSION['role']=='worker'){?>
                        <h1 class="text-danger"> <?php echo strtoupper($_SESSION['user']);?></h1>
                        <?php }?>
                        <h1 class="text-success">Welcome to Inventory Management System</h1>
                        <br />
                        <h2 class="text-danger">sapnay lifestyle pvt ltd.</h2>
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <P class="text-danger" align="right">Powered By @Brightcode Software Services Pvt Ltd</P>
                        <P class="text-warning" align="right">+919386806214, +919304748714</P>
                        <P class="text-info" align="right"><a href="https://brightcodess.com" target="_blank">www.brightcodess.com</a></P>
                    </center>
                </div><!-- end of col-md-12 -->
            </div>
        </div>
    	<script src="bootstrap/js/bootstrap.min.js"></script>
        <script>
        	$(document).ready(function(e) {
				var shop='<?php echo $_SESSION['shop_name']; ?>';
                $.ajax({
					type:"GET",
					url:"backup/backup.php",
					data:{time:"login",shop:shop},
					success: function(data){}
				});
            });	
        </script>      
    </body>
</html>