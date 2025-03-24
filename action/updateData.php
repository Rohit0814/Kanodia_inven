<?php
// session_start();
if(!isset($_SESSION)){
    session_start();
	ob_start();
}
include("config.php");
$obj = new database();

if (!function_exists('Imageupload')) {
	function Imageupload($dir, $inputname, $allext, $pass_width, $pass_height, $pass_size, $newname)
	{
		$error = "";
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
		} else {
			//print_r($_FILES);
			$error .= "Please Select an Image!!!";
			// $error;
		}
		return $error;
	}
}
if (isset($_POST['up_user'])) {
	$id = $_POST['uid'];
	$username = strip_tags($_POST['up_username']);
	$password = strip_tags($_POST['up_password']);
	$role = strip_tags($_POST['up_role']);
	$shop = strip_tags($_POST['up_shop']);
	$active = strip_tags($_POST['up_active']);
	$col_values = "`username`='$username', `role`='$role', `shop`='$shop', `active`='$active'";
	if ($password != '') {
		$col_values .= ", `password`='$password'";
	}
	$where = "`id`='$id'";
	$run = $obj->update("`users`", $col_values, $where);
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Updated!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../admin?pagename=admin");
} elseif (isset($_POST['update_worker'])) {
	$id = $_POST['id'];
	$designation = strip_tags($_POST['designation']);
	$name = strip_tags($_POST['name']);
	$mobile = strip_tags($_POST['mobile']);
	$address = strip_tags($_POST['address']);
	$aadhar = strip_tags($_POST['aadhar']);
	$pan = strip_tags($_POST['pan']);
	$gst = strip_tags($_POST['gst']);
	$bank = strip_tags($_POST['bank']);
	$account = strip_tags($_POST['account']);
	$ifsc = strip_tags($_POST['ifsc']);
	$payment_type = strip_tags($_POST['payment_type']);
	$payment = strip_tags($_POST['payment']);
	$reference = strip_tags($_POST['reference']);
	$col_values = "`designation`='$designation',`name`='$name', `mobile`='$mobile', `address`='$address', `aadhar`='$aadhar', `pan`='$pan', `gst`='$gst',`bank`='$bank', `account`='$account', `ifsc`='$ifsc', `reference`='$reference', `payment_type`='$payment_type', `payment`='$payment'";
	$where = "`id`='$id'";
	$run = $obj->update("`worker`", $col_values, $where);
	if ($run === true) {
		if (isset($_POST['username'])) {
			$username = $_POST['username'];
			$password = $_POST['password'];
			$shop = $_SESSION['shop'];
			$users = $obj->get_details('users', "*", "`user_id`='$id'");
			if (!empty($users)) {
				$obj->update("`users`", "`username`='$username',`password`='$password'", "`user_id`='$id'");
			} else {
				$col = "(`user_id`,`username`,`password`,`role`,`shop`,`active`)";
				$val = "('$id','$username','$password','$designation','$shop','1')";
				$run = $obj->insert("users", $col, $val);
			}
		}
		$_SESSION['msg'] = "Successfully Updated!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey");
} elseif (isset($_POST['update_raw'])) {
	$id = $_POST['id'];
	$name = strip_tags($_POST['name']);
	$unit = strip_tags($_POST['unit']);
	$rate = strip_tags($_POST['rate']);
	$type = strip_tags($_POST['type']);
	$col_values = "`name`='$name', `unit`='$unit', `rate`='$rate',`type`='$type'";
	$where = "`id`='$id'";
	$run = $obj->update("`raw_material`", $col_values, $where);
	// print_r($run); die;    
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Updated!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&rpage");
} elseif (isset($_POST['update_pattern'])) {
	$id = $_POST['id'];
	$item = strip_tags($_POST['item']);
	$pattern = strip_tags($_POST['pattern']);
	$col_values = "`prod_id`='$item', `pattern_name`='$pattern'";
	$where = "`id`='$id'";
	$run = $obj->update("`pattern`", $col_values, $where);
	// print_r($run); die;    
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Updated!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&patternpage");
} elseif (isset($_POST['payattendance'])) {
	$count = count($_POST['attendance_id']);
	$attendance_id = $_POST['attendance_id'];
	$col_values = "`paid`='1'";
	for ($i = 0; $i < $count; $i++) {
		$where = "`id`='$attendance_id[$i]'";
		$run = $obj->update("`attendance`", $col_values, $where);
	}
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Paid!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../reports/payment_report.php?pagename=paymentreportge");
} elseif (isset($_POST['paycutting'])) {
	$count = count($_POST['cutting_id']);
	$cutting_id = $_POST['cutting_id'];
	$col_values = "`paid`='1'";
	for ($i = 0; $i < $count; $i++) {
		$where = "`id`='$cutting_id[$i]'";
		$run = $obj->update("`cutting`", $col_values, $where);
	}
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Paid!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../reports/payment_report.php?pagename=paymentreportge");
} elseif (isset($_POST['paystitching'])) {
	$count = count($_POST['stitching_id']);
	$stitching_id = $_POST['stitching_id'];
	$col_values = "`paid`='1'";
	for ($i = 0; $i < $count; $i++) {
		$where = "`id`='$stitching_id[$i]'";
		$run = $obj->update("`stitching_detail`", $col_values, $where);
	}
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Paid!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../reports/payment_report.php?pagename=paymentreportge");
} elseif (isset($_POST['payzigzag'])) {
	$count = count($_POST['zigzag_id']);
	$zigzag_id = $_POST['zigzag_id'];
	$col_values = "`paid`='1'";
	for ($i = 0; $i < $count; $i++) {
		$where = "`id`='$zigzag_id[$i]'";
		$run = $obj->update("`stitching_detail`", $col_values, $where);
	}
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Paid!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../reports/payment_report.php?pagename=paymentreportge");
} elseif (isset($_POST['update_width'])) {
	$id = $_POST['id'];
	$width = strip_tags($_POST['width']);
	$unit = strip_tags($_POST['unit']);
	$shop = strip_tags($_POST['shop']);
	$col_values = "`width`='$width', `unit`='$unit', `shop`='$shop'";
	$where = "`id`='$id'";
	$run = $obj->update("`width`", $col_values, $where);
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Updated!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&wipage");
} elseif (isset($_POST['update_payment'])) {
	$id = $_POST['id'];
	$worker = strip_tags($_POST['worker']);
	$bedsheet_rate = strip_tags($_POST['bedsheet_rate']);
	$pillow_rate = strip_tags($_POST['pillow_rate']);
	$work_type = strip_tags($_POST['work_type']);
	$shop = strip_tags($_POST['shop']);
	$col_values = "`worker`='$worker', `work_type`='$work_type', `bedsheet_rate`='$bedsheet_rate', `pillow_rate`='$pillow_rate',  `shop`='$shop'";
	$where = "`id`='$id'";
	$run = $obj->update("`payment`", $col_values, $where);
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Updated!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&ppage");
} elseif (isset($_POST['update_attendance'])) {
	$id = $_POST['id'];
	$date = strip_tags($_POST['date']);
	$worker = strip_tags($_POST['worker']);
	$amount = strip_tags($_POST['amount']);
	$remark = strip_tags($_POST['remark']);
	$shop = strip_tags($_POST['shop']);
	$col_values = "`worker`='$worker', `date`='$date', `amount`='$amount', `remark`='$remark', `shop`='$shop'";
	$where = "`id`='$id'";
	$run = $obj->update("`attendance`", $col_values, $where);
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Updated!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&apage");
} elseif (isset($_POST['update_size'])) {
	//echo "<pre>";print_r($_POST);die;
	$id = $_POST['sid'];
	$item = strip_tags($_POST['item']);
	$size = strip_tags($_POST['size']);
	$shop = strip_tags($_POST['shop']);
	$widths = $_POST['width'];
	$consume = $_POST['consume'];
	$col_values = "`item`='$item', `size`='$size', `shop`='$shop'";
	$where = "`id`='$id'";
	$run = $obj->update("`size`", $col_values, $where);
	if ($run === true) {
		$getid = $obj->get_last_row("`size`", "`id`", "`size`='$size' and `shop`='$shop'");
		$size_id = $getid['id'];
		$obj->delete("`consumption`", "`shop`='$shop' AND `size_id`='$size_id'");
		$columns2 = "(`size_id`, `width_id`, `consume`, `shop`)";
		foreach ($widths as $key => $width) {
			if ($width == '') {
				continue;
			}
			$values2 = "('$size_id','$width','$consume[$key]','$shop')";
			$run2 = $obj->insert("`consumption`", $columns2, $values2);
		}
		$_SESSION['msg'] = "Successfully Updated !";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&spage");
} elseif (isset($_POST['update_finished'])) {
	// echo "<pre>";print_r($_POST);die;
	$id = $_POST['id'];
	$finished_product = strip_tags($_POST['finished_product']);
	$bedsheet_size = strip_tags($_POST['bedsheet_size']);
	$bedsheet_qty = strip_tags($_POST['bedsheet_qty']);
	$pillow_size = strip_tags($_POST['pillow_size']);
	$pillow_qty = strip_tags($_POST['pillow_qty']);
	$shop = strip_tags($_POST['shop']);
	$col_values = "`finished_product`='$finished_product', `bedsheet_size`='$bedsheet_size',`bedsheet_qty`='$bedsheet_qty',`pillow_size`='$pillow_size',`pillow_qty`='$pillow_qty', `shop`='$shop'";
	$where = "`id`='$id'";
	$run = $obj->update("`finished`", $col_values, $where);
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Updated !";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&fpage");
} elseif (isset($_POST['update_stock'])) {
	$id = strip_tags($_POST['id']);
	// $date=strip_tags($_POST['date']);
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
		$check = Imageupload($dir, 'image', $allext, "3000", "3000", '100000000', $image);
		$image = $image . ".jpg";
		$col_values = "`raw_id`='$raw_id', `d_no`='$d_no', `width_id`='$width_id',`meter`='$meter', `quantity`='$quantity', `image`='$image'";
	} else {
		$check = true;
		$col_values = "`raw_id`='$raw_id', `d_no`='$d_no', `width_id`='$width_id',`meter`='$meter',  `quantity`='$quantity'";
	}
	if ($check === true) {
		$where = "`id`='$id' AND `shop`='$shop'";
		$run = $obj->update("`stock`", $col_values, $where);

		if ($run === true) {
			$_SESSION['msg'] = "Successfully Updated !";
		} else {
			$_SESSION['err'] = $run;
		}
		header("Location:../stock?pagename=stocklist");
	} else {
		$_SESSION['err'] = $check;
		header("Location:../stock?pagename=stocklist");
	}
	header("Location:../stock?pagename=stocklist");
} elseif (isset($_POST['update_designation'])) {
	$id = strip_tags($_POST['id']);
	$designation = strip_tags($_POST['designation']);
	$shop = strip_tags($_POST['shop']);
	$col_values = "`designation`='$designation'";
	$where = "`id`='$id' AND `shop`='$shop'";
	$run = $obj->update("`designation`", $col_values, $where);
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Updated!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&dpage");
} elseif (isset($_POST['update_product'])) {
	$id = strip_tags($_POST['id']);
	$product = strip_tags($_POST['product']);
	$job_sqn = strip_tags(json_encode($_POST['job_process']));
	// $job_sqn = json_encode($job_sqn);
	// print_r($job_sqn); die;
	$col_values = "`product_name`='$product', `job_squence`='$job_sqn'";
	$where = "`id`='$id'";
	$run = $obj->update("`product`", $col_values, $where);
	// print_r($run); die;
	if ($run === true) {
		$_SESSION['msg'] = "Successfully Updated!";
	} else {
		$_SESSION['err'] = $run;
	}
	header("Location:../masterkey/?pagename=masterkey&productpage");
} elseif (isset($_POST['update_payment_worker'])) {
	$id = $_POST['payment_Work_id'];
	// print_r($id); die;
	$worker = $_POST['worker'];
	$rate = $_POST['rate'];
	$payment_type = 'worker_wise';
	$shop = $_POST['shop'];

	$query = "`worker` = '$worker', `rate` = '$rate', `work_type` = '$payment_type', `shop` = $shop";
	$result = $obj->update("payment", $query, "`id` = $id");

	if ($result) {
		$_SESSION['msg'] = "Payment Updated Successfully!";
	} else {
		$_SESSION['err'] = "Error Updating Payment!";
	}
	header('Location:../masterkey/?pagename=masterkey&ppage');
} elseif (isset($_POST['update_payment_product'])) {
	$id = $_POST['payment_product_id'];
	// print_r($id); die;
	$product = $_POST['product'];
	$size_id = $_POST['size_id'];
	$pattern_id = $_POST['pattern_id'];
	$rate = $_POST['rate'];
	$payment_type = 'product_wise';
	$shop = $_POST['shop'];

	$query = "`product_id` = '$product', `size_id` = '$size_id', `pattern_id` = $pattern_id, `rate` = '$rate', `work_type` = '$payment_type', `shop` = $shop";
	$result = $obj->update("payment", $query, "`id` = $id");

	if ($result) {
		$_SESSION['msg'] = "Payment Updated Successfully!";
	} else {
		$_SESSION['err'] = "Error Updating Payment!";
	}
	header('Location:../masterkey/?pagename=masterkey&ppage');
} elseif (isset($_POST['update_supplier'])) {
	$id = $_POST['id'];
	// print_r($id); die;
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

	$query = "`name` = '$name', `mobile` = '$mobile', `email` = '$email', `shop_name` = '$shop_name', `gst` = '$gst', `pan` = '$pan_no', `bank_name` = '$bank', `account_no`='$acc_no', `ifsc`='$ifsc',`state`='$state_id', `district`='$district_id', `pin_code`='$pin_code', `address`='$address'";
	$result = $obj->update("supplier", $query, "`id` = $id");

	if ($result) {
		$_SESSION['msg'] = "Supplier  Updated Successfully!";
	} else {
		$_SESSION['err'] = "Error Updating Payment!";
	}
	header('Location:../masterkey/?pagename=masterkey&supplier');
} elseif (isset($_POST['update_jobprocess'])) {
	$id = $_POST['id'];
	$process = strip_tags($_POST['process_name']);
	$permission = json_encode($_POST['permission']);
	$lower_process = strtolower($process);

	$formate_process = preg_replace('/[^a-z0-9]+/', '-', $lower_process);

	$slug = trim($formate_process, '-');

	$query = "`process` = '$process', `slug` = '$slug', `permission` = '$permission'";
	$result = $obj->update("job_process", $query, "`id` = $id");

	if ($result) {
		$_SESSION['msg'] = "Job Process Updated Successfully!";
	} else {
		$_SESSION['err'] = "Error Updating Payment!";
	}
	header('Location:../masterkey/?pagename=masterkey&jprocess');
}
ob_end_flush(); // Flush the output buffer
