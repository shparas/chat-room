<?php
//has a block of 300px for sign up purpose.
//lets begin the code
	//preventing the emptiness of variable
	$fname=trim(varsetcheck($_POST["fname"]));
	$lname=trim(varsetcheck($_POST["lname"]));
	$email=trim(strtolower(varsetcheck($_POST["email"])));
	$reemail=trim(strtolower(varsetcheck($_POST["reemail"])));
	$country=varsetcheck($_POST["country"]);
	$month=varsetcheck($_POST["month"]);
	$day=varsetcheck($_POST["day"]);
	$year=varsetcheck($_POST["year"]);
	$sex=varsetcheck($_POST["sex"]);
	$usrname=trim(strtolower(varsetcheck($_POST["usrname"])));
	$uniqueid=varsetcheck($_POST["uniqueid"], md5(uniqid("hello",true)));
	$pswd=varsetcheck($_POST["pswd"]);
	$capvalue=varsetcheck($_POST["capvalue"]);
	$agree=varsetcheck($_POST["agree"]);
	$signup=varsetcheck($_POST["signup"]);
	//preventing the emptiness of error variable
	varsetcheck($error["fname"]["class"]);	varsetcheck($error["fname"]["error"]);
	varsetcheck($error["lname"]["class"]);	varsetcheck($error["lname"]["error"]);
	varsetcheck($error["email"]["class"]);	varsetcheck($error["email"]["error"]);
	varsetcheck($error["reemail"]["class"]);varsetcheck($error["reemail"]["error"]);
	varsetcheck($error["country"]["class"]);varsetcheck($error["country"]["error"]);
	varsetcheck($error["dob"]["class"]);	varsetcheck($error["dob"]["error"]);
	varsetcheck($error["sex"]["class"]);	varsetcheck($error["sex"]["error"]);
	varsetcheck($error["usrname"]["class"]);varsetcheck($error["usrname"]["error"]);
	varsetcheck($error["pswd"]["class"]); 	varsetcheck($error["pswd"]["error"]);
	varsetcheck($error["capvalue"]["class"]); 	varsetcheck($error["capvalue"]["error"]);
	varsetcheck($error["agree"]["error"]);
	
	//executing if signed up
	if ($signup=="Sign Up!"){
		if(($error["fname"]["error"]=validate_fname($fname))!==true){
			$error["fname"]["class"]="formerror";
			$skip=1;
		}
		if(($error["lname"]["error"]=validate_lname($lname))!==true){
			$error["lname"]["class"]="formerror";
			$skip=1;
		}
		if(($error["email"]["error"]=validate_email($email))!==true){
			$error["email"]["class"]="formerror";
			$skip=1;
		}else{
			$records = selectrecord("SELECT id FROM users WHERE email='$email'");
			if($records[0][0]==1) {
				$error["email"]["error"]="Email already registered, try a different one!";
				$error["email"]["class"]="formerror";
				$skip=1;
			}
		}
		if(($error["reemail"]["error"]=validate_email($reemail))!==true){
			$error["reemail"]["class"]="formerror";
			$skip=1;
		}else if($email!=$reemail){
			$error["reemail"]["class"]="formerror";
			$error["reemail"]["error"]="Ops! Email address didn't match!";
			$skip=1;
		}
		if($country==""){
			$error["country"]["class"]="formerror";
			$error["country"]["error"]="You've to specify your country/region!";
			$skip=1;
		}
		if(strlen($month)<3 || !($day>=1 && $day<=31) || !($year>1900 && $year<2015)){
			$error["dob"]["class"]="formerror";
			$error["dob"]["error"]="Please enter a valid date of birth!";
			$skip=1;
		}
		if($sex==""){
			$error["sex"]["class"]="formerror";
			$error["sex"]["error"]="You've to specify your sex!";
			$skip=1;
		}
		if(($error["usrname"]["error"]=validate_usrname($usrname))!==true){
			$error["usrname"]["class"]="formerror";
			$skip=1;
		}else{
			$records = selectrecord("SELECT id FROM users WHERE username='$usrname'");
			if($records[0][0]==1) {
				$error["usrname"]["error"]="Username already registered, try a new one!";
				$error["usrname"]["class"]="formerror";
				$skip=1;
			}
			$records = selectrecord("SELECT id FROM oldusernames WHERE username='$usrname'");
			if($records[0][0]==1) {
				$error["usrname"]["error"]="Username already registered, try a new one!";
				$error["usrname"]["class"]="formerror";
				$skip=1;
			}
		}
		if(($error["pswd"]["error"]=validate_pswd($pswd))!==true){
			$error["pswd"]["class"]="formerror";
			$skip=1;
		}
		if(($error["capvalue"]["error"]=validate_captcha($capvalue))!==true){
			$error["capvalue"]["class"]="formerror";
			$skip=1;
		}
		if ($agree!="yes"){
			$error["agree"]["error"]="You have to agree to our terms and policies!";
			$skip=1;
		}

		//remove all those 1 got from the validation check
		ifchange($error["fname"]["error"],true,"",true);
		ifchange($error["lname"]["error"],true,"",true);
		ifchange($error["email"]["error"],true,"",true);
		ifchange($error["reemail"]["error"],true,"",true);
		ifchange($error["usrname"]["error"],true,"",true);
		ifchange($error["pswd"]["error"],true,"",true);
		ifchange($error["capvalue"]["error"],true,"",true);
		
		if(!isset($skip)){			//if everything's great
			$timenow=time();
			$sql = "INSERT INTO users (fname, lname, email, country, dob, sex, username, password, type, status, reg_date) 
				VALUES ('$fname', '$lname', '$email', '$country', '$month/$day/$year', '$sex', '$usrname', '$pswd', 'user', 'inactive', $timenow)";
			$status_raw=insertrecord($sql);
			
			mkdir("$usrname");
			mkdir("$usrname/message");
			
			$_SESSION["id"]=substr($status_raw[2],strlen("Record added successfully and its id is: "));
			$_SESSION["fname"]=$fname;
			$_SESSION["lname"]=$lname;
			$_SESSION["username"]=$usrname;
			$_SESSION["email"]=$email;
			$_SESSION["country"]=$country;
			$_SESSION["signedin"]=1;
			$_SESSION["userstat"]="email_not_activated";
			$_SESSION['captcha']="";
			$id=$_SESSION["id"];			
			$sql="INSERT INTO lastonline (id, lastonline) VALUES ($id, $timenow)";
			$status_raw=insertrecord($sql);
			
			exit(header("Location: /setpro.php")); 
		}
	}
