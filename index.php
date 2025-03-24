<?php
session_start();
  	include_once "action/config.php";
  	if(isset($_COOKIE['userdata'])){
	  	$ud=explode(',',$_COOKIE['userdata']);
	  	$_SESSION['user']=$ud[0];
	  	$_SESSION['role']=$ud[1];
	  	$_SESSION['shop']=$ud[2];
		$_SESSION['user_id']=$us[3];
  	}
  	if(isset($_SESSION['user'])){
	   	header("Location:home.php?pagename=home");
	   	echo "<script>location='home.php?pagename=home'</script>";
  	}
  	$obj=new database();   
  	$msg="";
	if(isset($_POST['login'])){
	   // echo '<pre>';
	   // print_r($_POST); die;
		$user=$_POST['username'];
		$pass=$_POST['password'];
		if(isset($_POST['remember'])){	$check=1; }
		else{ $check=0;}
		$flag =false;
		$table="`users`";
		$run=$obj->login($table,$user,$pass);
		if(is_array($run)){
			$flag=true;
// 			$_SESSION['user']=$user;/**/
			$_SESSION['user']=$run['user_id'];

			$_SESSION['role']=$run['role'];
			$_SESSION['shop']=$run['shop'];	
			$_SESSION['user_id']=$run['user_id'];	
			$_SESSION['check']=$check;
		 }
		    if($flag===true){
	   			header("Location:home.php");
			}
	       else if($flag==false){
			  $msg="<center>Wrong username or password!!</center>";
		   }	  
	} 
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
        <!-- Bootstrap Core JavaScript -->
        <script src="bootstrap/js/jquery-3.1.1.min.js"></script>
    	<title>Login</title>
    </head>
    
    <body>
    	<div class="container">
        	<div class="row" style="margin-top:100px;">
            	<div class="col-md-4"></div>
            	<div class="col-md-4">
                	<div class="panel panel-success">
                    	<div class="panel-heading">
                        	<h4>Login</h4>
                        </div>
                        <div class="panel-body">
                        	<form action="#" method="post">
                            	<div class="input-group">
		   							<span class="input-group-addon"><i class="fa fa-user"></i></span>
		   							<input type="text" name="username" placeholder="Username" class="form-control">
         						</div><br>
                            	<div class="input-group">
		   							<span class="input-group-addon"><i class="fa fa-lock"></i></span>
		   							<input type="password" name="password" placeholder="Password" class="form-control">
         						</div><br>
                                <div class="input-group">
                                	<label class="checkbox-inline"><input type="checkbox" name="remember" value="1">Remember Me</label>
                                </div>
                                <div class="text-danger" style="margin:5px 0;" ><?php echo $msg; ?></div>
                                <div class="input-group center-block">
                                	<input type="submit" name="login" class="btn  btn-primary  center-block"  value="Login" style="width:100%">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    	<script src="bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>