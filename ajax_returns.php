<?php

include("action/config.php");
session_start();
$obj = new database();
if (isset($_POST['editUser'])) {
	$id = $_POST['id'];
	$array = $obj->get_details("`users`", "*", "`id`='$id'");
	echo json_encode($array);
} elseif (isset($_POST['editWorker'])) {
	$id = $_POST['id'];
	$table = "`worker` t1, `users` t2";
	$columns = "t1.*,t2.`username`,t2.`password`";
	$where = "t1.`id`='$id' AND t2.`user_id`='$id'";
	$array = $obj->get_details($table, $columns, $where);
	// print_r($array);die;
	echo json_encode($array);
} elseif (isset($_POST['editRaw'])) {
	$id = $_POST['id'];
	$array = $obj->get_details("`raw_material`", "*", "`id`='$id'");
	echo json_encode($array);
} elseif (isset($_POST['editWidth'])) {
	$id = $_POST['id'];
	$array = $obj->get_details("`width`", "*", "`id`='$id'");
	echo json_encode($array);
} elseif (isset($_POST['editPayment'])) {
	$id = $_POST['id'];
	$array = $obj->get_details("`payment`", "*", "`id`='$id'");
	echo json_encode($array);
} elseif (isset($_POST['editPaymentWorker'])) {
	$id = $_POST['id'];
	$array = $obj->get_details("`payment`", "*", "`id`='$id'");
	$worker_id = $array['worker'];
	$array['worker'] = $obj->get_details("`worker`", "*", "`id`='$worker_id'");
	echo json_encode($array);
} elseif (isset($_POST['editAttendance'])) {
	$id = $_POST['id'];
	$array = $obj->get_details("`attendance`", "*", "`id`='$id'");
	echo json_encode($array);
} elseif (isset($_POST['editSize'])) {
	$id = $_POST['id'];
	$array = $obj->get_details("`size`", "*", "`id`='$id'");
	$consume = $obj->get_rows("`consumption`", "*", "`size_id`='$id'");
	$subsidary_item = $obj->get_rows("`raw_material`", "*", "`type`='subsidiary'");
	if (is_array($consume)) {
		$csize = sizeof($consume);
	} else {
		$csize = 0;
	}

	// Group consumption based on size_id, pattern, and width_id
	$grouped_consumption = [];

	foreach ($consume as $consumption) {
		$size_id = $consumption['size_id'];      // Get size_id
		$pattern = $consumption['pattern'];      // Get pattern
		$width_id = $consumption['width_id'];   // Get width_id

		// Initialize the nested array if it doesn't exist
		// if (!isset($grouped_consumption[$size_id])) {
		// 	$grouped_consumption[$size_id] = [];
		// }

		// if (!isset($grouped_consumption[$size_id][$pattern])) {
		// 	$grouped_consumption[$size_id][$pattern] = [];
		// }

		// if (!isset($grouped_consumption[$size_id][$pattern][$width_id])) {
		// 	$grouped_consumption[$size_id][$pattern][$width_id] = [];
		// }

		// Add the consumption data to the correct group
		$grouped_consumption[$size_id . '_' . $pattern . '_' . $width_id][] = $consumption;

		$subsidary = $obj->get_rows("`subsidary_details`", "*", "`consumption_id`='" . $consumption['id'] . "'");

		foreach ($subsidary as $key => $sub) {
			$product = $obj->get_rows("`product`", "*", "`id`=" . $sub['product_id']);
			$subsidary[$key]['product_name'] = $product[0]['product_name'];

			$option = '<option value="">Select Subsiday Material</option>';
			foreach ($subsidary_item as $items) {
				$option .= "<option value='" . $items['id'] . "'";
				if ($items['id'] == $sub['subsidary_id']) {
					$option .= " selected";
				}
				$option .= ">" . $items['name'] . "</option>";
			}
			$subsidary[$key]['sub_option'] = $option;
		}


		$grouped_consumption[$size_id . '_' . $pattern . '_' . $width_id]["subsidary"] = $subsidary;

		// print_r($grouped_consumption);
	}
	// print_r($grouped_consumption);


	$array['csize'] = $csize;
	$array['consume'] = $grouped_consumption;
	$array['subsidary'] = $option;
	echo json_encode($array);
} elseif (isset($_POST['getWidth'])) {
	$shop = $_POST['shop'];
	$array = $obj->get_rows("`width`", "*", "`shop`='$shop'", "`id`");
	$select = "<option value=''>Select Width</option>";
	// if(isset($_POST['page']) && $_POST['page']=='jobentry'){
	// 	$select.="<option value='-1'>Miscellaneous</option>";
	// }
	if (is_array($array)) {
		foreach ($array as $width) {
			$select .= "<option value='$width[id]'>$width[width]</option>";
		}
	}
	echo $select;
} elseif (isset($_POST['page']) && $_POST['page'] == 'barcode') {
	$barcode = $_POST['barcode'];
	$code = $obj->get_rows('bale_meter_tally', "*", "`barcode`=" . $barcode . "", "", "", "`barcode`");
	$code_id = $code[0]['stock_id'];
	$array = $obj->get_rows("`stock`", "*", "`id`='$code_id'", "`id`");
	// print_r($array);
	// if(isset($_POST['page']) && $_POST['page']=='jobentry'){
	// 	$select.="<option value='-1'>Miscellaneous</option>";
	// }
	echo json_encode($array);
} elseif (isset($_POST['getCity'])) {
	$state_id = $_POST['state_id'];
	$array = $obj->get_rows("`sm_area`", "*", "`type`='district' and `state_id`=$state_id");
	$select = "<option value=''>Select District</option>";

	if (is_array($array)) {
		foreach ($array as $city) {
			$select .= "<option value='$city[id]'>$city[name]</option>";
		}
	}
	echo $select;
} elseif (isset($_GET['same_packable_temp']) && $_GET['same_packable_temp'] == 'same_packable_temp') {
	$id = $_GET['id'];
	$id = (int) $id;
	$shop = $_GET['shop'];
	$bale = $obj->get_details("`packabletemp`", "*", "`id`=" . $id . " and `shop`=" . $shop);
	if ($bale === true) {
		echo json_encode($bale);
	} else {
		echo json_encode($bale);
	}
} elseif (isset($_POST['getsubsidaryproduct'])) {
	$shop = $_POST['shop'];
	$array = $obj->get_rows("`raw_material`", "*", "`shop`='$shop' and type='subsidiary'");
	$select = "<option value=''>Select Subsiday Material</option>";
	// if(isset($_POST['page']) && $_POST['page']=='jobentry'){
	// 	$select.="<option value='-1'>Miscellaneous</option>";
	// }
	if (is_array($array)) {
		foreach ($array as $material) {
			$select .= "<option value='$material[id]'>$material[name]</option>";
		}
	}
	echo $select;
} elseif (isset($_POST['getPattern'])) {
	$shop = $_POST['shop'];
	$id = $_POST['id'];
	$array = $obj->get_rows("`pattern`", "*", "`shop`='$shop' and `prod_id`=$id");
	$select = "<option value=''>Select Pattern</option>";
	// if(isset($_POST['page']) && $_POST['page']=='jobentry'){
	// 	$select.="<option value='-1'>Miscellaneous</option>";
	// }
	if (is_array($array)) {
		foreach ($array as $pattern) {
			$select .= "<option value='$pattern[id]'>$pattern[pattern_name]</option>";
		}
	}
	echo $select;
} elseif (isset($_POST['getBedsheetsize'])) {
	$shop = $_POST['shop'];
	$width_id = $_POST['width_id'];
	//$array=$obj->get_rows("`size`","*","`shop`='$shop' and `item`='Bedsheet'","`size`");
	$array = $obj->get_rows("`                                                                                                                    ` t1, `consumption` t2", "t1.*,t2.`consume`", "t1.`item`='Bedsheet' and t1.`id`=t2.`size_id` and t2.`width_id`='$width_id'");
	//    print_r($array);die;
	$select = "<option value=''>Select Bedsheet Size</option>";
	if (is_array($array)) {
		foreach ($array as $a) {
			$select .= "<option value='$a[consume]'>$a[size]</option>";
		}
	}
	echo $select;
} elseif (isset($_POST['getPillowsize'])) {
	$shop = $_POST['shop'];
	$width_id = $_POST['width_id'];
	//$array=$obj->get_rows("`size`","*","`shop`='$shop' and `item`='Pillow'","`size`");
	$array = $obj->get_rows("`size` t1, `consumption` t2", "t1.*,t2.`consume`", "t1.`item`='Pillow' and t1.`id`=t2.`size_id` and t2.`width_id`='$width_id'");
	$select = "<option value=''>Select Pillow Size</option>";
	if (is_array($array)) {
		foreach ($array as $a) {
			$select .= "<option value='$a[consume]'>$a[size]</option>";
		}
	}
	echo $select;
} elseif (isset($_POST['getWorker']) && $_POST['page'] == 'stitching') {
	$shop = $_POST['shop'];
	$array = $obj->get_rows("`worker`", "*", "`designation`!='cutter' and `payment_type`='pcs_wise' and `shop`='$shop'", "`name`");
	$select = "<option value='' disabled selected>Select Worker</option>";
	if (is_array($array)) {
		foreach ($array as $worker) {
			$select .= "<option value='$worker[id]'>$worker[name]</option>";
		}
	}
	echo $select;
} elseif (isset($_POST['getWorker'])) {
	$shop = $_POST['shop'];
	$array = $obj->get_rows("`worker`", "*", "`shop`='$shop'", "`name`");
	$select = "<option value=''>Select Job Worker</option>";
	if (is_array($array)) {
		foreach ($array as $worker) {
			$select .= "<option value='$worker[id]'>$worker[name]</option>";
		}
	}
	echo $select;
} elseif (isset($_POST['editFinished'])) {
	$id = $_POST['id'];
	$array = $obj->get_details("`finished`", "*", "`id`='$id'");
	echo json_encode($array);
} elseif (isset($_POST['addstock']) && $_POST['pagename'] == 'cutting') {
	// print_r($_POST);die;
	$date = date('Y-m-d');
	$raw_id = strip_tags($_POST['raw_id']);
	$d_no = strip_tags($_POST['d_no']);
	$width_id = strip_tags($_POST['width_id']);
	$meter = strip_tags($_POST['meter']);
	$quantity = strip_tags($_POST['qty']);
	$shop = $_SESSION['shop'];
	$image = '';
	$columns = "(`date`,`raw_id`, `d_no`,`width_id`,`meter`,`quantity`,`image`,`shop`)";
	$values = "('$date','$raw_id','$d_no','$width_id','$meter','$quantity','$image','$shop')";
	$run = $obj->insert("`stock`", $columns, $values);
	if ($run) {
		$lastid = $obj->get_last_row("`stock`", "`id`", "`shop`='$shop'");
		echo $lastid['id'];
	} else {
		echo '0';
	}
} elseif (isset($_POST['addcuttingstock']) && $_POST['addcuttingstock'] == 'add') {
	$date = $_POST['date'];
	$shop = $_POST['shop'];
	$raw_id = $_POST['raw_id'];
	$width_id = $_POST['width_id'];
	$meter = $_POST['meter'];
	$quantity = $_POST['quantity'];
	$d_no = $_POST['d_no'];
	$image = '';
	// $image==$_FILES['file']['tmp_name'];
	// print_r($image);die;

	$arr1 = $obj->get_details('`stock`', "`id`,`meter`", "`raw_id`='$raw_id' AND `width_id`='$width_id' AND `d_no`='$d_no'");
	if (is_array($arr1)) {
		$run = $obj->update("`stock`", "`meter`=`meter`+'$meter'", "`id`='$arr1[id]'");
		$lastid = $arr1['id'];
		$msg = "Meter Updated Successfully !";
	} else {
		$columns = "(`date`,`raw_id`, `d_no`,`width_id`,`meter`,`quantity`,`image`,`shop`)";
		$values = "('$date','$raw_id','$d_no','$width_id','$meter','$quantity','$image','$shop')";
		$run = $obj->insert("`stock`", $columns, $values);
		$lastid = $obj->get_last_row("`stock`", "`id`", "`shop`='$shop'");
		$lastid = $lastid['id'];
		$msg = "stock Added Successfully !";
	}
	if ($run === true) {
		echo $lastid;
	} else {
		echo "0";
	}
} elseif (isset($_POST['getStock'])) {
	$stock_id = $_POST['stock_id'];
	$array = $obj->get_details("`stock` t1, `raw_material` t2,`width` t3", "t1.*,t2.`name` as `raw_name`,t3.`width` as `width_name`", "t1.`id`='$stock_id' and t1.`raw_id`=t2.`id` and t1.`width_id`=t3.`id`");
	// print_r($array);
	$bale_meter = $obj->get_details("`bale_meter_tally`", "sum(`meter_breakup`) as avail_qty", "`stock_id`=$stock_id and `status`=1");
	$array['bale_meter'] = $bale_meter;

	echo json_encode($array);
} elseif (isset($_POST['getCutting'])) {
	$cutting_id = $_POST['cutting_id'];
	$array = $obj->get_details("`cutting` t1, `worker` t2", "t1.*,t2.`name` as `worker_name`", "t1.`id`='$cutting_id' and t1.`worker`=t2.`id`");
	echo json_encode($array);
} elseif (isset($_POST['getStitching']) && isset($_POST['page'])) {
	$stitching_id = $_POST['stitching_id'];
	$array = $obj->get_details("`stitching`", "*", "`id`='$stitching_id'");
	echo json_encode($array);
} elseif (isset($_POST['getStitching'])) {
	$cutting_id = $_POST['cutting_id'];
	$cuttingitem = $obj->get_rows('`cuttingitem` t1,`stock` t2,`raw_material` t3,`width` t4', 't1.*,t2.raw_id,t2.image,t2.d_no,t2.meter,t3.name as raw_material,t4.width,t4.unit', "t1.`cutting_id`=$cutting_id and t1.stock_id=t2.id and t2.raw_id=t3.id and t2.width_id=t4.id");
	$html = '';
	if (!empty($cuttingitem)) {
		$i = 0;
		foreach ($cuttingitem as $item) {
			$i++;
			$image[] = $item['image'];
			$raw_material[] = $item['raw_material'];
			$width[] = $item['width'] . ' ' . $item['unit'];
			$d_no[] = $item['d_no'];
			$html .= '
<table width="100%" border="1" cellpadding="5px" cellspacing="0px" style="text-align:center;">
<tr>
	<th>#</th>
	<th>Raw Material</th>
	<td>Design No.</td>
	<th>Width</th>
	<td>Meter</td>
</tr>
<tr>
	<td>' . $i . '</td>
	<td><img src="../uploads/' . $item['image'] . '" alt="Image" class="img-fluid" style="max-width:100px;">' . $item['raw_material'] . '</td>
	<td>' . $item['d_no'] . '</td>
	<td>' . $item['width'] . '</td>
	<td>' . $item['meter'] . '</td>
</tr>
<tr>
	<td colspan="5">
<table width="100%" border="1" cellpadding="5px" cellspacing="0px" style="text-align:center;">
<tr>
	<th>Meter Breakup</th>
	<th>Remark</th>
	<th>Bedsheet Size</th>
	<th>Qty</th>
	<th>Pillow Size</th>
	<th>Qty</th>
	<th>Total Consume</th>
</tr>';
			$cuttingdetail = $obj->get_rows('`cuttingdetail`', '*', "`cuttingitem_id`=$item[id]");
			if (!empty($cuttingdetail)) {
				foreach ($cuttingdetail as $detail) {

					$html .= '<tr>
		<td>' . $detail['meterbreakup'] . '</td>
		<td>';
					$remark = json_decode($detail['remark']);
					if (!empty($remark)) {
						foreach ($remark as $r) {
							$html .= '<p>' . $r . '</p>';
						}
					}
					$html .= '</td>
		<td>
		';
					$bedsheetsize_id = json_decode($detail['bedsheetsizeid']);
					if (!empty($bedsheetsize_id)) {
						foreach ($bedsheetsize_id as $bsid) {
							$size = $obj->get_details('`size`', '*', "`id`=$bsid");

							$html .= '<p>' . $size['size'] . '</p>';
						}
					}
					$html .= '</td>
		<td>
		';
					$bedsheetpcs = json_decode($detail['bedsheetpcs']);
					if (!empty($bedsheetpcs)) {
						foreach ($bedsheetpcs as $bkey => $bsp) {
							if (!empty($bedsheetsize_id[$bkey])) {
								$html .= '<p>' . $bsp . '</p>';
							}
						}
					}
					$html .= '</td>
		<td>
		';
					$pillowsize_id = json_decode($detail['pillowsizeid']);
					if (!empty($pillowsize_id)) {
						foreach ($pillowsize_id as $psid) {
							$size = $obj->get_details('`size`', '*', "`id`=$psid");
							$html .= '<p>' . $size['size'] . '</p>';
						}
					}
					$html .= '</td>
		<td>';
					$pillowpcs = json_decode($detail['pillowpcs']);
					if (!empty($pillowpcs)) {
						foreach ($pillowpcs as $pkey => $psp) {
							if (!empty($pillowsize_id[$pkey])) {
								$html .= '<p>' . $psp . '</p>';
							}
						}
					}
					$html .= '</td>
		<td>';
					$consume = json_decode($detail['consume']);
					if (!empty($consume)) {
						foreach ($consume as $c) {
							$html .= '<p>' . $c . '</p>';
						}
					}
					$html .= '</td>
	</tr>';
				}
			}
			$html .= '</table>
</td>
</tr>
</table>';
		}
	}
	$html .= '
<input type="hidden" name="raw_material" value="' . $raw_material[0] . '">
<input type="hidden" name="width" value="' . $width[0] . '">
<input type="hidden" name="d_no" value="' . $d_no[0] . '">
<input type="hidden" name="image" value="' . $image[0] . '">';
	echo $html;
}
// elseif(isset($_POST['getStitching'])){
// 	$cutting_id=$_POST['cutting_id'];
// 	$array=$obj->get_rows("`cuttingitem` t1, `cuttingdetail` t2, `stock` t3, `raw_material` t4,`width` t5","t2.`bedsheetsizeid`,t2.`pillowsizeid`,t2.`bedsheetpcs`,t2.`pillowpcs`,t3.d_no,t3.image,t4.name as raw_material,t5.width,t5.unit","t1.`cutting_id`='$cutting_id' AND t1.`id`=t2.`cuttingitem_id` AND t1.`stock_id`=t3.`id` AND t3.`raw_id`=t4.`id` AND t3.`width_id`=t5.`id`");
// 	// echo "<pre>";print_r($array);die;
// 	$bedsheetsize=array();
// 	$pillowsize=array();
// 	foreach($array as $ar){
// 		$image=$ar['image'];
// 		$raw_material=$ar['raw_material'];
// 		$width=$ar['width'].' '.$ar['unit'];
// 		$d_no=$ar['d_no'];