?>
	<div class='paTRBL10' style='overflow:hidden'>
		<form id="signup" method="POST" action="<?php echo htmlspecialchars(str_ireplace("index.php","",$_SERVER["PHP_SELF"]));?>">
			<div class='block' style='text-decoration:italic;font-family:comic;font-size:25px;color:rgb(0,255,0);'>Lets get you signed up!</div>
			<div class='paTB10'>
				<?php if ($error["fname"]["error"]){ ?> <div class="block paTRBL3 errortext"><?php echo $error["fname"]["error"];?></div>	<?php } ?>
				<input type="text" name="fname" value="<?php echo $fname; ?>" placeholder="First Name" class='<?php echo $error["fname"]["class"];?> block'>
				
				<?php if ($error["lname"]["error"]){ ?><div class="block paTRBL3 errortext"><?php echo $error["lname"]["error"];?></div><?php } ?>
				<input type="text" name="lname" value="<?php echo $lname; ?>" placeholder="Last Name"  class='<?php echo $error["lname"]["class"];?> block'>
			</div>		
			<hr class='break'>
			
			<div class='paTB10'>	
				<?php if ($error["sex"]["error"]){ ?><div class="block paTRBL3 errortext"><?php echo $error["sex"]["error"];?></div>	<?php } ?>
				<label for="male">Male:</label><input id="male" type="radio" name="sex" value="male" <?php echo ($sex=="male"?"checked":""); ?>> 
				&nbsp;&nbsp;<label for="female">Female:</label><input id="female" type="radio" name="sex" value="female" <?php echo ($sex=="female"?"checked":""); ?>>
			</div>
			<hr class='break'>	
			
			<div class='paTB10'>
				<?php if ($error["dob"]["error"]){ ?><div class="block paTRBL3 errortext"><?php echo $error["dob"]["error"];?></div>	<?php } ?>
				<table id="dob"  class='<?php echo $error["dob"]["class"];?>' style='width:100%'>
				<tr>
					<td>
					<select name="month">
						<option value="month">Month</option>
						<?php 
							$mons = array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");
							for ($i=1; $i<=12; $i++){
								echo "<option value='".$mons[$i]."'".($mons[$i]==$month?" selected":"").">".$mons[$i]."</option>";
							}
							unset ($mons);
						?>
					</select>
					</td><td>-</td>
					<td>
					<select name="day">
						<option value="day">Day</option>
						<?php 
							for ($i=1; $i<=31; $i++){
								echo "<option value=$i".($day==$i?" selected":"").">$i</option>";
							}
						?>
					</select>
					</td><td>-</td>
					<td>
					<select name="year">
						<option value="year">Year</option>
						<?php 
							for ($i=2015; $i>=1900; $i--){
								echo "<option value=$i".($year==$i?" selected":"").">$i</option>";
							}
						?>
					</select>
					</td>
				</tr>
				</table>
			</div>
			<hr class='break'>
			
			<div class='paTB10'>	
				<?php if ($error["country"]["error"]){ ?><div class="block paTRBL3 errortext"><?php echo $error["country"]["error"];?></div><?php } ?>
				<select name="country" value="algeria" class='<?php echo $error["country"]["class"];?> block'>
					<option value=""><b>Select your country/region</b></option>
					<optgroup label="Africa-54">
						<?php
							$countrylist=explode(";", "Algeria;Angola;Benin;Botswana;Burkina;Burundi;Cameroon;Cape Verde;Central African Republic;Chad;Comoros;Congo;Congo, Democratic Republic of;Djibouti;Egypt;Equatorial Guinea;Eritrea;Ethiopia;Gabon;Gambia;Ghana;Guinea;Guinea-Bissau;Ivory Coast;Kenya;Lesotho;Liberia;Libya;Madagascar;Malawi;Mali;Mauritania;Mauritius;Morocco;Mozambique;Namibia;Niger;Nigeria;Rwanda;Sao Tome and Principe;Senegal;Seychelles;Sierra Leone;Somalia;South Africa;South Sudan;Sudan;Swaziland;Tanzania;Togo;Tunisia;Uganda;Zambia;Zimbabwe");
							for($i=0;$i<count($countrylist);$i++){
								echo "<option value='".$countrylist[$i]."'".($countrylist[$i]==$country?" selected":"").">".$countrylist[$i]."</option>";
							}
						?>
					</optgroup>
					<optgroup label="Asia-44">
						<?php
							$countrylist=explode(";", "Afghanistan;Bahrain;Bangladesh;Bhutan;Brunei;Burma (Myanmar);Cambodia;China;East Timor;India;Indonesia;Iran;Iraq;Israel;Japan;Jordan;Kazakhstan;Korea, North;Korea, South;Kuwait;Kyrgyzstan;Laos;Lebanon;Malaysia;Maldives;Mongolia;Nepal;Oman;Pakistan;Philippines;Qatar;Russian Federation;Saudi Arabia;Singapore;Sri Lanka;Syria;Tajikistan;Thailand;Turkey;Turkmenistan;United Arab Emirates;Uzbekistan;Vietnam;Yemen");
							for($i=0;$i<count($countrylist);$i++){
								echo "<option value='".$countrylist[$i]."'".($countrylist[$i]==$country?" selected":"").">".$countrylist[$i]."</option>";
							}
						?>
					</optgroup>
					<optgroup label="Australia and Ocenia-14">
						<?php
							$countrylist=explode(";", "Australia;Fiji;Kiribati;Marshall Islands;Micronesia;Nauru;New Zealand;Palau;Papua New Guinea;Samoa;Solomon Islands;Tonga;Tuvalu;Vanuatu");
							for($i=0;$i<count($countrylist);$i++){
								echo "<option value='".$countrylist[$i]."'".($countrylist[$i]==$country?" selected":"").">".$countrylist[$i]."</option>";
							}
						?>
					</optgroup>
					<optgroup label="Europe-47">
						<?php
							$countrylist=explode(";", "Albania;Andorra;Armenia;Austria;Azerbaijan;Belarus;Belgium;Bosnia and Herzegovina;Bulgaria;Croatia;Cyprus;Czech Republic;Denmark;Estonia;Finland;France;Georgia;Germany;Greece;Hungary;Iceland;Ireland;Italy;Latvia;Liechtenstein;Lithuania;Luxembourg;Macedonia;Malta;Moldova;Monaco;Montenegro;Netherlands;Norway;Poland;Portugal;Romania;San Marino;Serbia;Slovakia;Slovenia;Spain;Sweden;Switzerland;Ukraine;United Kingdom;Vatican City");
							for($i=0;$i<count($countrylist);$i++){
								echo "<option value='".$countrylist[$i]."'".($countrylist[$i]==$country?" selected":"").">".$countrylist[$i]."</option>";
							}
						?>
					</optgroup>
					<optgroup label="North America-23">
						<?php
							$countrylist=explode(";", "Antigua and Barbuda;Bahamas;Barbados;Belize;Canada;Costa Rica;Cuba;Dominica;Dominican Republic;El Salvador;Grenada;Guatemala;Haiti;Honduras;Jamaica;Mexico;Nicaragua;Panama;Saint Kitts and Nevis;Saint Lucia;Saint Vincent and the Grenadines;Trinidad and Tobago;United States");
							for($i=0;$i<count($countrylist);$i++){
								echo "<option value='".$countrylist[$i]."'".($countrylist[$i]==$country?" selected":"").">".$countrylist[$i]."</option>";
							}
						?>
					</optgroup>
					<optgroup label="South America-12">
						<?php
							$countrylist=explode(";", "Argentina;Bolivia;Brazil;Chile;Colombia;Ecuador;Guyana;Paraguay;Peru;Suriname;Uruguay;Venezuela");
							for($i=0;$i<count($countrylist);$i++){
								echo "<option value='".$countrylist[$i]."'".($countrylist[$i]==$country?" selected":"").">".$countrylist[$i]."</option>";
							}
						?>
						

					</optgroup>
				</select>
				
			</div>
			<hr class='break'>
			
			<div class='paTB10'>
				<?php if ($error["email"]["error"]){ ?> <div class="block paTRBL3 errortext"><?php echo $error["email"]["error"];?></div>	<?php } ?>
				<input type="email" name="email" value="<?php echo $email; ?>" placeholder="Email"  class='<?php echo $error["email"]["class"];?> block'>
				
				<?php if ($error["reemail"]["error"]){ ?><div class="block paTRBL3 errortext"><?php echo $error["reemail"]["error"];?></div>	<?php } ?>
				<input type="email" name="reemail" value="<?php echo $reemail; ?>" placeholder="Reenter Email"  class='<?php echo $error["reemail"]["class"];?> block'>
			</div>	
			<hr class='break'>
			
			<div class='paTB10'>			
				<?php if ($error["usrname"]["error"]){ ?><div class="block paTRBL3 errortext"><?php echo $error["usrname"]["error"];?></div> <?php } ?>
				<input type="text" name="usrname" value="<?php echo $usrname; ?>" placeholder="Username"  class='<?php echo $error["usrname"]["class"];?> block'>
				<?php if ($error["pswd"]["error"]){ ?> <div class="block paTRBL3 errortext"><?php echo $error["pswd"]["error"];?></div>	<?php } ?>	
				<input type="password" name="pswd" value="<?php echo $pswd; ?>" placeholder="Password" class='<?php echo $error["pswd"]["class"];?> block'>
				
			</div>
			<hr class='break'>
			
			<div class='paTB10'>
				<?php if ($error["capvalue"]["error"]){ ?><div class="block paTRBL3 errortext" style="width:245px"><?php echo $error["capvalue"]["error"];?></div>	<?php } ?>		
				<center>
				<table id="capbox" class='<?php echo $error["capvalue"]["class"];?>'>
					<tr>
						<td><img id='capimage' src='cap/cap.php'></td>
						<td><img id="caprefresh" title='Refresh' src='images/refresh.png' width="50" height="50" onclick="document.getElementById('capimage').src='cap/cap.php?+Math.random()'; document.getElementById('capvalue').value='';"></td>
					</tr>
					<tr>
						<td colspan=2><input id='capvalue' class='block maTRBL0' style='width:100%;' type='text' name='capvalue' placeholder='Type the above text here!' autocomplete='off'>
						</td>
					</tr>
				</table>
				</center>
			</div>
			<hr class='break'>
			
			<div class='paTB10'>	
				<div id='policyaccept' class='<?php if($error["agree"]["error"]) echo "formerror"; ?> block paTRBL5'>
					<input id="agree" type="checkbox" name="agree" value="yes" <?php echo ($agree=="yes"?"checked":"");?>>
					<label for="agree">I've read the <a href='/tou.php' target='_blank'>terms of use</a> and accept to join ArcVania.</label><?php if ($error["agree"]["error"]){ ?> <div class="block paTRBL3 errortext"><?php echo $error["agree"]["error"];?></div><?php } ?>
				</div>
			</div>				
			<input type="submit" name="signup" value="Sign Up!">
		</form>
	</div>