<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use Picqer\Barcode\BarcodeGeneratorPNG;


// session_start();
if (!isset($_SESSION)) {
	session_start();
	ob_start();
}
include("config.php");
$obj = new database();
function Imageupload($dir, $inputname, $allext, $pass_width, $pass_height, $pass_size, $newname, $key = -1)
{
	$error = "";

	if ($key >= 0) {
		if (file_exists($_FILES["$inputname"]["tmp_name"][$key])) {
			//do this contain any file check
			$file_extension = strtolower(pathinfo($_FILES["$inputname"]["name"][$key], PATHINFO_EXTENSION));
			if (in_array($file_extension, $allext)) {
				//file extension check
				list($width, $height, $type, $attr) = getimagesize($_FILES["$inputname"]["tmp_name"][$key]);
				// print_r($image_weight); die;
				$image_weight = $_FILES["$inputname"]["size"][$key];
				// print_r($width); echo "<br>";
				// print_r($pass_width); die;
				if ($width <= "$pass_width" && $height <= "$pass_height" && $image_weight <= "$pass_size") {
					//dimension check
					$tmp = $_FILES["$inputname"]["tmp_name"][$key];
					$extension[1] = "jpg";
					//$extension = explode(".", $_FILES["$inputname"]["name"]);
					$name = $newname . "." . $extension[1];
					//$extension[1] ="jpg";
					// die;
					if (move_uploaded_file($tmp, "$dir" . $name)) {
						return true;
					}
				} else {
					$error .= "Please upload photo size of $pass_width X $pass_height!!!";
					//echo $error;
				}
			} else {
				$error .= "Please Upload an Image!!!";
				//echo $error;
			}
		}
	} else {
		if (file_exists($_FILES["$inputname"]["tmp_name"])) {
			//do this contain any file check
			$file_extension = strtolower(pathinfo($_FILES["$inputname"]["name"], PATHINFO_EXTENSION));
			if (in_array($file_extension, $allext)) {
				//file extension check
				list($width, $height, $type, $attr) = getimagesize($_FILES["$inputname"]["tmp_name"]);
				$image_weight = $_FILES["$inputname"]["size"];
				if ($width <= "$pass_width" && $height <= "$pass_height" && $image_weight <= "$pass_size") {
					//dimension check
					$tmp = $_FILES["$inputname"]["tmp_name"];
					$extension[1] = "jpg";
					//$extension = explode(".", $_FILES["$inputname"]["name"]);
					$name = $newname . "." . $extension[1];
					//$extension[1] ="jpg";
					// die;
					if (move_uploaded_file($tmp, "$dir" . $name)) {
						return true;
					}
				} else {
					$error .= "Please upload photo size of $pass_width X $pass_height!!!";
					//echo $error;
				}
			} else {
				$error .= "Please Upload an Image!!!";
				//echo $error;
			}
		}
		//print_r($_FILES);
		$error .= "Please Select an Image!!!";
		// $error;
	}
	return $error;
}
if (isset($_POST['adduser'])) {
	$username = strip_tags($_POST['username']);
	$password = strip_tags($_POST['password']);
	$role = strip_tags($_POST['role']);
	$shop = strip_tags($_POST['shop']);
	$active = strip_tags($_POST['active']);
	$columns = "(`username`, `password`, `role`, `shop`, `active`)";
	$values = "('$username','$password','$role','$shop','$active')";
	$run = $obj->insert("`users`", $columns, $values);
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Added!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../admin/?pagename=admin");
} elseif (isset($_POST['save_worker'])) {
	$designation = strip_tags($_POST['designation']);
	$name = strip_tags($_POST['name']);
	$username = strip_tags($_POST['username']);
	$password = strip_tags($_POST['password']);
	$mobile = strip_tags($_POST['mobile']);
	$address = strip_tags($_POST['address']);
	$aadhar = strip_tags($_POST['aadhar']);
	$pan = strip_tags($_POST['pan']);
	$gst = strip_tags($_POST['gst']);
	$bank = strip_tags($_POST['bank']);
	$account = strip_tags($_POST['account']);
	$ifsc = strip_tags($_POST['ifsc']);
	$reference = strip_tags($_POST['reference']);
	$payment_type = strip_tags($_POST['payment_type']);
	$payment = strip_tags($_POST['payment']);
	// $w_hrs=strip_tags($_POST['w_hrs']);
	$shop = strip_tags($_POST['shop']);
	$columns = "(`designation`,`name`, `mobile`, `address`, `aadhar`, `pan`, `gst`, `bank`, `account`, `ifsc`, `reference`,`payment_type`,`payment`, `active`, `shop`)";
	$values = "('$designation','$name','$mobile','$address','$aadhar','$pan','$gst','$bank','$account','$ifsc','$reference','$payment_type','$payment','1','$shop')";
	$run = $obj->insert("`worker`", $columns, $values);
	$last_id = $obj->get_last_row("`worker`", $column = "id", $where = "`mobile`='$mobile'");
	$last_id = $last_id['id'];
	if ($run === true) {
		$col = "(`user_id`,`username`,`password`,`role`,`shop`,`active`)";
		$val = "('$last_id','$username','$password','$designation','$shop','1')";
		$run1 = $obj->insert("`users`", $col, $val);
		if ($run1) {
			$_SESSION['msg'] = "Successfully Added!";
		} else {
			$_SESSION['err'] = $run1;
			$this->db->delete('worker', "`id`=$last_id");
			header("Location:../masterkey/?pagename=masterkey");
			// exit;
		}
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey");
	// exit;
} elseif (isset($_POST['save_raw'])) {
	$name = strip_tags($_POST['name']);
	$unit = strip_tags($_POST['unit']);
	$rate = strip_tags($_POST['rate']);
	$shop = strip_tags($_POST['shop']);
	$type = strip_tags($_POST['type']);
	$columns = "(`name`, `unit`, `rate`, `type`, `shop`)";
	$values = "('$name','$unit','$rate','$type','$shop')";
	$run = $obj->insert("`raw_material`", $columns, $values);
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Added!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&rpage");
} elseif (isset($_POST['save_width'])) {
	// echo "<pre>";print_r($_POST);die;
	$width = strip_tags($_POST['width']);
	$unit = strip_tags($_POST['unit']);
	$shop = strip_tags($_POST['shop']);
	$columns = "(`width`, `unit`,`shop`)";
	$values = "('$width','$unit','$shop')";
	$run = $obj->insert("`width`", $columns, $values);
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Added!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&wipage");
} elseif (isset($_POST['save_product'])) {
	// echo "<pre>";print_r($_POST);die;
	$product = strip_tags($_POST['product']);
	$job_process = explode(",", $_POST['job_process']);
	$job_squence = json_encode($job_process);
	// echo "<pre>";print_r($job_squence);die;
	$shop = strip_tags($_POST['shop']);
	$created_at = date('Y-m-d');
	$updated_at = date('Y-m-d');
	$columns = "(`product_name`,`job_squence`,`shop`, `created_at`,`updated_at`)";
	$values = "('$product','$job_squence','$shop','$created_at','$updated_at')";
	$run = $obj->insert("`product`", $columns, $values);
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Added!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&productpage");
} elseif (isset($_POST['save_payment'])) {
	$worker = strip_tags($_POST['worker']);
	$bedsheet_rate = strip_tags($_POST['bedsheet_rate']);
	$pillow_rate = strip_tags($_POST['pillow_rate']);
	$work_type = strip_tags($_POST['work_type']);
	$shop = strip_tags($_POST['shop']);
	$columns = "(`worker`,`work_type`,`bedsheet_rate`,`pillow_rate`,`shop`)";
	$values = "('$worker','$work_type','$bedsheet_rate','$pillow_rate','$shop')";
	$run = $obj->insert("`payment`", $columns, $values);
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Added!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&ppage");
} elseif (isset($_POST['save_attendance'])) {
	$date = strip_tags($_POST['date']);
	$worker = $_POST['worker'];
	$remark = strip_tags($_POST['remark']);
	$shop = strip_tags($_POST['shop']);
	$columns = "(`worker`,`date`,`amount`,`remark`,`shop`)";
	foreach ($worker as $w) {
		$payment = $obj->get_last_row("`worker`", "`payment`", "`id`='$w' and `shop`='$shop'");
		$values = "('$w','$date','$payment[payment]','$remark','$shop')";
		$run = $obj->insert("`attendance`", $columns, $values);
	}
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Added!";
	} else {
		$_SESSION['err'] = $run;
	}
	// print_r($_GET); die;
	if ($_GET['pagename'] == "punch") {
		header("Location:../punchattendance/?pagename=punch");
	} else {
		header("Location:../masterkey/?pagename=masterkey&apage");
	}
} elseif (isset($_POST['save_desination'])) {
	$designation = strip_tags($_POST['designation']);
	$shop = strip_tags($_POST['shop']);
	$columns = "(`designation`, `shop`)";
	$values = "('$designation','$shop')";
	$run = $obj->insert("`designation`", $columns, $values);
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Added!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&dpage");
} elseif (isset($_POST['save_jobprocess'])) {
	$process = strip_tags($_POST['process_name']);
	$designation = json_encode($_POST['permission']);
	// print_r($designation); die;
	$lower_process = strtolower($process);

	$formate_process = preg_replace('/[^a-z0-9]+/', '-', $lower_process);

	$slug = trim($formate_process, '-');
	// print_r($slug); die;
	$shop = strip_tags($_POST['shop']);
	$columns = "(`process`,`permission`, `slug`, `shop`)";
	$values = "('$process','$designation', '$slug','$shop')";
	$run = $obj->insert("`job_process`", $columns, $values);
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Added!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&jprocess");
} elseif (isset($_POST['save_size'])) {
	// echo "<pre>";
	// print_r($_POST); die;
	$item = strip_tags($_POST['item']);
	$size = strip_tags($_POST['size']);
	$shop = strip_tags($_POST['shop']);
	$widths = $_POST['width'];
	$pattern = $_POST['pattern'];
	$consume = $_POST['consume'];
	$rate = $_POST['rate'];

	$sub_prod = $_POST['sub_prod'];
	$sub_consume = $_POST['sub_consume'];


	$columns = "(`item`, `size`, `shop`)";
	$values = "('$item','$size','$shop')";
	$run = $obj->insert("`size`", $columns, $values);
	if ($run === true) {
		$getid = $obj->get_last_row("`size`", "`id`", "`size`='$size' and `shop`='$shop'");
		$size_id = $getid['id'];
		$columns2 = "(`prod_id`,`size_id`, `width_id`, `consume`, `pattern`, `rate`, `shop`)";
		foreach ($widths as $key => $width) {
			if ($width == '') {
				continue;
			}
			$values2 = "('$item','$size_id','$width','$consume[$key]',$pattern[$key], $rate[$key],'$shop')";
			$run2 = $obj->insert("`consumption`", $columns2, $values2);

			foreach ($sub_prod as $key1 => $sub) {
				if ($key1 != $width) {
					continue;
				}

				// print_r($sub_prod); die;
				foreach ($sub_consume as $key2 => $sub_con) {
					// print_r($key2); print_r($pattern[$key]);die;
					if ($key2 != $pattern[$key]) {
						continue;
					}
					$get_consume = $obj->get_last_row("`consumption`", "`id`", "`size_id`='$size_id' and `width_id`='$width' and`shop`='$shop'");
					$consume_id = $get_consume['id'];
					foreach ($sub_con as $key5 => $sub2) {
						if ($sub[$key5] != 0) {
							$columns3 = "(`consumption_id`, `subsidary_id`, `subsidary_consume`, `product_id`, `shop`)";
							$values3 = "('$consume_id','$sub[$key5]','$sub2','$item','$shop')";
							$run3 = $obj->insert("`subsidary_details`", $columns3, $values3);
							if ($run3 === false) {
								echo $obj->db->last_query();
								die;
							}
						}
					}
				}
				// print_r($consume_id); die;
			}
		}
		$_SESSION['msg'] = "Successfully Added!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&spage");
} elseif (isset($_POST['save_pattern'])) {
	$item = strip_tags($_POST['item']);
	$size = strip_tags($_POST['pattern']);
	$shop = strip_tags($_POST['shop']);

	// $widths=$_POST['width'];
	// $consume=$_POST['consume'];
	$created_at = date('Y-m-d');
	$updated_at = date('Y-m-d');
	$columns = "(`prod_id`, `pattern_name`, `shop`,`created_at`, `updated_at`)";
	$values = "('$item','$size','$shop','$created_at','$updated_at')";
	$run = $obj->insert("`pattern`", $columns, $values);
	if ($run === true) {
		$getid = $obj->get_last_row("`pattern`", "`id`", "`prod_id`='$size' and `shop`='$shop'");
		$size_id = $getid['id'];
		$columns2 = "(`size_id`, `width_id`, `consume`, `shop`)";
		foreach ($widths as $key => $width) {
			if ($width == '') {
				continue;
			}
			$values2 = "('$size_id','$width','$consume[$key]','$shop')";
			$run2 = $obj->insert("`consumption`", $columns2, $values2);
		}
		$_SESSION['msg'] = "Successfully Added!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&patternpage");
} elseif (isset($_POST['save_finished'])) {
	// echo "<pre>";print_r($_POST);die;
	$finished_product = strip_tags($_POST['finished_product']);
	$bedsheet_size = strip_tags($_POST['bedsheet_size']);
	$bedsheet_qty = strip_tags($_POST['bedsheet_qty']);
	$pillow_size = strip_tags($_POST['pillow_size']);
	$pillow_qty = strip_tags($_POST['pillow_qty']);
	$shop = strip_tags($_POST['shop']);
	$columns = "(`finished_product`, `bedsheet_size`,`bedsheet_qty`,`pillow_size`,`pillow_qty`, `shop`)";
	$values = "('$finished_product','$bedsheet_size','$bedsheet_qty','$pillow_size','$pillow_qty','$shop')";
	$run = $obj->insert("`finished`", $columns, $values);
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Added!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&fpage");
} elseif (isset($_POST['save_loose_stock'])) {
	$material_type = strip_tags($_POST['material_type']);
	if ($material_type == 'subsidiary') {
		$date = strip_tags($_POST['sub_date']);
		$raw_id = strip_tags($_POST['sub_raw_id']);
		$d_no = '';
		$width_id = 0;
		$meter = 0;
		$quantity = strip_tags($_POST['sub_quantity']);
		$shop = strip_tags($_POST['shop']);
		$photo = '';
		$remark = strip_tags($_POST['sub_remark']);
	} elseif ($material_type == 'stitching') {
		$date = strip_tags($_POST['sti_date']);
		$raw_id = strip_tags($_POST['sti_raw_id']);
		$d_no = '';
		$width_id = 0;
		$meter = 0;
		$quantity = strip_tags($_POST['sti_quantity']);
		$shop = strip_tags($_POST['shop']);
		$photo = '';
		$remark = strip_tags($_POST['sti_remark']);
	} elseif ($material_type == 'loose') {
		$date = strip_tags($_POST['date']);
		$raw_id = strip_tags($_POST['raw_id']);
		$d_no = strip_tags($_POST['d_no']);
		$width_id = strip_tags($_POST['width_id']);
		// $meter = strip_tags($_POST['meter']);
		$meter = 0;
		$quantity = strip_tags($_POST['quantity']);
		$shop = strip_tags($_POST['shop']);
		$photo = $_FILES['image']['name'];
		$remark = '';
	}
	if (!empty($photo)) {
		$photo = explode('.', $photo);
		$image = time() . $photo[0];
		$imagename = $_FILES['image']['tmp_name'];
		list($width, $height) = getimagesize($_FILES['image']['tmp_name']);
		$dir = "../uploads/";
		$allext = array("png", "PNG", "jpg", "JPG", "jpeg", "JPEG", "GIF", "gif");
		$check = Imageupload($dir, 'image', $allext, "3000", "3000", '100000000', $image);
		$image = $image . ".jpg";
	} else {
		$check = true;
		$image = '';
	}
	if ($check === true) {
		// $arr1 = $obj->get_details('`stock`', "`id`,`meter`", "`raw_id`='$raw_id' AND `width_id`='$width_id' AND `d_no`='$d_no'");
		// if (is_array($arr1)) {
		// 	$run = $obj->update("`stock`", "`meter`=`meter`+'$meter'", "`id`='$arr1[id]'");
		// 	$msg = "Meter Updated Successfully !";
		// } else {
		$columns = "(`material_type`,`date`,`raw_id`, `d_no`,`width_id`,`meter`,`quantity`,`image`,`shop`,`remark`)";
		$values = "('$material_type','$date','$raw_id','$d_no','$width_id','$meter','$quantity','$image','$shop', '$remark')";
		$run = $obj->insert("`stock`", $columns, $values);
		$msg = "stock Added Successfully !";
		// }
		if ($run === true) {
			$_SESSION['msg'] = $msg;
		} else {
			$_SESSION['err'] = $run;
		}
		header("Location:../stock?pagename=stocklist");
	} else {
		$_SESSION['msg'] = $check;
		header("Location:../stock?pagename=stocklist");
	}
	header("Location:../stock?pagename=stocklist");
} elseif (isset($_POST['addcuttingstock'])) {
	$date = strip_tags($_POST['date']);
	$raw_id = strip_tags($_POST['raw_id']);
	$d_no = strip_tags($_POST['d_no']);
	$width_id = strip_tags($_POST['width_id']);
	$meter = strip_tags($_POST['meter']);
	$quantity = strip_tags($_POST['quantity']);
	$shop = strip_tags($_POST['shop']);
	$photo = $_FILES['image']['name'];
	if (!empty($photo)) {
		$photo = explode('.', $photo);
		$image = time() . $photo[0];
		$imagename = $_FILES['image']['tmp_name'];
		list($width, $height) = getimagesize($_FILES['image']['tmp_name']);
		$dir = "../uploads/";
		$allext = array("png", "PNG", "jpg", "JPG", "jpeg", "JPEG", "GIF", "gif");
		$check = Imageupload($dir, 'image', $allext, "4000", "4000", '100000000', $image);
		$image = $image . ".jpg";
	} else {
		$check = true;
		$image = '';
	}
	if ($check === true) {
		$arr1 = $obj->get_details('`stock`', "`id`,`meter`", "`raw_id`='$raw_id' AND `width_id`='$width_id' AND `d_no`='$d_no'");
		if (is_array($arr1)) {
			$run = $obj->update("`stock`", "`meter`=`meter`+'$meter'", "`id`='$arr1[id]'");
			$msg = "Meter Updated Successfully !";
		} else {
			$columns = "(`date`,`raw_id`, `d_no`,`width_id`,`meter`,`quantity`,`image`,`shop`)";
			$values = "('$date','$raw_id','$d_no','$width_id','$meter','$quantity','$image','$shop')";
			$run = $obj->insert("`stock`", $columns, $values);
			$msg = "stock Added Successfully !";
		}
		if ($run === true) {
			$_SESSION['msg'] = $msg;
			header("Location:../cutting?pagename=cutting");
		} else {
			$_SESSION['err'] = $run;
			header("Location:../cutting?pagename=cutting");
		}
	} else {
		$_SESSION['msg'] = $check;
		header("Location:../cutting?pagename=cutting");
	}
	header("Location:../cutting?pagename=cutting");
} elseif (isset($_POST['tempbutton']) && $_POST['tempbutton'] == 'add') {

	$raw_id = strip_tags($_POST['raw_id']);
	$job_id = strip_tags($_POST['jobId']);
	$bill_date = strip_tags($_POST['bill_date']);
	$supplier_id = strip_tags($_POST['supplier_id']);
	$bill_no = strip_tags($_POST['bill_no']);
	$pur_date = strip_tags($_POST['pur_date']);
	$lot_no = strip_tags($_POST['lot_no']);
	$bale_no = strip_tags($_POST['bale_no']);
	$width_id = strip_tags($_POST['width_id']);
	$d_no = strip_tags($_POST['d_no']);
	$meter = strip_tags($_POST['meter']);
	$quantity = strip_tags($_POST['quantity']);
	$shop = strip_tags($_POST['shop']);
	$user_id = strip_tags($_POST['user_id']);
	$photo = $_FILES['image']['name'];
	if (!empty($photo)) {
		$photo = explode('.', $photo);
		$image = time() . $photo[0];
		$imagename = $_FILES['image']['tmp_name'];
		list($width, $height) = getimagesize($_FILES['image']['tmp_name']);
		$dir = "../uploads/";
		$allext = array("png", "PNG", "jpg", "JPG", "jpeg", "JPEG", "GIF", "gif");
		$check = Imageupload($dir, 'image', $allext, "3000", "3000", '100000000', $image);
		$image = $image . ".jpg";
	} else {
		$check = true;
		$image = '';
	}
	if ($check === true) {
		$arr1 = $obj->get_details('`packabletemp`', "`id`,`meter`", "`raw_id`='$raw_id' AND `width_id`='$width_id' AND `d_no`='$d_no'");
		if (is_array($arr1)) {
			$run = $obj->update("`packabletemp`", "`meter`=`meter`+'$meter'", "`id`='$arr1[id]'");
			$msg = "Meter Updated Successfully !";
		} else {
			$columns = "(`jobId`,`bill_date`,`supplier_id`,`bill_no`,`pur_date`,`lot_no`,`bale_no`,`image`,`raw_id`,`width_id`, `d_no`, `meter`, `qty`,`user_id`,`shop`) ";
			$values = "('$job_id','$bill_date',$supplier_id,'$bill_no','$pur_date','$lot_no','$bale_no','$image','$raw_id','$width_id','$d_no','$meter','$quantity','$user_id','$shop')";
			$run = $obj->insert("`packabletemp`", $columns, $values);
			$msg = "stock Added Successfully !";
		}
		if ($run === true) {
			echo $msg;
		} else {
			echo $run;
		}
	} else {
		echo $check;
	}
} elseif (isset($_POST['add_packable']) && $_POST['add_packable'] == 'save') {
	// $date = strip_tags($_POST['date']);
	$date = date('Y-m-d');
	$_POST['user_id'] = $_SESSION['user'];
	// print_r($_POST['Unit']); die;

	foreach ($_POST['bale_no'] as $key => $bale) {
		$image = '';
		$photo = $_FILES['image']['name'][$key];
		// echo "<pre>";
		// print_r($_FILES); die;
		if (!empty($photo)) {
			$photo = explode('.', $photo);
			$image = time() . $photo[0];
			// print_r($image); die;
			$imagename = $_FILES['image']['tmp_name'];
			list($width, $height) = getimagesize($_FILES['image']['tmp_name'][$key]);
			// print_r($_FILES['image']['tmp_name'][$key]); die;
			$dir = "../uploads/";
			$allext = array("png", "PNG", "jpg", "JPG", "jpeg", "JPEG", "GIF", "gif");
			// print_r($dir);
			$check = Imageupload($dir, 'image', $allext, "4000", "4000", '100000000', $image, $key);
			// print_r($check); die;
			$image = $image . ".jpg";
		}
		$columns = "(`jobid`,`bill_date`,`supplier_id`,`bill_no`,`pur_date`,`lot_no`,`date`,`bale_id`,`image`,`raw_id`, `width_id`,`d_no`,`quantity`, `current_stock`, `unit`,`shop`)";
		$values = "('" . $_POST['jobId'] . "','" . $_POST['bill_date'] . "'," . $_POST['supplier_id'] . ", '" . $_POST['bill_no'] . "','" . $_POST['pur_date'] . "','" . $_POST['lot_no'][$key] . "','" . $date . "','" . $_POST['bale_no'][$key] . "','" . $image . "','" . $_POST['raw_id'][$key] . "','" . $_POST['width_id'][$key] . "','" . $_POST['d_no'][$key] . "','" . $_POST['quantity'][$key] . "','" . $_POST['quantity'][$key] . "','" . $_POST['Unit'][$key] . "','" . $_POST['shop'] . "')";
		$table2 = "`stock`";
		$run2 = $obj->insert($table2, $columns, $values);
		if ($run2) {
			echo "Successfully Added!";
		} else {
			echo $run;
		}
		// print_r($values);
		// echo "<br>";
	}

	// $user_id = strip_tags($_POST['user_id']);
	// $shop = strip_tags($_POST['shop']);
	// $run = true;
	// if ($run === true) {
	// 	$packabletemp_list = $obj->get_rows("`packabletemp`", "*", "`shop`='$shop' AND `user_id`='$user_id'");
	// 	$obj->delete("`packabletemp`", "`shop`='$shop' AND `user_id`='$user_id'");
	// 	$table2 = "`stock`";
	// 	$columns2 = "(`jobid`,`bill_date`,`supplier_id`,`bill_no`,`pur_date`,`lot_no`,`date`,`bale_id`,`image`,`raw_id`, `width_id`,`d_no`,`meter`,`quantity`,`shop`, `barcode_id`)";
	// 	foreach ($packabletemp_list as $list) {
	// 		$arr1 = $obj->get_details('`stock`', "`id`,`meter`", "`raw_id`='$list[raw_id]' AND `width_id`='$list[width_id]' AND `d_no`='$list[d_no]'");
	// 		$pack_id = $list['bale_no'];
	// 		$generator = new BarcodeGeneratorPNG();
	// 		// print_r($obj); die;
	// 		$stmt = $obj->get_count('stock');
	// 		$count = $stmt + 1;
	// 		$barcode = $count;
	// 		$barcodeText = str_pad($count, 6, '0', STR_PAD_LEFT);

	// 		$barcodeImage = $generator->getBarcode($barcodeText, $generator::TYPE_CODE_128);

	// 		$filePath = 'barcodes/barcode_' . $barcodeText . '.png';

	// 		if (!file_exists('barcodes')) {
	// 			mkdir('barcodes', 0777, true);
	// 		}

	// 		file_put_contents($filePath, $barcodeImage);

	// 		// echo "Barcode saved to: " . $filePath; die;

	// 		if (is_array($arr1)) {
	// 			// $upd=$obj->update($table2,"`meter`=`meter`+'$list[meter]'","`id`='$arr1[id]'");	
	// 			$values2 = "('$list[jobId]','$list[bill_date]',$list[supplier_id], $list[bill_no], '$list[pur_date]', $list[lot_no],'$date','$pack_id','$list[image]','$list[raw_id]','$list[width_id]','$list[d_no]','$list[meter]','$list[qty]',$list[shop],'$barcodeText')";
	// 			$run2 = $obj->insert($table2, $columns2, $values2);
	// 		} else {
	// 			// $values2 = "('$date','$pack_id','$list[image]','$list[raw_id]','$list[width_id]','$list[d_no]','$list[meter]','$list[qty]',$list[shop])";
	// 			$values2 = "('$list[jobId]','$list[bill_date]',$list[supplier_id], $list[bill_no], '$list[pur_date]', $list[lot_no],'$date','$pack_id','$list[image]','$list[raw_id]','$list[width_id]','$list[d_no]','$list[meter]','$list[qty]',$list[shop],'$barcodeText')";
	// 			$run2 = $obj->insert($table2, $columns2, $values2);
	// 		}
	// 	}
	// 	echo "Successfully Added!";
	// } else {
	// 	echo $run;
	// }
	header("Location:../stock/inward_list.php?pagename=packable&type=entry");
} elseif (isset($_POST['save_cuttingsheet'])) {
	// echo "<pre>";print_r($_POST);die;
	$date = $_POST['date'];
	$worker = $_POST['worker_id'];
	$finalmeter = $_POST['finalmeter'];
	$finalbedsheet = $_POST['finalbed'];
	$finalpillow = $_POST['finalpillow'];
	$wastage = $_POST['wastage'];
	$excess = $_POST['excess'];
	$shop = $_POST['shop'];
	$user = $_POST['user'];
	$rcount = $_POST['rcount'];

	$table = "`cutting`";
	$columns = "(`date`,`worker`, `finalmeter`,`finalbedsheet`,`finalpillow`,`wastage`,`excess`,`shop`,`user`)";
	$values = "('$date','$worker','$finalmeter','$finalbedsheet','$finalpillow','$wastage','$excess','$shop','$user')";
	$run = $obj->insert($table, $columns, $values);
	if ($run === true) {
		$arr = $obj->get_last_row($table, "`id`", "`shop`='$shop' AND `user`='$user'");
		$cutting_id = $arr['id'];
		$table1 = "`cuttingitem`";
		$columns1 = "(`cutting_id`,`stock_id`, `totalmeter`,`totalbedsheet`,`totalpillow`,`totalconsume`)";
		$table2 = "`cuttingdetail`";
		$columns2 = "(`cuttingitem_id`,`meterbreakup`,`bedsheetsizeid`,`pillowsizeid`,`bedsheetsize`,`bedsheetpcs`,`pillowsize`,`pillowpcs`,`consume`,`remark`)";
		for ($i = 1; $i <= $rcount; $i++) {
			$stock_id = $_POST['stock_id' . $i];
			$totalmeter = $_POST['totalmeter' . $i];
			$totalbedsheet = $_POST['totalbed' . $i];
			$totalpillow = $_POST['totalpillow' . $i];
			$totalconsume = $_POST['totalconsume' . $i];
			$count = $_POST['count' . $i];
			$values1 = "('$cutting_id','$stock_id','$totalmeter','$totalbedsheet','$totalpillow','$totalconsume')";
			$run1 = $obj->insert($table1, $columns1, $values1);
			if ($run1 === true) {
				$update = $obj->update("`stock`", "`meter`=`meter`-'$totalmeter'", "`id`='$stock_id'");
				$width = $obj->get_details("`stock`", "`width_id`", "`id`='$stock_id'");
				$arr1 = $obj->get_last_row($table1, "`id`", "`cutting_id`='$cutting_id'");
				$cuttingitem_id = $arr1['id'];
				for ($j = 1; $j <= $count; $j++) {
					$arr_bed = $_POST['bedsheetsize' . $i . $j];
					$arr_bedsheetsizeid = array();
					foreach ($arr_bed as $ab) {
						if ($ab != '') {
							$bsizeid = $obj->get_details("`consumption`", "`size_id`", "`width_id`=$width[width_id] AND `consume`=$ab");
							$arr_bedsheetsizeid[] = $bsizeid['size_id'];
						}
					}
					$arr_pill = $_POST['pillowsize' . $i . $j];
					$arr_pillowsizeid = array();
					foreach ($arr_pill as $ap) {
						if ($ap != '') {
							$bpillid = $obj->get_details("`consumption`", "`size_id`", "`width_id`=$width[width_id] AND `consume`=$ap");
							$arr_pillowsizeid[] = $bpillid['size_id'];
						}
					}
					$meterbreakup = $_POST['metrebreak' . $i . $j];
					$bedsheetsizeid = json_encode($arr_bedsheetsizeid);
					$pillowsizeid = json_encode($arr_pillowsizeid);
					$bedsheetsize = json_encode($_POST['bedsheetsize' . $i . $j]);
					$bedsheetpcs = json_encode($_POST['bpieces' . $i . $j]);
					$pillowsize = json_encode($_POST['pillowsize' . $i . $j]);
					$pillowpcs = json_encode($_POST['ppieces' . $i . $j]);
					$consume = json_encode($_POST['consume' . $i . $j]);
					$remark = json_encode($_POST['remark' . $i . $j]);

					$values2 = "('$cuttingitem_id','$meterbreakup','$bedsheetsizeid','$pillowsizeid','$bedsheetsize','$bedsheetpcs','$pillowsize','$pillowpcs','$consume','$remark')";
					$run2 = $obj->insert($table2, $columns2, $values2);
				}
			}
		}
	}
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Added!";
		header("Location:../cutting/printcutting.php?id=" . $cutting_id);
	} else {
		$_SESSION['err'] = $run;
		header("Location:../cutting?pagename=cutting");
	}
} elseif (isset($_POST['save_stitching'])) {
	// echo "<pre>";print_r($_POST);die;
	$date = $_POST['date'];
	$cutting_id = $_POST['cutting_id'];
	$alteration = $_POST['alteration'];
	$damage = $_POST['damage'];
	$palteration = $_POST['palteration'];
	$pdamage = $_POST['pdamage'];
	$working_hrs = $_POST['working_hrs'];
	$totalbedsheet = $_POST['totalbedsheet'];
	$totalpillow = $_POST['totalpillow'];
	$shop = $_POST['shop'];
	$user = $_POST['user'];
	$count = $_POST['count'];

	$table = "`stitching`";
	$columns = "(`date`,`cutting_id`, `alteration`,`damage`, `palteration`,`pdamage`,`working_hrs`,`totalbedsheet`,`totalpillow`,`shop`,`user`)";
	$values = "('$date','$cutting_id','$alteration','$damage','$palteration','$pdamage','$working_hrs','$totalbedsheet','$totalpillow','$shop','$user')";
	$run = $obj->insert($table, $columns, $values);
	if ($run === true) {
		$arr = $obj->get_last_row($table, "`id`", "`shop`='$shop' AND `user`='$user'");
		$stitching_id = $arr['id'];
		$update = $obj->update("`cutting`", "`status`='2'", "`id`='$cutting_id'");
		$table1 = "`stitching_detail`";
		$columns1 = "(`stitching_id`,`date`,`worker`, `job`,`qty`,`start_time`,`finish_time`)";
		for ($i = 1; $i <= $count; $i++) {
			$worker = $_POST['worker' . $i];
			$job = $_POST['job' . $i];
			$qty = $_POST['qty' . $i];
			$start_time = $_POST['start_time' . $i];
			$finish_time = $_POST['finish_time' . $i];
			$values1 = "('$stitching_id','$date','$worker','$job','$qty','$start_time','$finish_time')";
			$run2 = $obj->insert($table1, $columns1, $values1);
		}
	}
	if ($run === true && $run2 === true) {
		$_SESSION['msg'] = "Successfully Added!";
		header("Location:../stitching?pagename=stitching");
	} else {
		$_SESSION['err'] = $run;
		header("Location:../stitching?pagename=stitching");
	}
} elseif (isset($_POST['save_other_job_process'])) {
	echo "<pre>";
	print_r($_POST);
	die;
	$date = $_POST['date'];
	$cutting_id = $_POST['cutting_id'];
	$alteration = $_POST['alteration'];
	$damage = $_POST['damage'];
	$palteration = $_POST['palteration'];
	$pdamage = $_POST['pdamage'];
	$working_hrs = $_POST['working_hrs'];
	$totalbedsheet = $_POST['totalbedsheet'];
	$totalpillow = $_POST['totalpillow'];
	$shop = $_POST['shop'];
	$user = $_POST['user'];
	$count = $_POST['count'];

	$table = "`stitching`";
	$columns = "(`date`,`cutting_id`, `alteration`,`damage`, `palteration`,`pdamage`,`working_hrs`,`totalbedsheet`,`totalpillow`,`shop`,`user`)";
	$values = "('$date','$cutting_id','$alteration','$damage','$palteration','$pdamage','$working_hrs','$totalbedsheet','$totalpillow','$shop','$user')";
	$run = $obj->insert($table, $columns, $values);
	if ($run === true) {
		$arr = $obj->get_last_row($table, "`id`", "`shop`='$shop' AND `user`='$user'");
		$stitching_id = $arr['id'];
		$update = $obj->update("`cutting`", "`status`='2'", "`id`='$cutting_id'");
		$table1 = "`stitching_detail`";
		$columns1 = "(`stitching_id`,`date`,`worker`, `job`,`qty`,`start_time`,`finish_time`)";
		for ($i = 1; $i <= $count; $i++) {
			$worker = $_POST['worker' . $i];
			$job = $_POST['job' . $i];
			$qty = $_POST['qty' . $i];
			$start_time = $_POST['start_time' . $i];
			$finish_time = $_POST['finish_time' . $i];
			$values1 = "('$stitching_id','$date','$worker','$job','$qty','$start_time','$finish_time')";
			$run2 = $obj->insert($table1, $columns1, $values1);
		}
	}
	if ($run === true && $run2 === true) {
		$_SESSION['msg'] = "Successfully Added!";
		header("Location:../stitching?pagename=stitching");
	} else {
		$_SESSION['err'] = $run;
		header("Location:../stitching?pagename=stitching");
	}
} elseif (isset($_POST['save_zigzag'])) {
	// 	echo "<pre>";print_r($_POST);die;
	$date = $_POST['date'];
	$stitching_id = $_POST['stitching_id'];
	$alteration = $_POST['alteration'];
	$damage = $_POST['damage'];
	$working_hrs = $_POST['working_hrs'];
	$totalpillow = $_POST['totalzigzag'];
	// 	print_r($totalpillow); die;
	$shop = $_POST['shop'];
	$user = $_POST['user'];
	$count = $_POST['count'];

	$table = "`stitching`";
	$col_values = "`totalzigzag`=$totalpillow, `zigzag_alteration`='$alteration', `zigzag_damage`=`damage`+'$damage', `zigzag_date`='$date'";
	$where = "`id`='$stitching_id'";
	$run = $obj->update($table, $col_values, $where);
	// 	print_r($this->db->last_query()); die;
	if ($run === true) {
		$table1 = "`stitching_detail`";
		$columns1 = "(`stitching_id`,`date`,`worker`, `job`,`qty`,`start_time`,`finish_time`)";
		for ($i = 1; $i <= $count; $i++) {
			$worker = $_POST['worker' . $i];
			$job = $_POST['job' . $i];
			$qty = $_POST['qty' . $i];
			$start_time = $_POST['start_time' . $i];
			$finish_time = $_POST['finish_time' . $i];
			$values1 = "('$stitching_id','$date','$worker','$job','$qty','$start_time','$finish_time')";
			$run2 = $obj->insert($table1, $columns1, $values1);
		}
	}
	if ($run === true && $run2 === true) {
		$run1 = $obj->update($table, "`status`='1'", $where);
		$_SESSION['msg'] = "Successfully Added!";
		header("Location:../zigzag?pagename=zigzag");
	} else {
		$_SESSION['err'] = $run;
		header("Location:../zigzag?pagename=zigzag");
	}
} elseif (isset($_POST['finish_stock'])) {
	// echo "<pre>";print_r($_POST);die;
	$count = count($_POST['item']);
	$cutting_id = $_POST['cutting_id'];
	$raw_material = $_POST['raw_material'];
	$width = $_POST['width'];
	$d_no = $_POST['d_no'];
	$image = $_POST['image'];
	$date = $_POST['date'];
	$shop = $_POST['shop'];
	$table = '`finished_stock`';
	$columns = "(`cutting_id`,`image`,`raw_material`,`width`,`d_no`,`item`, `bedsheet_size`,`bedsheet_qty`,`pillow_size`,`pillow_qty`,`date`,`shop`)";
	for ($i = 0; $i < $count; $i++) {
		$item = $_POST['item'][$i];
		$bedsheet_size = $_POST['bedsheet_size'][$i];
		$bedsheet_qty = $_POST['bedsheet_qty'][$i];
		$pillow_size = $_POST['pillow_size'][$i];
		$pillow_qty = $_POST['pillow_qty'][$i];
		$values = "('$cutting_id','$image','$raw_material','$width','$d_no','$item','$bedsheet_size','$bedsheet_qty','$pillow_size','$pillow_qty','$date','$shop')";
		$run = $obj->insert($table, $columns, $values);
	}
	if ($run) {
		$run1 = $obj->update("`stitching`", "`status`='2'", "`cutting_id`='$cutting_id'");
		$_SESSION['msg'] = "Finish Stock Successfully Added!";
		header("Location:../stock_movement?pagename=stock_movement");
	} else {
		$_SESSION['err'] = $run;
		header("Location:../stock_movement?pagename=stock_movement");
	}
} elseif (isset($_POST['save_supplier'])) {
	$name = $_POST['name'];
	$mobile = $_POST['mobile'];
	$date = $_POST['date'];
	$email = $_POST['email'];
	$shop_name = $_POST['shop_name'];
	$pan_no = $_POST['pan_no'];
	$gst = $_POST['gst'];
	$bank = $_POST['bank'];
	$acc_no = $_POST['acc_no'];
	$ifsc = $_POST['ifsc'];
	$state_id = $_POST['state'];
	$district_id = $_POST['district'];
	$pin_code = $_POST['pin_code'];
	$address = $_POST['address'];
	$shop = $_POST['shop'];

	$table = '`supplier`';
	$columns = "(`name`,`mobile`,`email`,`shop_name`,`gst`,`pan`,`bank_name`,`account_no`,`ifsc`,`state`,`district`,`pin_code`,`address`,`date`,`shop`)";
	$values = "('$name','$mobile','$email','$shop_name','$gst','$pan_no','$bank','$acc_no','$ifsc',$state_id,$district_id,$pin_code,'$address','$date',$shop)";
	$run = $obj->insert($table, $columns, $values);
	// print_r($run); die;
	if ($run == 1) {
		$_SESSION['msg'] = "Supplier Added Successfully!";
		header("Location:../masterkey/?pagename=masterkey&supplier");
	} else {
		// $_SESSION['err'] = $run;
		$_SESSION['err'] = "Something Went Wrong";
		header("Location:../masterkey/?pagename=masterkey&supplier");
	}
}