// 		$arr_bedsheetsize=json_decode($ar['bedsheetsizeid']);
// 		$arr_bedsheetpcs=json_decode($ar['bedsheetpcs']);
// 		$arr_pillowsize=json_decode($ar['pillowsizeid']);
// 		$arr_pillowpcs=json_decode($ar['pillowpcs']);
// 		foreach($arr_bedsheetsize as $bedkey=>$arbed){
// 			if(in_array($arbed,$bedsheetsize)){
// 				$bk=array_search($arbed,$bedsheetsize);
// 				$bedsheetqty[$bk]=$arr_bedsheetpcs[$bk]+$arr_bedsheetpcs[$bedkey];
// 			}else{
// 				$bedsheetsize[]=$arbed;
// 				$bedsheetqty[]=$arr_bedsheetpcs[$bedkey];
// 			}
// 		}
// 		foreach($arr_pillowsize as $pillkey=>$arpill){
// 			if(in_array($arpill,$pillowsize)){
// 				$pk=array_search($arpill,$pillowsize);
// 				$pillowqty[$pk]=$arr_pillowpcs[$pk]+$arr_pillowpcs[$pillkey];
// 			}else{
// 				$pillowsize[]=$arpill;
// 				$pillowqty[]=$arr_pillowpcs[$pillkey];
// 			}
// 		}
// 	}
// $array1['bedsheetsize']=$bedsheetsize;
// $array1['bedsheetqty']=$bedsheetqty;
// $array1['pillowsize']=$pillowsize;
// $array1['pillowqty']=$pillowqty;
// $count=count($bedsheetsize)+count($pillowsize);
// $html='';
// $html.='<table class="table table-bordered"><tr>
// <th rowspan="2">Image</th>
// <th rowspan="2">Raw Material</th>
// <th rowspan="2">Width</th>
// <th rowspan="2">Design No.</th>
// <th colspan="2">Bedsheet</th>
// <th colspan="2">Pillow</th>
// </tr>
// <tr>
// <th>Size</th>
// <th>Qty</th>
// <th>Size</th>
// <th>Qty</th>
// </tr>';
// for($i=0;$i<$count/2;$i++){
// $bedsize=$obj->get_details("`size`","`size`","`id`='".$bedsheetsize[$i]."'");
// $pillsize=$obj->get_details("`size`","`size`","`id`='".$pillowsize[$i]."'");
// $html.='<tr>
// <td><img src="../uploads/'.$image.'" alt="Image" class="img-fluid" style="max-width:100px;"></td>
// <td>'.$raw_material.'</td>
// <td>'.$width.'</td>
// <td>'.$d_no.'</td>
// <td>'.$bedsize['size'].'</th>
// <td>'.$bedsheetqty[$i].'</th>
// <td>'.$pillsize['size'].'</th>
// <td>'.$pillowqty[$i].'</th>
// </tr>';
// }
// $html.='</table>
// <input type="hidden" name="raw_material" value="'.$raw_material.'">
// <input type="hidden" name="width" value="'.$width.'">
// <input type="hidden" name="d_no" value="'.$d_no.'">
// <input type="hidden" name="image" value="'.$image.'">
// ';
// echo $html;
// }
elseif (isset($_POST['checkStatus'])) {
	$job_id = $_POST['job_id'];
	$check = $obj->get_details("`jobs` t1,`worker` t2", "t1.`id`,t2.`name` as `worker`", "t1.`id`='$job_id' and t1.`worker`=t2.`id`");
	echo json_encode($check);
} elseif (isset($_POST['getPro'])) {
	$shop = $_POST['shop'];
	$job_id = $_POST['job_id'];
	$getworker = $obj->get_details("`jobs`", "`worker`", "`id`='$job_id'");
	$worker = $getworker['worker'];
	$array = $obj->get_rows("`finished`", "*", "`shop`='$shop'", "`name`");
	$select = "<option value=''>Select Product</option>";
	if (is_array($array)) {
		foreach ($array as $finished) {
			$getraw = $obj->get_rows("`materials_used`", "`rawmaterial`", "`finished_id`='$finished[id]'");
			$count = 0;
			foreach ($getraw as $raw) {
				//$check=$obj->get_count("`avl_quantity`","`worker`='$worker' and `raw`='$raw[rawmaterial]'");
				$check = $obj->get_count("`material_list`", "`raw`='$raw[rawmaterial]' and `job_id`='$job_id'");
				if ($check == 0) {
					$count++;
					break;
				}
			}
			if ($count == 0) {
				$select .= "<option value='$finished[id]'>$finished[name]</option>";
			}
		}
	}
	echo $select;
} elseif (isset($_POST['getJobcharge'])) {
	$product = $_POST['product'];
	$array = $obj->get_details("`finished`", "*", "`id`='$product'");
	echo json_encode($array);
} elseif (isset($_POST['getProduct'])) {
	$prod = $_POST['product'];
	// print_r($prod);
	$getsize1 = $obj->get_rows("`size`", "*", "`item`=$prod");
	// print_r($getsize1);
	// $array=$obj->get_rows("`size`","*","`item`='$prod'");
	$sizeselect = "<option value=''>Select Size</option>";
	if (is_array($getsize1)) {
		foreach ($getsize1 as $size1) {
			$sizeselect .= "<option value='$size1[id]'>$size1[size]</option>";
		}
	}

	$getpattern1 = $obj->get_rows("`pattern`", "*", "`prod_id`=$prod");
	// print_r($getsize1);
	// $array=$obj->get_rows("`size`","*","`item`='$prod'");
	$patternselect = "<option value=''>Select Pattern</option>";
	if (is_array($getpattern1)) {
		foreach ($getpattern1 as $pattern1) {
			$patternselect .= "<option value='$pattern1[id]'>$pattern1[pattern_name]</option>";
		}
	}
	echo json_encode(array('size' => $sizeselect, 'pattern' => $patternselect));
} elseif (isset($_POST['bale_meter_tally'])) {
	$raw_id = $_POST['raw_id'];
	$width_id = $_POST['width_id'];
	$bale_no = $_POST['bale_no'];
	$lot_no = $_POST['lot_no'];

	// Construct the WHERE clause dynamically based on the provided inputs
	$whereConditions = [];

	if (!empty($raw_id) && !empty($width_id)) {
		$whereConditions[] = "`raw_id` = '$raw_id' and `width_id` = '$width_id'";
	}

	// if (!empty($width_id)) {
	//     $whereConditions[] = " = '$width_id'";
	// }

	if (!empty($bale_no)) {
		$whereConditions[] = "`bale_id` = '$bale_no'";
	}

	if (!empty($lot_no)) {
		$whereConditions[] = "`lot_no` = '$lot_no'";
	}

	// If any condition is provided, join them with "OR"
	$whereQuery = implode(" OR ", $whereConditions);

	// If there are any conditions to filter by, apply the WHERE clause
	if (!empty($whereQuery)) {
		$bale_meter_tally = $obj->get_rows("`stock`", "stock.*,raw_material.name, stock.id as stock_id, width.width as width_name, width.unit as width_unit, supplier.name as supplier_name", $whereQuery, '', '', '', "INNER JOIN `raw_material` ON `stock`.`raw_id` = `raw_material`.`id` INNER JOIN `width` ON `stock`.`width_id` = `width`.`id` INNER JOIN `supplier` ON `stock`.`supplier_id`=`supplier`.`id`");

		echo json_encode($bale_meter_tally);
	} else {
		// If no parameters are provided, return an empty result
		echo json_encode([]);
	}
} elseif (isset($_POST['get_meterbreakup'])) {
	$stock_id = $_POST['stock_id'];
	$bale_meter = $obj->get_rows("`bale_meter_tally`", "*", '`stock_id`=' . $stock_id . ' and `status`=1');
	// print_r($bale_meter); die;
	echo json_encode($bale_meter);
} elseif (isset($_POST['page']) && $_POST['page'] == 'cutting_breakupwise') {
	$stock = $_POST['stock_id'];
	$bale_meter = $obj->get_rows("`bale_meter_tally`", "*", '`stock_id`=' . $stock . ' and `status`=1');
	$product = $obj->get_rows("`product`", "*", "`status`=1");

	$pagename = $_POST['pagename'];
	// print_r($pagename);
	$jobs = $obj->get_rows("`job_process`", "*", "`slug`='$pagename' and `status`=1");
	// print_r($jobs);
	$cutting_prod = array();
	$job_sqnce = "";
	foreach ($product as $prod) {
		$job_sqn = json_decode($prod['job_squence']);
		foreach ($job_sqn as $job) {
			// print_r($jobs[0]['id']);
			if ($job == $jobs[0]['id']) {
				$job_sqnce = $prod['job_squence'];
				// print_r("hii");
				$cutting_prod[] = $prod;
				break;
			}
		}
	}

	$data['bale_meter'] = $bale_meter;
	$data['cutting_prod'] = $cutting_prod;


	// print_r($bale_meter); die;
	echo json_encode(array('bale_meter' => $bale_meter, 'cutting_prod' => $cutting_prod, 'job' => $job, 'job_sqn' => $job_sqnce));
} elseif (isset($_POST['page']) && $_POST['page'] == 'get_product_details') {
	$prod_id = $_POST['prod_id'];
	$width_id = $_POST['width_id'];
	// print_r($prod_id);
	$size = $obj->get_rows("`size`", "*", "`item`='$prod_id'");
	// print_r($size);
	$consumption = $obj->get_rows("`consumption`", '*', "`prod_id`=$prod_id and width_id=$width_id");
	// print_r($size); die;

	$valid_size_ids = [];
	$valid_patter_id = [];
	// Loop through the consumption data and collect the valid size_ids
	if (!empty($consumption)) {
		foreach ($consumption as $c) {
			$valid_size_ids[] = $c['size_id'];
			$valid_patter_id[] = $c['pattern'];
		}
	}


	$option = '<option>Select Size</option>';
	$patternoption = ' <option>Select Pattern</option>';
	if (!empty($size)) {
		foreach ($size as $s) {
			// Check if the size id is in the valid_size_ids array
			if (in_array($s['id'], $valid_size_ids)) {
				$option .= "<option value=" . $s['id'] . ">" . $s['size'] . "</option>";
			}
		}

		$pattern = $obj->get_rows("`pattern`", "*", "`prod_id`='$prod_id'");
		// print_r($size);

		if (!empty($pattern)) {
			foreach ($pattern as $p) {
				if (in_array($p['id'], $valid_patter_id)) {
					$patternoption .= "<option value=" . $p['id'] . ">" . $p['pattern_name'] . "</option>";
				}
			}
		}

		// $subsidary_details = $obj->get_rows('subsidary_details', '*', "`product_id`=$prod_id");
		// if(!empty($subsidary_details)){
		// foreach($subsidary_details as $subd){

		// 	}
		// }
	}


	// print_r($option);

	// print_r($bale_meter); die;
	echo json_encode(array('size_option' => $option, 'pattern_option' => $patternoption));
} elseif (isset($_POST['page']) && $_POST['page'] == 'get_subsidary') {
	$prod_id = $_POST['prod_id'];
	$size_id = $_POST['size_id'];
	$pattern_id = $_POST['pattern_id'];
	$width_id = $_POST['width_id'];

	$subsidary_details = $obj->get_rows("`subsidary_details`", "*", "`product_id`='$prod_id'");
	$sub_details = array();
	if (!empty($subsidary_details)) {
		foreach ($subsidary_details as $subd) {
			$cus_id = $subd['consumption_id'];
			// $subsidary_details = $obj->get_rows("`consumption`", "*", "`id`='$subd['']'");
			$cunsume = $obj->get_rows('consumption', '*', "`id`=$cus_id and `size_id`=$size_id and `pattern`=$pattern_id and `width_id`=$width_id");
			// print_r($cunsume);
			if (!empty($cunsume)) {
				foreach ($cunsume as $cons) {
					$size = $cons['size_id'];
					$width = $cons['width_id'];
					$raw_id = $subd['subsidary_id'];

					$size_details = $obj->get_rows('size', '*', "`id`=$size");
					$width_details = $obj->get_rows('width', '*', "`id`=$width");
					$prod_details = $obj->get_rows('product', '*', "`id`=$prod_id");
					$raw_material = $obj->get_rows('raw_material', '*', "`id`=$raw_id");

					$sub_details[] = array(
						'subsidary_id' => $subd['id'],
						'subsidary_name' => $raw_material[0]['name'],
						'subsidary_unit' => $raw_material[0]['unit'],
						'product_name' => $prod_details[0]['product_name'],
						'prod_id' => $prod_details[0]['id'],
						'pattern_id' => $pattern_id,
						'size_id' => $size_details[0]['id'],
						'consumption' => $subd['subsidary_consume'],
						'size' => $size_details[0]['size'],
						'width' => $width_details[0]['width'],
						'width_unit' => $width_details[0]['unit'],
						'rate' => $raw_material[0]['rate'],
						// 'consumption' => $cons['consumption']
					);
				}
			}
		}
	}
	$data['subsidary'] = $sub_details;
	$consume = $obj->get_rows('consumption', '*', "`size_id`=$size_id and `width_id`=$width_id and `pattern`=$pattern_id");
	// print_r($consume);
	if (!empty($consume)) {
		$data['consumption'] = $consume[0]['consume'];
	}

	echo json_encode($data);
} elseif (isset($_POST['page']) && $_POST['page'] == 'get_prod_consumtion') {
	$width_id = $_POST['width_id'];
	$size_id = $_POST['size_option'];
	$prod_id = $_POST['prod_id'];
	echo json_encode($sub_details);
} elseif (isset($_POST["job_process_flow"]) && $_POST["job_process_flow"] == "job_process_flow") {
	// print_r($_POST);
	$order_id = $_POST['order_id'];
	$curr_job_id = $_POST['curr_job_id'];
	$sqn_id = $_POST['sqn_id'];

	$order_product = $obj->get_rows("`order_product`", "*", "`order_id`=$order_id");
	$order = $obj->get_rows("`sm_order`", "*", "`id`=$order_id");

	$prod = [];
	$subidary_prods2 = [];
	$pagename = $_POST['pagename'];
	$cutting_id = '';
	$order_id = $order[0]["id"];
	$cutting_id = json_decode($order[0]['sqn_id']);
	$cutting_id = $cutting_id[0];
	$consumption_total = floatval($order[0]["total_consumption"]) * floatval($order[0]["order_quantity"]);
	//print_r($consumption_total);
	$product_table = '<div><table id="my_product_table" class="table table-bordered my_product_table">';
	$product_table .= '<thead style="background: antiquewhite;"><tr>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Meter Breakup</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Product</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Pattern</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Raw Material</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Width</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Size</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Quantity</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Consumption</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Worker</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Remark</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Alloted Quantity</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Action</th>
	</tr></thead>';
	$total_quant = 0;
	$subsidary_table = '<div>
		<h3 style="text-align:center">Subsidary Material</h3>
		<table id="product_table" class="table table-bordered">
		<thead style="background: antiquewhite;"><tr>
		<th style="vertical-align: middle; text-align: center;">Product</th>
			<th style="vertical-align: middle; text-align: center;">Material</th>
			<th style="vertical-align: middle; text-align: center;">Per unit Consumption</th>
			<th style="vertical-align: middle; text-align: center;">Consumption</th>
		</tr></thead>
	';
	$jobs = $obj->get_rows("`job_process`", "*", "`slug`='$pagename' and `status`=1");

	$ass_id_data = array();
	foreach ($order_product as $key => $product) {
		$job_sqn = json_decode($product['job_squence']);
		$sqn_id = 0;
		foreach ($job_sqn as $key => $sqn) {
			if ($product['curr_job'] == $sqn) {
				$sqn_id = $key;
				break;
			}
		}
		// print_r($sqn_id);
		if ($job_sqn[$sqn_id] == $jobs[0]['id']) {
			$prod[] = $product;
			// print_r($product);
			$prods = $obj->get_rows("`product`", "*", "`id`='" . $product['product_id'] . "' and `status`=1");
			$prod[$key]["product"] = $prods[0]['product_name'];

			$pattern = $obj->get_rows("`pattern`", "*", "`id`='" . $product['pattern_id'] . "' and `status`=1");
			$prod[$key]["pattern"] = $pattern[0]['pattern_name'];

			$raw = $obj->get_rows("`raw_material`", "*", "`id`='" . $product['raw_id'] . "'");
			$prod[$key]["raw"] = $raw[0]['name'];

			$size = $obj->get_rows("`size`", "*", "`id`='" . $product['size_id'] . "'");
			$prod[$key]["size"] = $size[0]['size'];

			$width = $obj->get_rows("`width`", "*", "`id`='" . $product['width_id'] . "'");
			$prod[$key]["width"] = $width[0]['width'];

			$subidary_prods = $obj->get_rows("`consumption`", "*", "`size_id`=" . $product['size_id'] . " and `pattern`=" . $product['pattern_id'] . " and `width_id`=" . $product['width_id']);

			// $subidary_prods2 = $obj->get_rows("`consumption`", "*", "`size_id`=" . $product['size_id'] . " and `pattern`=" . $product['pattern_id']. "");

			// print_r($subidary_prods);

			$product_table .= '
				<tr>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . $product['meter_breakup'] . '</td>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . $prods[0]['product_name'] . '</td>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . $pattern[0]['pattern_name'] . '</td>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . $raw[0]['name'] . '</td>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . $width[0]['width'] . '</td>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . $size[0]['size'] . '</td>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . $product['product_quant'] . '</td>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . floatval($product['product_quant']) * floatval($subidary_prods[0]['consume']) . '</td>';
			$process = $_POST['pagename'];
			$job_process = $obj->get_rows("`job_process`", "*", "`slug`='$process'");
			$job_permission = json_decode($job_process[0]['permission'] ?? '');
			$where = '';
			if (!empty($job_permission)) {
				$lastKey = array_key_last($job_permission); // Get the last key
				foreach ($job_permission as $key => $job) {
					if ($key == $lastKey) {
						$where .= "`designation`='$job'"; // Do not add 'or' to the last element
					} else {
						$where .= "`designation`='$job' OR "; // Add 'or' for all other elements
					}
				}
			}
			$product_table .= '<td style="vertical-align: middle;">
    <select name="worker_id[]" id="worker_id" class="form-control to-enter" required>
        <option value="" selected disabled>Select Worker</option>';

			$workers = $obj->get_rows("`worker`", "*", $where);

			if (!empty($workers)) {
				foreach ($workers as $workerData) {
					$attendance = $obj->get_rows(
						"`attendance`",
						"*",
						"`worker`=" . (int) $workerData['id'] . " AND `date`='" . date('Y-m-d') . "' AND `status`=1"
					);

					if (!empty($attendance)) {
						$product_table .= '<option value="' . htmlspecialchars($workerData['id']) . '">';
						$product_table .= htmlspecialchars($workerData['name']);
						$product_table .= '</option>';
					}
				}
			}

			$product_table .= '</select>
</td>';
			// print_r($product);
			$product_table .= '<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . $product['remark'] . '</td>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;"><input type="hidden" name="order_prod_id[]" value="' . $product['id'] . '"><input type="hidden" name="order_id[]" value="' . $product['order_id'] . '"><input type="hidden" name="curr_job[]" value="' . $product['curr_job'] . '"><input type="text" name="alloted_quantity[]" id="alloted_quantity" class="form-control to-enter" placeholder="Enter Alloted Quantity"> </td>
					<td><button type="button" class="btn btn-success add_worker_job">Add</button></td>
				</tr>
				';
			$total_quant = floatval($total_quant) + floatval($product['product_quant']) * floatval($subidary_prods[0]['consume']);
			$key = 0;

			$ass_id_data[] = array(
				'order_id' => $order_id,
				'order_prod_id' => $product['id'],
				'process_id' => $job_sqn[$sqn_id]
			);
			if (!empty($subidary_prods)) {
				foreach ($subidary_prods as $subsidary) {
					// print_r($subsidary);
					$prod[$key]["subsidary"][] = $subsidary;
					$consumption = $obj->get_rows("`subsidary_details`", "*", "`consumption_id`=" . $subsidary['id'] . " and `product_id`=" . $product['product_id']);

					if (!empty($consumption)) {
						foreach ($consumption as $con) {
							//print_r($key);
							$sub_prod = $obj->get_rows("`raw_material`", "*", "`id`=" . $con['subsidary_id']);
							$prod[$key]["subsidary"][$key]["subsidary_name"] = $sub_prod[0]['name'];
							$subsidary_table .= '<tr>
							<td style="vertical-align: middle; text-align: center;">' . $prods[0]['product_name'] . '</td>
								<td style="vertical-align: middle; text-align: center;">' . $sub_prod[0]['name'] . '</td>
								<td style="vertical-align: middle; text-align: center;">' . $con['subsidary_consume'] . '</td>
								<td style="vertical-align: middle; text-align: center;">' . floatval($product['product_quant']) * floatval($con['subsidary_consume']) . '</td>
							</tr>';
							//intval($key++);
						}
					}
				}
			}
			// print_r($consumption);
		}
	}

	// print_r($prod);

	$product_table .= '</table></div>';
	$subsidary_table .= '</table></div>';
	// print_r($product_table); die;
	$data_table = '<div><table id="product_table" class="table table-bordered"><tr>';
	$data_table .= "<th style='white-space: nowrap; padding:0px 20px; vertical-align: middle;'>Cutting ID</th>
										<td style='vertical-align: middle;'><input type='text' name='cutting_id'
												class='form-control to-enter cutting_id' placeholder='Enter Cutting ID' value=" . $cutting_id . " readonly>
										</td>";
	$process = $_POST['pagename'];
	$job_process = $obj->get_rows("`job_process`", "*", "`slug`='$process'");
	$job_permission = json_decode($job_process[0]['permission'] ?? '');

	$where = '';
	if (!empty($job_permission)) {
		$lastKey = array_key_last($job_permission); // Get the last key
		foreach ($job_permission as $key => $job) {
			if ($key == $lastKey) {
				$where .= "`designation`='$job'"; // Do not add 'or' to the last element
			} else {
				$where .= "`designation`='$job' OR "; // Add 'or' for all other elements
			}
		}
	}

	// 	$data_table .= '<th style="white-space: nowrap; padding:20px; vertical-align: middle;"><label>Worker</label></th>';
	// 	$data_table .= '<td style="vertical-align: middle;">
	//     <select name="worker_id[]" id="worker_id" class="form-control to-enter" required>
	//         <option value="" selected disabled>Select Worker</option>';

	// 	$workers = $obj->get_rows("`worker`", "*", $where);

	// 	if (!empty($workers)) {
	// 		foreach ($workers as $workerData) {
	// 			$attendance = $obj->get_rows(
	// 				"`attendance`",
	// 				"*",
	// 				"`worker`=" . (int) $workerData['id'] . " AND `date`='" . date('Y-m-d') . "' AND `status`=1"
	// 			);

	// 			if (!empty($attendance)) {
	// 				$data_table .= '<option value="' . htmlspecialchars($workerData['id']) . '">';
	// 				$data_table .= htmlspecialchars($workerData['name']);
	// 				$data_table .= '</option>';
	// 			}
	// 		}
	// 	}

	// 	$data_table .= '</select>
	// </td>';

	$data_table .= '<th style="white-space: nowrap; padding:0px 20px; vertical-align: middle;">Total Quantity</th>';
	$data_table .= '<td style="vertical-align: middle;"><input type="text" name="total_quantity" id="total_quantity" 
    class="form-control to-enter" placeholder="Enter Total Quantity" value="' . $total_quant . '" readonly>
	<input type="hidden" name="order[]" value="' . $order_id . '"
	</td>';
	// print_r($data_table);
	// $data_table .= '<th style="white-space: nowrap; padding:0px 20px; vertical-align: middle;">Alloted Quantity</th>';
	// $data_table .= '<td style="vertical-align: middle;"><input type="text" name="alloted_quantity" id="alloted_quantity" 
	// class="form-control to-enter" placeholder="Enter Alloted Quantity"> 
	// $data_table .= '<input type="hidden" name="order[]" value="' . $order_id . '"</td>';
	// $data_table .= '<td>';
	$data_table .= '</tr></table>';
	// print_r($ass_id_data);
	$data_table .= $product_table;
	$data_table .= $subsidary_table;
	echo json_encode(array("data" => $prod, "status" => true, "data_table" => $data_table, "assign_id_data" => $ass_id_data));
	// print_r($subidary_prods2);
} elseif (isset($_POST["edit_job_process_flow"]) && $_POST["edit_job_process_flow"] == "edit_job_process_flow") {
	// print_r($_POST);
	$order_id = $_POST['order_id'];
	$curr_job_id = $_POST['curr_job_id'];
	$sqn_id = $_POST['sqn_id'];

	$order_product = $obj->get_rows("`order_product`", "*", "`order_id`=$order_id");
	$order = $obj->get_rows("`sm_order`", "*", "`id`=$order_id");

	$prod = [];
	$subidary_prods2 = [];
	$pagename = $_POST['pagename'];
	$cutting_id = '';
	$order_id = $order[0]["id"];
	$cutting_id = json_decode($order[0]['sqn_id']);
	$cutting_id = $cutting_id[0];
	$consumption_total = floatval($order[0]["total_consumption"]) * floatval($order[0]["order_quantity"]);
	// print_r($consumption_total);
	$product_table = '<div><table id="my_product_table" class="table table-bordered my_product_table">';
	$product_table .= '<thead style="background: antiquewhite;"><tr>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Meter Breakup</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Product</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Pattern</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Raw Material</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Width</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Size</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Quantity</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Consumption</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Left Consumption</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Worker</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Remark</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Alloted Quantity</th>
		<th style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">Action</th>
	</tr></thead>';
	$total_quant = 0;
	$subsidary_table = '<div>
		<h3 style="text-align:center">Subsidary Material</h3>
		<table id="product_table" class="table table-bordered">
		<thead style="background: antiquewhite;"><tr>
		<th style="vertical-align: middle; text-align: center;">Product</th>
			<th style="vertical-align: middle; text-align: center;">Material</th>
			<th style="vertical-align: middle; text-align: center;">Per Unit Consumption</th>
			<th style="vertical-align: middle; text-align: center;">Consumption</th>
		</tr></thead>
	';

	$assign_table = '<div>
		<table id="assign_table" class="table table-bordered">
		<thead style="background: antiquewhite;"><tr>
			<th style="vertical-align: middle; text-align: center;">Product</th>
			<th style="vertical-align: middle; text-align: center;">Raw Material</th>
			<th style="vertical-align: middle; text-align: center;">Size</th>
			<th style="vertical-align: middle; text-align: center;">Width</th>
			<th style="vertical-align: middle; text-align: center;">Assign Date/time</th>
			<th style="vertical-align: middle; text-align: center;">Assigned Qty</th>
			<th style="vertical-align: middle; text-align: center;">Assinee Name</th>
			<th style="vertical-align: middle; text-align: center;">Delete</th>
		</tr></thead><tbody>
	';
	$jobs = $obj->get_rows("`job_process`", "*", "`slug`='$pagename' and `status`=1");
	// print_r($curr_job_id); die;
	$ass_id_data = array();
	foreach ($order_product as $key => $product) {
		$job_sqn = json_decode($product['job_squence']);
		$sqn_id = 0;
		foreach ($job_sqn as $key => $sqn) {
			if ($product['curr_job'] == $sqn) {
				$sqn_id = $key;
				break;
			}
		}
		if ($job_sqn[$sqn_id] == $jobs[0]['id']) {
			$prod[] = $product;
			$prods = $obj->get_rows("`product`", "*", "`id`='" . $product['product_id'] . "' and `status`=1");
			$prod[$key]["product"] = $prods[0]['product_name'];

			$pattern = $obj->get_rows("`pattern`", "*", "`id`='" . $product['pattern_id'] . "' and `status`=1");
			$prod[$key]["pattern"] = $pattern[0]['pattern_name'];

			$raw = $obj->get_rows("`raw_material`", "*", "`id`='" . $product['raw_id'] . "'");
			$prod[$key]["raw"] = $raw[0]['name'];

			$size = $obj->get_rows("`size`", "*", "`id`='" . $product['size_id'] . "'");
			$prod[$key]["size"] = $size[0]['size'];

			$width = $obj->get_rows("`width`", "*", "`id`='" . $product['width_id'] . "'");
			$prod[$key]["width"] = $width[0]['width'];

			$subidary_prods = $obj->get_rows("`consumption`", "*", "`size_id`=" . $product['size_id'] . " and `pattern`=" . $product['pattern_id'] . " and `width_id`=" . $product['width_id']);

			// $subidary_prods2 = $obj->get_rows("`consumption`", "*", "`size_id`=" . $product['size_id'] . " and `pattern`=" . $product['pattern_id']. "");

			// print_r($product['curr_job']);

			$assign_history = $obj->get_rows("`order_assign_history`", "*", '`status`=1 and `order_id`=' . $product['order_id'] . ' and `order_prod_id`=' . $product['id'] . ' and `process_id`=' . $product['curr_job']);
			// print_r($assign_history);
			$left_con = 0;
			$total_qnt = 0;
			if (!empty($assign_history)) {
				foreach ($assign_history as $ass) {
					$total_qnt += floatval($ass['quant']);
				}
			}
			$left_con = round(floatval($product['product_quant']) * floatval($subidary_prods[0]['consume']) - floatval($total_qnt), 2);

			// print_r((floatval($product['product_quant']) * floatval($subidary_prods[0]['consume'])) - floatval($total_qnt));
			$product_table .= '
				<tr>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . $product['meter_breakup'] . '</td>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . $prods[0]['product_name'] . '</td>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . $pattern[0]['pattern_name'] . '</td>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . $raw[0]['name'] . '</td>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . $width[0]['width'] . '</td>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . $size[0]['size'] . '</td>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . $product['product_quant'] . '</td>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . floatval($product['product_quant']) * floatval($subidary_prods[0]['consume']) . '</td>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . $left_con . '</td>';
			$process = $_POST['pagename'];
			$job_process = $obj->get_rows("`job_process`", "*", "`slug`='$process'");
			// print_r($job_process);
			$job_permission = json_decode($job_process[0]['permission'] ?? '');
			$where = '';
			if (!empty($job_permission)) {
				$lastKey = array_key_last($job_permission); // Get the last key
				foreach ($job_permission as $key => $job) {
					if ($key == $lastKey) {
						$where .= "`designation`='$job'"; // Do not add 'or' to the last element
					} else {
						$where .= "`designation`='$job' OR "; // Add 'or' for all other elements
					}
				}
			}
			$product_table .= '<td style="vertical-align: middle;">
    <select name="worker_id[]" id="worker_id" class="form-control to-enter">
        <option value="" selected disabled>Select Worker</option>';

			$workers = $obj->get_rows("`worker`", "*", $where);

			if (!empty($workers)) {
				foreach ($workers as $workerData) {
					$attendance = $obj->get_rows(
						"`attendance`",
						"*",
						"`worker`=" . (int) $workerData['id'] . " AND `date`='" . date('Y-m-d') . "' AND `status`=1"
					);

					if (!empty($attendance)) {
						$product_table .= '<option value="' . htmlspecialchars($workerData['id']) . '">';
						$product_table .= htmlspecialchars($workerData['name']);
						$product_table .= '</option>';
					}
				}
			}

			$product_table .= '</select>
</td>';
			// print_r($product);
			$product_table .= '<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;">' . $product['remark'] . '</td>
					<td style="vertical-align: middle; text-align: center; text-wrap-mode: nowrap;"><input type="hidden" name="order_prod_id[]" value="' . $product['id'] . '"><input type="hidden" name="order_id[]" value="' . $product['order_id'] . '"><input type="hidden" name="curr_job[]" value="' . $product['curr_job'] . '"><input type="text" name="alloted_quantity[]" id="alloted_quantity" class="form-control to-enter" placeholder="Enter Alloted Quantity"> </td>
					<td><button type="button" class="btn btn-success add_worker_job">Add</button></td>
				</tr>
				';
			$total_quant = floatval($total_quant) + floatval($product['product_quant']) * floatval($subidary_prods[0]['consume']);
			$key = 0;

			$ass_id_data[] = array(
				'order_id' => $order_id,
				'order_prod_id' => $product['id'],
				'process_id' => $job_sqn[$sqn_id]
			);
			if (!empty($subidary_prods)) {
				foreach ($subidary_prods as $subsidary) {
					// print_r($subsidary);
					$prod[$key]["subsidary"][] = $subsidary;
					$consumption = $obj->get_rows("`subsidary_details`", "*", "`consumption_id`=" . $subsidary['id'] . " and `product_id`=" . $product['product_id']);

					if (!empty($consumption)) {
						foreach ($consumption as $con) {
							//print_r($key);
							$sub_prod = $obj->get_rows("`raw_material`", "*", "`id`=" . $con['subsidary_id']);
							$prod[$key]["subsidary"][$key]["subsidary_name"] = $sub_prod[0]['name'];
							$subsidary_table .= '<tr>
							<td style="vertical-align: middle; text-align: center;">' . $prods[0]['product_name'] . '</td>
								<td style="vertical-align: middle; text-align: center;">' . $sub_prod[0]['name'] . '</td>
								<td style="vertical-align: middle; text-align: center;">' . $con['subsidary_consume'] . '</td>
								<td style="vertical-align: middle; text-align: center;">' . floatval($product['product_quant']) * floatval($con['subsidary_consume']) . '</td>
							</tr>';
							//intval($key++);
						}
					}
				}
			}

			if (!empty($assign_history)) {
				foreach ($assign_history as $ass) {
					$emp = $obj->get_rows("worker", "*", "`id`=" . $ass['assign_id']);
					$assign_table .= "<tr><td>" . $prods[0]['product_name'] . "</td>";
					$assign_table .= "<td>" . $raw[0]['name'] . "</td>";
					$assign_table .= "<td>" . $size[0]['size'] . "</td>";
					$assign_table .= "<td>" . $width[0]['width'] . "</td>";
					$assign_table .= "<td>" . $ass['created_at'] . "</td>";
					$assign_table .= "<td>" . $ass['quant'] . "</td>";
					$assign_table .= "<td>" . $emp[0]['name'] . "</td>";
					$assign_table .= "<td><button type='button' class='btn btn-danger delete_assign_emp' data_id='" . $ass['id'] . "'><i class='fa fa-trash'></i></button></td></tr>";
				}
			}



			// print_r($consumption);
		}
	}
	$assign_table .= "</tbody></table>";
	// print_r($prod);

	$product_table .= '</table></div>';
	$subsidary_table .= '</table></div>';
	// print_r($product_table); die;
	$data_table = '<div><table id="product_table" class="table table-bordered"><tr>';
	$data_table .= "<th style='white-space: nowrap; padding:0px 20px; vertical-align: middle;'>Cutting ID</th>
										<td style='vertical-align: middle;'><input type='text' name='cutting_id'
												class='form-control to-enter cutting_id' placeholder='Enter Cutting ID' value=" . $cutting_id . " readonly>
										</td>";
	$process = $_POST['pagename'];
	$job_process = $obj->get_rows("`job_process`", "*", "`slug`='$process'");
	$job_permission = json_decode($job_process[0]['permission'] ?? '');

	$where = '';
	if (!empty($job_permission)) {
		$lastKey = array_key_last($job_permission); // Get the last key
		foreach ($job_permission as $key => $job) {
			if ($key == $lastKey) {
				$where .= "`designation`='$job'"; // Do not add 'or' to the last element
			} else {
				$where .= "`designation`='$job' OR "; // Add 'or' for all other elements
			}
		}
	}

	// 	$data_table .= '<th style="white-space: nowrap; padding:20px; vertical-align: middle;"><label>Worker</label></th>';
	// 	$data_table .= '<td style="vertical-align: middle;">
	//     <select name="worker_id[]" id="worker_id" class="form-control to-enter" required>
	//         <option value="" selected disabled>Select Worker</option>';

	// 	$workers = $obj->get_rows("`worker`", "*", $where);

	// 	if (!empty($workers)) {
	// 		foreach ($workers as $workerData) {
	// 			$attendance = $obj->get_rows(
	// 				"`attendance`",
	// 				"*",
	// 				"`worker`=" . (int) $workerData['id'] . " AND `date`='" . date('Y-m-d') . "' AND `status`=1"
	// 			);

	// 			if (!empty($attendance)) {
	// 				$data_table .= '<option value="' . htmlspecialchars($workerData['id']) . '">';
	// 				$data_table .= htmlspecialchars($workerData['name']);
	// 				$data_table .= '</option>';
	// 			}
	// 		}
	// 	}

	// 	$data_table .= '</select>
	// </td>';

	$data_table .= '<th style="white-space: nowrap; padding:0px 20px; vertical-align: middle;">Total Quantity</th>';
	$data_table .= '<td style="vertical-align: middle;"><input type="text" name="total_quantity" id="total_quantity" 
    class="form-control to-enter" placeholder="Enter Total Quantity" value="' . $total_quant . '" readonly>
	<input type="hidden" name="order[]" value="' . $order_id . '"
	</td>';
	// print_r($data_table);
	// $data_table .= '<th style="white-space: nowrap; padding:0px 20px; vertical-align: middle;">Alloted Quantity</th>';
	// $data_table .= '<td style="vertical-align: middle;"><input type="text" name="alloted_quantity" id="alloted_quantity" 
	// class="form-control to-enter" placeholder="Enter Alloted Quantity"> 
	// $data_table .= '<input type="hidden" name="order[]" value="' . $order_id . '"</td>';
	// $data_table .= '<td>';
	$data_table .= '</tr></table>';
	// print_r($ass_id_data);
	$data_table .= $product_table;
	$data_table .= $subsidary_table;
	$data_table .= '<div><h3 style="text-align:center">Assignment List</h3><table id="assign_table" class="table table-bordered"></table></div>';
	// $data_table .= $assign_table;
	echo json_encode(array("data" => $prod, "status" => true, "data_table" => $data_table, "assign_id_data" => $ass_id_data, "assign_table" => $assign_table));
	// print_r($subidary_prods2);
} elseif (isset($_POST['incomplete_process'])) {
	$order_id = $_POST['id'];
	// print_r($order_id);
	$incomplete_process = $_POST['job_process'];
	$order = $obj->get_rows("`sm_order`", "*", "`id` = $order_id");

	$order_product = $obj->get_rows("`order_product`", "*", "`order_id` = $order_id");
	// print_r($order_product);

	foreach ($order_product as $prod) {
		$job_sqn = json_decode($prod['job_squence']);

		$index = 0;
		foreach ($job_sqn as $key => $job) {
			if ($job == $incomplete_process) {
				if (!empty($job_sqn[$key + 1])) {
					$index = $key + 1;
					break;
				}
			}
		}
		// print_r($index); die;
		if ($index != 0) {
			// print_r("hii"); die;
			$order_prod_state = $obj->update("`order_product`", "`order_status` = 1", "`id` = $prod[id] and `curr_job`=$incomplete_process");
			$obj->update("`order_product`", "curr_job = '" . $job_sqn[$index] . "'", "`id` = $prod[id] and `curr_job`=$incomplete_process");

			$order_status = $obj->update("`sm_order`", "order_status = 1", "`id` = $order_id");
			// print_r($order_status);
			// die;
		} else {
			// If no next job is available, keep the current job
			$obj->update("`order_product`", "curr_job = '$incomplete_process'", "`id` = $prod[id] and `curr_job`=$incomplete_process");
			$obj->update("`order_product`", "`order_status` = 3", "`id` = $prod[id] and `curr_job`=$incomplete_process");
			$obj->update("`sm_order`", "order_status = 3", "`id` = $order_id");
		}
	}

	// Fetch the order details
	// $order = $obj->get_rows("`sm_order`", "*", "`id` = $order_id");

	// // Fetch the order product details
	// $order_product = $obj->get_rows("`order_product`", "*", "`order_id` = $order_id");

	// foreach ($order_product as $ord) {
	// 	$job_sqn = json_decode($ord['job_squence']);
	// 	$curr_job = $ord['curr_job'];
	// 	$k1 = -1;

	// 	// Find the index of the current job in the sequence
	// 	foreach ($job_sqn as $key => $sqn) {
	// 		// print_r($sqn);

	// 		if ($sqn == $curr_job) {
	// 			$k1 = $key;
	// 			break;
	// 		}
	// 	}

	// 	// print_r($job_sqn[intval($k1) + 1]);

	// 	// Check if current job is found and the next job exists
	// 	if ($k1 != -1 && isset($job_sqn[$k1 + 1])) {
	// 		$next_job = $job_sqn[$k1 + 1];
	// 		// print_r($incomplete_process); 
	// 		// If the next job is the incomplete process, update to that job
	// 		if ($next_job == $incomplete_process) {
	// 			$obj->update("`order_product`", "curr_job = '$next_job'", "`id` = $ord[id]");
	// 			$order_status = $obj->update("`sm_order`", "order_status = 1", "`id` = $order_id");
	// 			// print_r($order_status);
	// 			// die;
	// 		} else {
	// 			// If no next job is available, keep the current job
	// 			$obj->update("`order_product`", "curr_job = '$curr_job'", "`id` = $ord[id]");
	// 			$obj->update("`sm_order`", "order_status = 3", "`id` = $order_id");
	// 		}
	// 	} else {
	// 		// If no next job, keep the current job as it is
	// 		$obj->update("`order_product`", "curr_job = '$curr_job'", "`id` = $ord[id]");
	// 		$obj->update("`sm_order`", "order_status = 3", "`id` = $order_id");
	// 	}
	// }

	// Update the `sm_order` to reflect the current job


	// Return success response
	echo json_encode(array('status' => true));
} elseif (isset($_POST['delete_assign_data'])) {
	$assign_id = $_POST['assign_id'];
	// print_r($assign_id);
	// print_r($order_id);
	$obj->update("`order_assign_history`", "`status`=0", "`id`=$assign_id");
	echo true;
} elseif (isset($_POST['editdesignation'])) {
	$id = $_POST['id'];
	// print_r($id);
	// $designation = $_POST['designation'];
	$designation = $obj->get_rows("`designation`", "*", "`id`=$id");

	echo json_encode(array('status' => true, 'designation' => $designation[0]));
} elseif (isset($_POST['editProduct'])) {
	$id = $_POST['id'];
	// print_r($id);
	// $designation = $_POST['designation'];
	$product = $obj->get_rows("`product`", "*", "`id`=$id");

	$job = array();

	$job_sqn = json_decode($product[0]['job_squence']);

	foreach ($job_sqn as $jb) {
		$jobsqn = $obj->get_rows("job_process", "*", "`id`=$jb");
		$job[] = $jobsqn[0];
	}
	echo json_encode(array('status' => true, 'product' => $product[0], 'job_seq' => $job));
} elseif (isset($_POST['editPattern'])) {
	$id = $_POST['id'];
	$pattern = $obj->get_rows("`pattern`", "*", "`id`=$id");
	echo json_encode(array('status' => true, 'pattern' => $pattern[0]));
} elseif (isset($_POST['deletedesignation'])) {
	$id = $_POST['id'];
	$obj->delete("`designation`", "`id`=$id");

	echo json_encode(array('status' => true));
} elseif (isset($_POST['deletePattern'])) {
	$id = $_POST['id'];
	$obj->delete("`pattern`", "`id`=$id");

	echo json_encode(array('status' => true));
} elseif (isset($_POST['deleteWidth'])) {
	$id = $_POST['id'];
	$obj->delete("`width`", "`id`=$id");

	echo json_encode(array('status' => true));
} elseif (isset($_POST['deleteProduct'])) {
	$id = $_POST['id'];
	$obj->delete("`product`", "`id`=$id");

	echo json_encode(array('status' => true));
} elseif (isset($_POST['deleteRaw'])) {
	$id = $_POST['id'];
	// print_r($id);
	$obj->delete("`raw_material`", "`id`=$id");

	echo json_encode(array('status' => true));
} elseif (isset($_POST['deleteprocess'])) {
	$id = $_POST['id'];
	// print_r($id);
	$obj->delete("`job_process`", "`id`=$id");

	echo json_encode(array('status' => true));
} elseif (isset($_POST['deletesupplier'])) {
	$id = $_POST['id'];
	// print_r($id); 
	$obj->delete("`supplier`", "`id`=$id");

	echo json_encode(array('status' => true));
} elseif (isset($_POST['deletepayment'])) {
	$id = $_POST['id'];
	// print_r($id); 
	$obj->delete("`payment`", "`id`=$id");

	echo json_encode(array('status' => true));
} elseif (isset($_POST['editsupplier'])) {
	$id = $_POST['id'];
	// print_r($id);
	// $designation = $_POST['designation'];
	$supplier = $obj->get_rows("`supplier`", "*", "`id`=$id");

	echo json_encode(array('status' => true, 'supplier' => $supplier[0]));
} elseif (isset($_POST['get_pattern'])) {
	$id = $_POST['id'];

	$pattern = $obj->get_rows("`pattern`", "*", "`prod_id`=$id and `status`=1");

	$option = '<option value="">Select Pattern</option>';
	foreach ($pattern as $pat) {
		$option .= '<option value="' . $pat['id'] . '">' . $pat['pattern_name'] . '</option>';
	}
	echo json_encode(array('status' => true, 'pattern' => $option));
} elseif (isset($_POST['editprocess'])) {
	$id = $_POST['id'];
	// print_r($id);
	// $designation = $_POST['designation'];
	$process = $obj->get_rows("`job_process`", "*", "`id`=$id");

	echo json_encode(array('status' => true, 'process' => $process[0]));
} elseif (isset($_POST['get_unit_stock'])) {
	$id = $_POST['raw_id'];
	// print_r($id);
	// $designation = $_POST['designation'];
	$process = $obj->get_rows("`raw_material`", "`name`,`unit`", "`id`=$id");

	echo json_encode(array('status' => true, 'process' => $process[0]));
}
