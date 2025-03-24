
<?php 
// print_r($_SESSION); die;
	if(isset($_SESSION['user'])){
		$user=$_SESSION['user'];
		$role=$_SESSION['role'];
		$shop=$_SESSION['shop'];
	  }
	  else{
		   header("Location:index.php");
		   echo "<script>location='index.php'</script>"; 
	  }
	  include_once "../action/config.php";
		$obj=new database();
		$job_process=$obj->get_rows("`job_process`","*","`status`=1");
		// print_r($job_process); die;
?>

<nav class="navbar navbar-inverse">
  	<div class="container-fluid" >
    	<div class="navbar-header">
      		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        		<span class="icon-bar"></span>
        		<span class="icon-bar"></span>
        		<span class="icon-bar"></span>
      		</button>
      		<a class="navbar-brand" href="../home.php?pagename=home" style="color: white;">
        		<?php echo $_SESSION['shop_name'];?>
      		</a>
    	</div>
    	<div class="collapse navbar-collapse" id="myNavbar">
      		<ul class="nav navbar-nav navbar-right"  style="font-size:13px;">
				<?php //echo "hii"; die; ?>
        		 <?php if(isset($_GET['pagename'])){ $pagename=$_GET['pagename']; }else{$pagename="home";} ?>
        		<li <?php if($pagename=="home"){?> class="active"<?php } ?>><a href="../home.php?pagename=home"><i class="fa fa-home"></i>Home</a></li>
             
            	<?php if($role=='admin'){ ?>
                <li class="dropdown <?php if($pagename=="packable" || $pagename=="stocklist"){echo "active"; } ?>"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-list"></i> Stock<span class="caret"></span></a>
                    <ul class="dropdown-menu">
						<li <?php  if($pagename=="stocklist"){?> class="active"<?php } ?>><a href="../stock?pagename=stocklist">Stock List</a></li>
                        <li <?php  if($pagename=="packable"){?> class="active"<?php } ?>><a href="../stock/addpackable.php?pagename=packable">Job Work Inward Entry</a></li>
                        <li <?php  if($pagename=="finish_stock"){?> class="active"<?php } ?>><a href="../stock/finish_stock.php?pagename=finish_stock">Finish Stock</a></li>
						<li <?php  if($pagename=="bale_meter_tally"){?> class="active"<?php } ?>><a href="../stock/bale_meter_stock.php?pagename=bale_meter_tally">Bale Meter Tally</a></li>
                    </ul>
                </li>

				<?php if($role=='admin'){ ?>
                <li class="dropdown <?php if($pagename=="jobprocess" || $pagename=="jobprocess"){echo "active"; } ?>"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-list"></i> Job Process<span class="caret"></span></a>
                    <ul class="dropdown-menu">
						<?php foreach($job_process as $job) { ?>
						<li <?php  if($pagename=="jobprocess"){?> class="active"<?php } ?>><a href="../job_process?pagename=<?php echo $job['slug'] ?>"><?php echo $job['process']; ?></a></li>
						<?php } ?>
                    </ul>
                </li>
					<?php } ?>

            	<!-- <?php }if($role=='admin' || $role=='cutter'){ ?>
				<li <?php  if($pagename=="cutting"){?> class="active"<?php } ?>><a href="../cutting?pagename=cutting"><i class="fa fa-cut"></i>&nbsp;Cutting Sheet</a></li> 
				<?php }if($role=='admin' || $role=='cutter' || $role=='worker'){ ?>
				<li <?php  if($pagename=="stitching"){?> class="active"<?php } ?>><a href="../stitching?pagename=stitching"><i class="fa fa-file"></i>&nbsp;Stitching Recoard</a></li>
				<li <?php  if($pagename=="zigzag"){ ?> class="active"<?php } ?>><a href="../zigzag?pagename=zigzag"><i class="fa fa-file"></i>&nbsp;Zigzag Recoard</a></li>
				<?php }if($role=='admin'){ ?>
				<li <?php  if($pagename=="stock_movement"){?> class="active"<?php } ?>><a href="../stock_movement?pagename=stock_movement"><i class="fa fa-file"></i>&nbsp;Stock Movement</a></li>  -->
            	
				<li <?php  if($pagename=="masterkey"){?> class="active"<?php } ?>><a href="../masterkey?pagename=masterkey"><i class="fa fa-key"></i>&nbsp;Master Key</a></li>
				<li <?php  if($pagename=="punch"){?> class="active"<?php } ?>><a href="../punchattendance?pagename=punch"><i class="fa fa-key"></i>&nbsp;Punch Entry/Exit</a></li>
            	<li <?php if(isset($_GET['pagename'])){ if($pagename=="admin"){?> class="active"<?php } } ?>><a href="../admin?pagename=admin"><i class="fa fa-user"></i>&nbsp;Admin</a></li>
            	<li class="dropdown <?php if($pagename=="cuttinglist" || $pagename=="stitchinglist"){echo "active"; } ?>">
                	<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-list"></i> Reports<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li <?php if($pagename=="paymentreport"){?> class="active"<?php } ?>><a href="../reports/payment_report.php?pagename=paymentreport">Payment Report</a></li>
                        <li <?php if($pagename=="productionreport"){?> class="active"<?php } ?>><a href="../reports/production_report.php?pagename=productionreport">Production Report</a></li>
                    </ul>
                </li>
				<?php } ?>
				<!--<li><a href="../backup/"><i class="fa fa-download"></i>&nbsp;Backup</a></li>-->
        		<li><a href="../logout.php"><i class="fa fa-sign-out"></i>&nbsp;Logout</a></li>
      		</ul>
    	</div>
  	</div>
</nav>