if (isset($_POST['save_excel_data'])) {
	$fileName = $_FILES['import_file']['name'];
	$file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

	// Define allowed file extensions
	$allowed_ext = ['xls', 'csv', 'xlsx'];

	if (in_array($file_ext, $allowed_ext)) {
		$inputFileNamePath = $_FILES['import_file']['tmp_name'];

		try {
			// Load the spreadsheet
			$spreadsheet = IOFactory::load($inputFileNamePath);
			// print_r($spreadsheet); die;
			// Convert the active sheet to an array
			$data = $spreadsheet->getActiveSheet()->toArray();
			$drawings = $spreadsheet->getActiveSheet()->getDrawingCollection();
			// print_r($drawings); die;
			$count = 0;
			$uploadDir = '../uploads/';

			// Ensure the upload directory exists
			// if (!is_dir($uploadDir)) {
			// 	mkdir($uploadDir, 0755, true);
			// }

			$count = 0; // Initialize counter

			foreach ($data as $row) {
				if ($count > 0) { // Skip the header row
					// print_r($row); die;
					// Check if all required fields are present
					$requiredFields = [
						0, // supplier_id
						1, // bill_no
						2, // pur_date
						3, // lot_no 
						4, // bale_no
						6, // raw_id
						7, // width_id
						8, // d_no
						9, // meter
						// 10 // qty
					];

					$allValuesPresent = true;
					foreach ($requiredFields as $index) {
						// print_r($index); 
						if (!isset($row[$index]) || empty($row[$index])) {
							$allValuesPresent = false;
							break;
						}
					}
					// print_r($row); die;
					if ($allValuesPresent) {
						$timestamp = time(); // Get the current timestamp
						$randomNum = rand(0, 999); // Generate a random number
						$jobId = "JOB-$timestamp"; // Create job ID
						$bill_date = date('Y-m-d');
						$supplier_id = $row[0];
						$bill_no = $row[1];
						$pur_date = $row[2];
						$lot_no = $row[3];
						$bale_no = $row[4];
						$raw_id = $row[6];
						$width_id = $row[7];
						$d_no = $row[8];
						// $meter = $row[9];
						$qty = $row[9];
						$shop = $_SESSION['shop'];

						// Initialize image variable
						$imagePath = '';
						$newFileName = '';
						// Loop through drawings to find the corresponding image
						foreach ($drawings as $drawing) {
							if ($drawing instanceof Drawing) {
								$coordinates = $drawing->getCoordinates();

								// Check if the image is in the current row
								if ($coordinates == 'F' . ($count + 1)) { // Adjust for current row
									// print_r($coordinates);
									// Get the path to the image file
									$imagePathFile = $drawing->getPath();
									$imageExtension = pathinfo($imagePathFile, PATHINFO_EXTENSION);
									$newFileName = uniqid('image_', true) . '.' . $imageExtension; // Create a unique file name
									$imagePath = $uploadDir . $newFileName;
									// print_r($imagePath); die;
									// Move the uploaded image to the upload directory
									copy($imagePathFile, $imagePath); // Copy the image to the uploads folder
								}
							}
						}

						// print_r($bale_no);
						// Prepare and insert data
						// $raw_id = '44-confort';
						$filter_supplier = explode('-', $supplier_id);
						$supplier = $filter_supplier[0];
						$filter_raw = explode('-', $raw_id); // Explode the string at the hyphen
						$integer_part = $filter_raw[0]; // The first part will be the integer

						$filter_width = explode('-', $width_id);
						$width = $filter_width[0];

						$unit = $obj->get_details("`raw_material`", '*', "`id`=$integer_part");
						$unit1 = $unit['unit'];
						// print_r($unit); die;
						$table = '`stock`';
						$current_date = date('Y-m-d H:i:s');
						$columns = "(`jobId`,`bill_date`,`supplier_id`,`bill_no`,`pur_date`,`lot_no`,`bale_id`,`date`,`image`,`raw_id`,`width_id`,`d_no`,`quantity`,current_stock,`unit`,`shop`)";
						$values = "('$jobId', '$bill_date', '$supplier', '$bill_no', '$pur_date', '$lot_no', '$bale_no','$current_date', '$newFileName', '$integer_part', '$width', '$d_no',  '$qty','$qty','$unit1', '$shop')";
						$result = $obj->insert($table, $columns, $values);
						// print_r($result); die;
						if ($result) {
							$msg = true; // Set a flag indicating success
						} else {
							// Handle error
							$msg = false;
							echo "Error: " . mysqli_error($con);
						}
					}
				}
				$count++; // Increment count to skip header

			}
			// die;

			// Check if data was inserted
			if (isset($msg)) {
				$_SESSION['message'] = "Successfully Imported";
			} else {
				$_SESSION['message'] = "Not Imported";
			}
		} catch (Exception $e) {
			// Handle any errors during file loading
			$_SESSION['message'] = "Error loading file: " . $e->getMessage();
		}
	} else {
		$_SESSION['message'] = "Invalid File";
	}

	header('Location: ../stock/inward_list.php?pagename=packable&type=entry');
	exit(0);
}

