<?php
	class database1{
		private $host='localhost';
		private $db='db_kanodia';
		private $username='root';
		private $password='root';
		private $link;
		
		function __construct(){
			date_default_timezone_set('Asia/Kolkata');
			$this->link=new mysqli($this->host,$this->username,$this->password,$this->db);
		}
		
		function insert($table,$columns,$values){
			$query="INSERT INTO ";
			$query.=$table;$query.=$columns;$query.=" VALUES ".$values;
			
			if($this->link->query($query)){ return true; }
			else{ return mysqli_error($this->link);}
		}
		
		function update($table,$col_values,$where){
			$query="UPDATE ".$table." set ";
			$query.=$col_values." where ".$where;
			//echo $query;
			if($this->link->query($query)){ return true; }
			else{ return mysqli_error($this->link);}
		}
		
		function delete($table,$where=''){
			$query="DELETE from".$table;
			$query.=" where ".$where;
			if($this->link->query($query)){ return true; }
			else{ return mysqli_error($this->link);}
		}
		
		function get_details($table,$column='',$where=''){
			$query="SELECT ";
			if($column!=''){ $query.=$column." from "; }else{ $query.="* from "; }
			$query.=$table;
			if($where!=''){ $query.=" where ".$where; }else{ $query.=" "; }
			// echo  $query."<br>";die;
			$run=$this->link->query($query);
			if($run){
				if($run->num_rows==1){
					return $run->fetch_assoc();
				}
				else{
					return false;	
				}
			}
			else{ return mysqli_error($this->link);}	
		}
		
		function get_count($table,$where='',$field=''){
			$query="SELECT count(";
			if($field!=''){ $query.=$field; }else{$query.="id";}
			$query.=") as count from ".$table;
			if($where!=''){ $query.=" where ".$where; }//echo $query;
			$run=$this->link->query($query);
			if($run){
				$array=$run->fetch_assoc();
				return $array['count'];
			}
			else{ return mysqli_error($this->link);}
		}
		
		function get_rows($table,$column='',$where='',$order='',$limit='',$group=''){
			$query="SELECT ";
			if($column!=''){ $query.=$column." from "; }else{ $query.="* from "; }
			$query.=$table;
			if($where!=''){ $query.=" where ".$where; }else{ $query.=" "; }
			if($group!=''){ $query.=" group by ".$group; }else{ $query.=" "; }
			if($order!=''){ $query.=" order by ".$order; }else{ $query.=" "; }
			if($limit!=''){ $query.=" limit ".$limit; }else{ $query.=" "; }
			// print_r($query);
			$run=$this->link->query($query);
			$result=array();
			if($run){
				if($run->num_rows>0){
					while($rows=$run->fetch_assoc()){
						$result[]=$rows;
					}
					return $result;
				}
				else{
					return false;	
				}
			}
			else{ return mysqli_error($this->link);}	
		}
		
		function login($table,$username,$password){			
			$query="SELECT * from ";
			$query.=$table." where "; 
			$query.="`username`='".$username."' and `password`='$password' and `active`='1'";
			//$query.="(`username` = '".$username."' or `email` = '".$username."') and `password` = '".md5($password)."' and `active` = '1'";
			$run=$this->link->query($query);
			if($run){
				if($run->num_rows==1){
					$array=$run->fetch_assoc();
					return $array;
				}
				else{
					return "Username or Password wrong!!";
				}
			}
			else{
				return mysqli_error($this->link);
			}	
		}
		
		function get_last_row($table,$column='',$where=''){
			$query="SELECT ";
			if($column!=''){ $query.=$column." from "; }else{ $query.="* from "; }
			$query.=$table;
			if($where!=''){ $query.=" where ".$where; }else{ $query.=" "; }
			$query.="order by id desc limit 1";
			$run=$this->link->query($query);
			if($run){
				return $array=$run->fetch_assoc();
			}
			else{ return mysqli_error($this->link);}
		}
		function getDBConnect(){
			return $this->link;
		}
	}
	
	class notowords{
		private $ones=array("","One","Two","Three","Four","Five","Six","Seven","Eight","Nine","Ten",
						"Eleven","Twelve","Thirteen","Fourteen","Fifteen","Sixteen","Seventeen",
						"Eighteen","Nineteen");
		private $tens=array(2=>"Twenty",3=>"Thirty",4=>"Forty",5=>"Fifty",6=>"Sixty",7=>"Seventy",
						8=>"Eighty",9=>"Ninety");
		private $words="";
		private $toreturn="";
		private $inwords="";
		private $inhundred="";
		function get_hundred($number){
	
			$rem=$number%100;
			$value=$number/100;
			if($number>=100){
				if($value<10){
					$this->inhundred.= " ".$this->ones[$value]." Hundred ";
				}
				
			}
			if($rem!=0 && $rem<20){
				$this->inhundred.= " ".$this->ones[$rem];
			}
			if($rem==20)
			{
				$this->inhundred.= " ".$this->tens[2];
			}
			if($rem!=0 && $rem>20){
				$rem2=$rem%10;
				$value2=$rem/10;
				$return =" ".$this->tens[$value2];
				if($rem2!=0){
					$return.=" ".$this->ones[$rem2];
				}
				$this->inhundred.= $return;
			}
			return $this->inhundred;
		}
		function get_thousand($number){
			$rem=$number%1000;
			$value=$number/1000;
			if($number>=1000){
				if($value<20){
					$this->inwords= " ".$this->ones[$value]." Thousand ";
				}
				elseif($value>=20 && $value<100){
					$rem2=$value%10;
					$value2=$value/10;
					$return = $this->tens[$value2];
					if($rem2!=0){
						$return.=" ".$this->ones[$rem2]." Thousand ";
					}
					else{
						$return.=" Thousand ";
					}
					$this->inwords= $return;
				}
			}
			return $this->inwords;
		}
		function get_lakhs($number){
			$rem=$number%100000;
			$value=$number/100000;
			if($number>=100000){
				if($value<20){
					$this->inwords= " ".$this->ones[$value]." Lakh ";
				}
				elseif($value>=20 && $value<100){
					$rem2=$value%10;
					$value2=$value/10;
					$return = $this->tens[$value2];
					if($rem2!=0){
						$return.=" ".$this->ones[$rem2]." Lakh ";
					}
					else{
						$return.=" Lakh ";
					}
					$this->inwords= $return;
				}
			}
			return $this->inwords;
		}
		function get_crore($number){
			$rem=$number%10000000;
			$value=$number/10000000;
			if($number>=10000000){
				if($value<20){
					$this->inwords= " ".$this->ones[$value]." Crore ";
				}
				elseif($value>=20 && $value<100){
					$rem2=$value%10;
					$value2=$value/10;
					$return = $this->tens[$value2];
					if($rem2!=0){
						$return.=" ".$this->ones[$rem2]." Crore ";
					}
					else{
						$return.=" Crore";
					}
					$this->inwords= $return;
				}
			}
			return $this->inwords;
		}
	
		function to_words($number){
			if($number<1000000000){
				$this->words.= $this->get_crore($number)." ".$this->get_lakhs($number%10000000)." ";$this->words="";
				$this->words.= $this->get_thousand($number%100000)." ".$this->get_hundred($number%1000);
			}
			$this->toreturn=$this->words;
			$this->words=""; $this->inwords="";$this->inhundred="";
			return $this->toreturn;
		}
	}
	
	function toDecimal($number){
		$amount=number_format((float)$number,2,'.','');
		$array=explode('.',$amount);
		$arr=str_split($array[0],1);
		$length=sizeof($arr);
		$amt="";
		if($length>3 && $arr[0]!='-'){
			switch($length){
				case 4:	for($i=0;$i<$length;$i++){
							$amt.=$arr[$i];
							if($i==0){
							$amt.=",";
							}
						}
				break;
				case 5:	for($i=0;$i<$length;$i++){
							$amt.=$arr[$i];
							if($i==1){
							$amt.=",";
							}
						}
				break;
				case 6:	for($i=0;$i<$length;$i++){
							$amt.=$arr[$i];
							if($i==0 || $i==2){
							$amt.=",";
							}
						}	
				break;
				case 7:	for($i=0;$i<$length;$i++){
							$amt.=$arr[$i];
							if($i==1 || $i==3){
							$amt.=",";
							}
						}
				break;
				case 8:	for($i=0;$i<$length;$i++){
							$amt.=$arr[$i];
							if($i==0 || $i==2 || $i==4){
							$amt.=",";
							}
						}
				break;
				case 9:	for($i=0;$i<$length;$i++){
							$amt.=$arr[$i];
							if($i==1 || $i==3 || $i==5){
							$amt.=",";
							}
						}
				break;
				case 10:	for($i=0;$i<$length;$i++){
							$amt.=$arr[$i];
							if($i==0 || $i==2 || $i==4 || $i==6){
							$amt.=",";
							}
						}
				break;
				case 11:	for($i=0;$i<$length;$i++){
							$amt.=$arr[$i];
							if($i==1 || $i==3 || $i==5 || $i==7){
							$amt.=",";
							}
						}
				break;
				case 12:	for($i=0;$i<$length;$i++){
							$amt.=$arr[$i];
							if($i==0 || $i==2 || $i==4 || $i==6 || $i==8){
							$amt.=",";
							}
						}
				break;
			}
		}
		else{
			$amt=$array[0];	
		}
		return $amt.'.'.$array[1];
	}
	function twoDigits($number){
		return number_format((float)$number,2,'.','');
	}
	function cleanStr($string){
		return filter_var($string, FILTER_SANITIZE_STRING);	
	}
	function cleanEmail($email){
		return filter_var($email, FILTER_SANITIZE_EMAIL);
	}
?>