<?php
session_start();
include("config.php");
$obj=new database();
if(isset($_POST['deleteUser'])){
	$id=$_POST['id'];
	$run=$obj->delete("`users`","`id`='$id'");
	if($run===true){
		$_SESSION['msg']="Successfully Deleted!";
	
	}else{
		$_SESSION['err']=$run;
	}
}
elseif(isset($_POST['deleteTailor'])){
	$id=$_POST['id'];
	$run=$obj->delete("`tailors`","`id`='$id'");
	if($run===true){
		$_SESSION['msg']="Successfully Deleted!";
	
	}else{
		$_SESSION['err']=$run;
	}
}
elseif(isset($_POST['deleteSupplier'])){
	$id=$_POST['id'];
	$run=$obj->delete("`supplier`","`id`='$id'");
	if($run===true){
		$_SESSION['msg']="Successfully Deleted!";
	
	}else{
		$_SESSION['err']=$run;
	}
}
elseif(isset($_POST['deletePtemp'])){
	$id=$_POST['id'];
	$run=$obj->delete("`material_temp`","`id`='$id'");
	if($run===true){
		echo "Successfully Deleted!";
	
	}else{
		echo $run;
	}
}
elseif(isset($_POST['deleteStemp'])){
	$id=$_POST['id'];
	$array=$obj->get_details("`job_temp`","*","`id`='$id'");
	$run=$obj->delete("`job_temp`","`id`='$id'");
	if($run===true){
		$update=$obj->update("`stock`","`quantity`=`quantity`+'$array[quantity]'","`id`='$array[stock_id]'");
		echo "Successfully Deleted!";
	
	}else{
		echo $run;
	}
}
elseif(isset($_POST['deleteGtemp'])){
	$id=$_POST['id'];
	$run=$obj->delete("`goods_temp`","`id`='$id'");
	if($run===true){
		echo "Successfully Deleted!";
	
	}else{
		echo $run;
	}
}
elseif(isset($_GET['del_packable_temp']) && $_GET['del_packable_temp']=='del_packable_temp'){
     $id=$_GET['id'];
	 $id=(int)$id;
	$shop=$_GET['shop'];
	$table="`packabletemp`";
	$where="`id` = '$id'";
	$delete=$obj->delete($table,$where);
	if($delete===true){
		echo "Deleted Successfully";
	}
	else{
		echo $delete;
	}
}
?>