if (isset($_POST['save_balemeter_tally'])) {
	// echo "<pre>";
	// print_r($_POST); die;
	$bale_meter = $_POST['bale_meter_tally'];
	$remark = $_POST['remark'];

	$stock = array();
	foreach ($bale_meter as $key => $bale) {
		$stock_id = $key;

		$stock[] = $key;

		// $generator = new BarcodeGeneratorPNG();
		// 	// print_r($obj); die;
		// 	$stmt = $obj->get_count('bale_meter_tally');
		// 	$count = $stmt + 1;
		// 	$barcode = $count;
		// 	$barcodeText = str_pad($count, 6, '0', STR_PAD_LEFT);
		// 	// print_r($barcodeText); die;
		// 	$barcodeImage = $generator->getBarcode($barcodeText, $generator::TYPE_CODE_128);

		// 	$filePath = 'barcodes/barcode_' . $barcodeText . '.png';

		// 	if (!file_exists('barcodes')) {
		// 		mkdir('barcodes', 0777, true);
		// 	}

		$table = "`bale_meter_tally`";
		$columns = "*";
		$order = "id";
		// $limit = "$offset,$count";
		$stmt = $obj->get_rows($table, $columns, "`stock_id`=$stock_id and `status`=1", $order, '', 'stock_id');

		// If stock_id exists, use the existing barcode from the table
		if ($stmt) {
			// print_r($stmt); die;
			// If stock_id exists, retrieve the barcode associated with it
			$barcodeText = $stmt[0]['barcode']; // Assuming the barcode is stored in the `barcode` column
			$barcode = $barcodeText; // Use the existing barcode
		} else {
			$generator = new BarcodeGeneratorPNG();
			// If stock_id does not exist, generate a new barcode
			$stmt = $obj->get_count('bale_meter_tally'); // Get the current count
			$count = $stmt + 1; // Increment count for new barcode
			$barcodeText = str_pad($count, 6, '0', STR_PAD_LEFT); // Pad the number to ensure it's 6 digits
			$barcode = $barcodeText; // Use the padded count as the barcode

			// Generate the barcode image
			$barcodeImage = $generator->getBarcode($barcodeText, $generator::TYPE_CODE_128);

			// Save the barcode image
			$filePath = 'barcodes/barcode_' . $barcodeText . '.png';
			if (!file_exists('barcodes')) {
				mkdir('barcodes', 0777, true); // Create the barcodes directory if it doesn't exist
			}
			file_put_contents($filePath, $barcodeImage); // Save the barcode image to the server
		}

		// file_put_contents($filePath, $barcodeImage);
		foreach ($bale as $b) {
			if (!empty($b)) {
				$table = '`bale_meter_tally`';
				$columns = "(`stock_id`,`meter_breakup`,`barcode`,`remark`)";
				$values = "($stock_id, '$b', '$barcodeText','$remark')";
				$result = $obj->insert($table, $columns, $values);

				// 		$sql = "SELECT stock_id, COUNT(meter_breakup) AS meter_breakup_count
				// FROM bale_meter_tally
				// GROUP BY stock_id";
				// $result = $obj->query($sql);

				$table = '`bale_meter_tally`';
				$field = 'meter_breakup';  // The field we want to sum
				$where = "`stock_id`=$stock_id";  // Condition to filter by stock_id
				$sum = $obj->get_sum($table, $where, $field);  // Get the sum of meter_breakup for the stock_id

				// Check if the sum is valid, otherwise set it to 0 if NULL
				$sum = $sum ? $sum : 0;  // If no value is returned, default to 0

				// Step 2: Update the current_stock column in the stock table with the sum
				$update_table = '`stock`';
				$columns = "`current_stock`=$sum";  // Set current_stock to the sum value
				$where = "`id`=$stock_id";  // Condition to filter by stock_id

				// Step 3: Execute the update query
				$result = $obj->update($update_table, $columns, $where);
			}
		}
	}
	if ($result) {
		$_SESSION['add_msg'] = "Balemeter Tally Saved Successfully!";
		$_SESSION['stock'] = $stock;
	} else {
		$_SESSION['err'] = "Error Saving Balemeter Tally!";
	}

	// header('Location: ../stock/bale_meter_list.php?pagename=bale_meter_tally&type=list');
	header('Location: ../stock/print_bale_meter.php');
	// die;
}

if (isset($_POST['save_cuttingsheet_withstock'])) {
	// Initializing order details
	// echo "<pre>";
	// print_r($_POST);
	// die;
	$order_id = 'ORD' . time() . rand(1000, 9999);
	$job_sqnc = '';

	// Combine job sequence numbers into a string and then split them back into an array
	foreach ($_POST['job_sqn'] as $job_sqn) {
		$job_sqnc = $job_sqn . ',';  // Combine with commas
	}
	// print_r($_POST['job_sqn']); die;
	$job_sqnc = rtrim($job_sqnc, ',');
	$job_sqn = explode(',', $job_sqnc);
	// print_r($job_sqn[0]);die;
	$worker_id_array = array($_POST['worker_id']);
	$sqn_id_array = array($_POST['cutting_id']);
	$total_quant = 0;
	// print_r($_POST['consumption2']); die;
	foreach ($_POST['consumption2'] as $consumption) {
		foreach ($consumption as $cons) {
			if (!empty($cons)) {
				$total_quant = intval($total_quant) + intval($cons);
			}
		}
	}
	$order = array(
		'order_id' => $order_id,
		'date' => $_POST['date'],
		'stock_id' => $_POST['stock_id1'],
		'quantity' => $_POST['total_quant'],
		'order_quantity' => $total_quant,
		'assign_id' => json_encode($worker_id_array),
		'total_consumption' => $_POST['consumption'],
		'sqn_id' => json_encode($sqn_id_array)
	);
	$worker = $_POST['worker_id'];

	// Insert into `sm_order`
	$table = '`sm_order`';
	$columns = "`" . implode('`, `', array_keys($order)) . "`";
	$values = "'" . implode("', '", array_values($order)) . "'";
	$result = $obj->insert($table, '(' . $columns . ')', '(' . $values . ')');

	// Get the last inserted order ID
	$get_last_order = $obj->get_last_row("`sm_order`", "`id`");
	foreach ($_POST['raw_id'] as $key => $raw) {
		// echo "<pre>";
		// $job_sqn = $_POST['job_sqn'][$raw];
		// print_r($_POST['job_sqn']); die;
		// $job_sqn_array = explode(',', $job_sqn);
		// print_r($_POST['job']); die;
		$job = $_POST['job'][$raw];

		foreach ($_POST['product'] as $key1 => $prod) {
			$product_array = [];
			$size_array = [];
			$pattern_array = [];
			$meterbreakup_array = [];
			$remark_array = '';
			if ($key1 == $raw) {
				foreach ($prod as $key2 => $prd2) {
					// print_r(); die;
					foreach ($prd2 as $key3 => $prd) {
						if (!empty($prd) && !empty($_POST['size'][$raw][$key2]) && !empty($_POST['pattern'][$raw][$key2])) {
							$job_sqn = $obj->get_rows("`product`", "*", "`id`=$prd");
							// print_r($job_sqn); die;
							$job_sqn = $job_sqn[0]['job_squence'];
							$job_sqn2 = json_decode($job_sqn);
							$job_index = 0;
							$prev_job = 0;
							foreach ($job_sqn2 as $key => $j2) {
								if (!empty($job_sqn2[$key + 1])) {
									if ($job_sqn2[$key] == $job) {
										$job_index = $job_sqn2[$key + 1];
										$prev_job = $job_sqn2[$key];
									}
								}
							}
							// print_r($job_index); die;
							$product_array = $prd;
							$size_array = $_POST['size'][$raw][$key2][$key3];
							$pattern_array = $_POST['pattern'][$raw][$key2][$key3];
							// $meterbreakup_array = $_POST['meter_breakup'][$raw][$key2];
							if (isset($_POST['button_type']) && $_POST['button_type'] == "add_total") {
								$meterbreakup_array = 0;
							} else {
								$meterbreakup_array = $key2;
							}
							$consumption_array = $_POST['consumption2'][$raw][$key2][$key3];
							if (!empty($_POST['remark'][$raw][$key2][$key3])) {
								$remark_array = $_POST['remark'][$raw][$key2][$key3];
							}
							$order_prod = array(
								'product_id' => $product_array,
								'product_quant' => $consumption_array,
								'job_squence' => $job_sqn,
								'curr_job' => $job_index,
								'meter_breakup' => $meterbreakup_array,
								'size_id' => $size_array,
								'pattern_id' => $pattern_array,
								'raw_id' => $raw,
								'order_id' => $get_last_order['id'],
								'remark' => $remark_array,
								'width_id' => $_POST['width_id1']
							);
							// print_r($order_prod);
							$table = '`order_product`';
							$columns = "`" . implode('`, `', array_keys($order_prod)) . "`";
							$values = "'" . implode("', '", array_values($order_prod)) . "'";
							$result = $obj->insert($table, '(' . $columns . ')', '(' . $values . ')');
							$get_last_order_prod = $obj->get_last_row("`order_product`", "`id`");
							$last_order_id = $get_last_order['id'];
							$last_prod_ord_id = $get_last_order_prod['id'];
							$total_consumption = $_POST['consumption'];

							$table = '`order_assign_history`';
							$columns = "(`assign_id`,`order_id`,`order_prod_id`,`process_id`,`quant`)";
							$values = "('$worker', '$last_order_id','$last_prod_ord_id', '$prev_job', '$total_consumption')";
							$result12 = $obj->insert($table, $columns, $values);

							$query2 = "`updated_at`='" . date('Y-m-d h:i:s') . "'";
							$obj->update("order_product", $query2, "`id`=$order_prod_id[$key]");
						}
					}
				}
				// die;
				$balance = $_POST['balanace'];
				$stock_id = $_POST['stock_id1'];
				$obj->update("`stock`", "`current_stock`=$balance", "`id`=$stock_id");

				// Convert arrays to JSON for storing in database
				// $product_array = json_encode($product_array);
				// $size_array = json_encode($size_array);
				// $pattern_array = json_encode($pattern_array);
				// $meterbreakup_array = json_encode($meterbreakup_array);
				// $consumption_array= json_encode($consumption_array);
				// $remark_array = !empty($remark_array) ? json_encode($remark_array) : 'null';
				// print_r($consumption_array); die;
				// Prepare data for the `order_product` table


				// Get the last inserted order product ID

				// $subsidary_name = [];
				// $subsidary_consumption = [];
				// $subsidary_unit = [];
				// $subsidary_product = [];
				// $subsidary_size = [];
				// $subsidary_width = [];
				// $subsidary_rate = [];
				// $subsidary_pattern = [];
				// $subsidary_prodid = [];
				// $subsidary_sizeid = [];
				// $subsidary_item = [];
				// if (isset($_POST['subsidary_name'][$raw])) {
				// 	foreach ($_POST['subsidary_name'][$raw] as $key5=>$subsidary) {
				// 		$subsidary_name[] = $subsidary;
				// 		$subsidary_consumption[] = $_POST['subsidary_consumption'][$raw][$key5];
				// 		$subsidary_unit[]= $_POST['subsidary_unit'][$raw][$key5];
				// 		$subsidary_product[]= $_POST['subsidary_product'][$raw][$key5];
				// 		$subsidary_size[]= $_POST['subsidary_size'][$raw][$key5];
				// 		$subsidary_width[]= $_POST['subsidary_width'][$raw][$key5];
				// 		$subsidary_rate[]= $_POST['subsidary_rate'][$raw][$key5];
				// 		$subsidary_pattern[]= $_POST['subsidary_pattern'][$raw][$key5];
				// 		$subsidary_prodid[]= $_POST['subsidary_prodid'][$raw][$key5];
				// 		$subsidary_sizeid[]= $_POST['subsidary_sizeid'][$raw][$key5];
				// 	}

				// 	$subsidary_item[] = array(
				// 		'order_id' => $get_last_order['id'],
				// 		'order_prod_id' => $get_last_order_prod['id'],
				// 		'product_name'=>json_encode($subsidary_name),
				// 		'product_id'=>json_encode($subsidary_prodid),
				// 		'size'=>json_encode($subsidary_size),
				// 		'size_id' => json_encode($subsidary_sizeid),
				// 		'consumption'=> json_encode($subsidary_consumption),
				// 		'width_id'=> json_encode($subsidary_width),
				// 		'pattern'=> json_encode($subsidary_pattern),
				// 		'rate'=> json_encode($subsidary_rate)
				// 		// 'subsidary_name' => $subsidary,
				// 	);
				// 	print_r($subsidary_item);

				// 	$table = '`order_subsidary`';
				// 	$columns = "`" . implode('`, `', array_keys($subsidary_item[0])) . "`";
				// 	// print_r($columns);
				// 	$values = "'" . implode("', '", array_values($subsidary_item[0])) . "'";
				// 	// print_r($values);
				// 	$result = $obj->insert($table, '(' . $columns . ')', '(' . $values . ')');
				// }
			}
		}
	}
	if ($result) {
		$_SESSION['msg'] = "Cutting Sheet Assigned!";
	} else {
		$_SESSION['err'] = "Error In cutting Sheet!";
	}
	header('Location: ../cutting?pagename=cutting-sheet');
}
if (isset($_POST['save_cuttingsheet_check_settled'])) {
	// Initializing order details
	// echo "<pre>";
	// print_r($_POST);
	// die;
	$order_id = 'ORD' . time() . rand(1000, 9999);
	$job_sqnc = '';

	// Combine job sequence numbers into a string and then split them back into an array
	foreach ($_POST['job_sqn'] as $job_sqn) {
		$job_sqnc = $job_sqn . ',';  // Combine with commas
	}
	// print_r($_POST['job_sqn']); die;
	$job_sqnc = rtrim($job_sqnc, ',');
	$job_sqn = explode(',', $job_sqnc);
	// print_r($job_sqn[0]);die;
	$worker_id_array = array($_POST['worker_id']);
	$sqn_id_array = array($_POST['cutting_id']);
	$total_quant = 0;
	// print_r($_POST['consumption2']); die;
	foreach ($_POST['consumption2'] as $consumption) {
		foreach ($consumption as $cons) {
			if (!empty($cons)) {
				$total_quant = intval($total_quant) + intval($cons);
			}
		}
	}
	$order = array(
		'order_id' => $order_id,
		'date' => $_POST['date'],
		'stock_id' => $_POST['stock_id1'],
		'quantity' => $_POST['total_quant'],
		'order_quantity' => $total_quant,
		'assign_id' => json_encode($worker_id_array),
		'total_consumption' => $_POST['consumption'],
		'sqn_id' => json_encode($sqn_id_array)
	);

	// Insert into `sm_order`
	$table = '`sm_order`';
	$columns = "`" . implode('`, `', array_keys($order)) . "`";
	$values = "'" . implode("', '", array_values($order)) . "'";
	$result = $obj->insert($table, '(' . $columns . ')', '(' . $values . ')');

	// Get the last inserted order ID
	$get_last_order = $obj->get_last_row("`sm_order`", "`id`");
	foreach ($_POST['raw_id'] as $key => $raw) {
		// echo "<pre>";
		// $job_sqn = $_POST['job_sqn'][$raw];
		// print_r($_POST['job_sqn']); die;
		// $job_sqn_array = explode(',', $job_sqn);
		$job = $_POST['job'][$raw];

		foreach ($_POST['product'] as $key1 => $prod) {
			$product_array = [];
			$size_array = [];
			$pattern_array = [];
			$meterbreakup_array = [];
			$remark_array = '';
			if ($key1 == $raw) {
				foreach ($prod as $key2 => $prd2) {
					// print_r(); die;
					foreach ($prd2 as $key3 => $prd) {
						if (!empty($prd) && !empty($_POST['size'][$raw][$key2]) && !empty($_POST['pattern'][$raw][$key2])) {
							$job_sqn = $obj->get_rows("`product`", "*", "`id`=$prd");
							// print_r($job_sqn); die;
							$job_sqn = $job_sqn[0]['job_squence'];
							$product_array = $prd;
							$size_array = $_POST['size'][$raw][$key2][$key3];
							$pattern_array = $_POST['pattern'][$raw][$key2][$key3];

							// $job_sqn = $job_sqn[0]['job_squence'];
							$job_sqn2 = json_decode($job_sqn);
							$job_index = 0;
							$prev_job = 0;
							foreach ($job_sqn2 as $key => $j2) {
								if (!empty($job_sqn2[$key + 1])) {
									if ($job_sqn2[$key] == $job) {
										$job_index = $job_sqn2[$key + 1];
										$prev_job = $job_sqn2[$key];
									}
								}
							}

							// $meterbreakup_array = $_POST['meter_breakup'][$raw][$key2];
							if (isset($_POST['button_type']) && $_POST['button_type'] == "add_total") {
								$meterbreakup_array = 0;
							} else {
								$meterbreakup_array = $key2;
							}
							$consumption_array = $_POST['consumption2'][$raw][$key2][$key3];
							if (!empty($_POST['remark'][$raw][$key2][$key3])) {
								$remark_array = $_POST['remark'][$raw][$key2][$key3];
							}
							$order_prod = array(
								'product_id' => $product_array,
								'product_quant' => $consumption_array,
								'job_squence' => $job_sqn,
								'curr_job' => $job_index,
								'meter_breakup' => $meterbreakup_array,
								'size_id' => $size_array,
								'pattern_id' => $pattern_array,
								'raw_id' => $raw,
								'order_id' => $get_last_order['id'],
								'remark' => $remark_array,
								'width_id' => $_POST['width_id1']
							);
							// print_r($order_prod);
							$table = '`order_product`';
							$columns = "`" . implode('`, `', array_keys($order_prod)) . "`";
							$values = "'" . implode("', '", array_values($order_prod)) . "'";
							$result = $obj->insert($table, '(' . $columns . ')', '(' . $values . ')');
							$get_last_order_prod = $obj->get_last_row("`order_product`", "`id`");


							$last_order_id = $get_last_order['id'];
							$last_prod_ord_id = $get_last_order_prod['id'];
							$total_consumption = $_POST['consumption'];
							$table = '`order_assign_history`';
							$columns = "(`assign_id`,`order_id`,`order_prod_id`,`process_id`,`quant`)";
							$values = "('$worker', '$last_order_id','$last_prod_ord_id', '$prev_job', '$total_consumption')";
							$result12 = $obj->insert($table, $columns, $values);
						}
					}
				}
				// die;
				$balance = $_POST['balanace'];
				$stock_id = $_POST['stock_id1'];
				$obj->update("`stock`", "`current_stock`=0", "`id`=$stock_id");

				$settled_stock = array(
					'stock_id' => $stock_id,
					'quant' => $balance,
				);

				$table = '`settled_stock`';
				$columns = "`" . implode('`, `', array_keys($settled_stock)) . "`";
				$values = "'" . implode("', '", array_values($settled_stock)) . "'";
				$result = $obj->insert($table, '(' . $columns . ')', '(' . $values . ')');
			}
		}
	}
	if ($result) {
		$_SESSION['msg'] = "Cutting Sheet Assigned!";
	} else {
		$_SESSION['err'] = "Error In cutting Sheet!";
	}
	header('Location: ../cutting?pagename=cutting-sheet');
} else if (isset($_GET['pagename']) && $_GET['pagename'] == 'checkout') {
	$id = $_GET['id'];
	$run = $obj->update("`attendance`", "`status`=2", "`id`=$id");
	header("Location:../punchattendance/?pagename=punch");
	// print_r($id);
} else if (isset($_POST['save_process'])) {
	// print_r("hii");
	// echo "<pre>";
	// print_r($_POST); die;	
	$id = $_POST['order'];
	$assign_id = $_POST['worker_id'];
	$alloted_quantity2 = $_POST['alloted_quantity'];
	$curr_job = array();
	$order_prod_id = array();
	$order_id = array();
	$alloted_quantity = array();

	foreach ($alloted_quantity2 as $key => $quant) {
		if (!empty($quant)) {
			$curr_job[] = $_POST['curr_job'][$key];
			$order_id[] = $_POST['order_id'][$key];
			$order_prod_id[] = $_POST['order_prod_id'][$key];
			$alloted_quantity[] = $quant;
		}
	}
	// print_r($order_id); die;
	$status = 2;
	$process_name = $_POST['process_name'];
	// $ord_id = $_POST['order_id'];  
	foreach ($order_id as $key => $order) {
		// $curr_job = $_POST['curr_job'][$key];
		// $order_prod_id = $_POST['order_prod_id'][$key];
		// $alloted_quantity = $_POST['alloted_quantity'][$key];
		$worker_id = $_POST['worker_id'][$key];
		if (!empty($alloted_quantity)) {

			$obj->update("`order_product`", '`order_status`=2', "`id`=" . $order_prod_id[$key]);

			$table = '`order_assign_history`';
			$columns = "(`assign_id`,`order_id`,`order_prod_id`,`process_id`,`quant`)";
			$values = "('$worker_id', '$order','$order_prod_id[$key]', '$curr_job[$key]', '$alloted_quantity[$key]')";
			// print_r($values);
			$result12 = $obj->insert($table, $columns, $values);
			$query2 = "`updated_at`='" . date('Y-m-d h:i:s') . "'";
			// print_r($query2); die;
			$obj->update("order_product", $query2, "`id`=$order_prod_id[$key]");
		}
	}
	// die;

	foreach ($id as $key => $order_id) {

		$order = $obj->get_rows("sm_order", "*", "`id`=$order_id");

		$assign = json_decode($order[0]['assign_id']);

		$assign[] = $assign_id[$key];

		$assign = json_encode($assign);

		$query = "`order_status` = $status, `assign_id` = '$assign'";  

		$run = $obj->update("sm_order", $query, "`id` = $order_id");
	}

	if ($run) {
		$_SESSION['msg'] = "Order Status Updated!";
	} else {
		$_SESSION['err'] = "Error in updating Order Status!";
	}

	header('Location: ../job_process/?pagename=' . $process_name);
} elseif (isset($_POST['save_payment_worker'])) {
	$worker = $_POST['worker'];
	$rate = $_POST['rate'];
	$payment_type = $_POST['payment_type'];
	$shop = $_POST['shop'];

	$table = '`payment`';
	$columns = "(`worker`,`work_type`,`rate`,`shop`)";
	$values = "('$worker', '$payment_type', '$rate',$shop)";
	$result = $obj->insert($table, $columns, $values);

	if ($result) {
		$_SESSION['msg'] = "Payment Saved Successfully!";
	} else {
		$_SESSION['err'] = "Error Saving Payment!";
	}
	header('Location: ../masterkey/?pagename=masterkey&ppage');
} elseif (isset($_POST['save_payment_product'])) {
	$product = $_POST['product'];
	$size_id = $_POST['size_id'];
	$pattern_id = $_POST['pattern_id'];
	$payment_type = $_POST['payment_type'];
	$rate = $_POST['rate'];
	$shop = $_POST['shop'];

	$table = '`payment`';
	$columns = "(`product_id`,`work_type`,`size_id`,`pattern_id`,`rate`,`shop`)";
	$values = "('$product', '$payment_type','$size_id', $pattern_id,'$rate',$shop)";
	$result = $obj->insert($table, $columns, $values);

	if ($result) {
		$_SESSION['msg'] = "Payment Saved Successfully!";
	} else {
		$_SESSION['err'] = "Error Saving Payment!";
	}
	header('Location: ../masterkey/?pagename=masterkey&ppage');
}
ob_end_flush();
