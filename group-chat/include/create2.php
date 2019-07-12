<div class='paTRBL10' style='overflow:hidden'>
	<form method="POST" action="">
		<div class='paB5'>
			<?php if ($error["portname"]["error"]){ ?> <div class="block paTRBL3 errortext"><?php echo $error["portname"]["error"];?></div>	<?php } ?>
			<input type="text" name="portname" value="<?php echo $portname; ?>" placeholder="Port Name" class='<?php echo $error["portname"]["class"];?> block'>
		</div>
		<div class='paTB5'>
			<input id='usepassword' type='checkbox' name='usepassword' value='yes' onchange='togglepassword()' <?php if($usepassword) echo 'checked';?>><label for='usepassword'>Protect Using Password</label>
			<div id='passwordcontainer' class='paB10' style='display:none'>	
				<?php if ($error["password"]["error"]){ ?><div class="block paTRBL3 errortext"><?php echo $error["password"]["error"];?></div><?php } ?>
				<input id='password' type="password" name="password" value="<?php echo $password; ?>" placeholder="Password" class='<?php echo $error["password"]["class"];?> block'>
									<input id='repassword' type="password" name="repassword" value="<?php echo $repassword; ?>" placeholder="Retype Password"  class='<?php echo $error["repassword"]["class"];?> block'>
			</div>
			<script>
				togglepassword();
				function togglepassword(){
					if (document.getElementById('usepassword').checked)
						document.getElementById('passwordcontainer').style.display='block';
					else{
						document.getElementById('passwordcontainer').style.display='none';
						document.getElementById('password').value='';
						document.getElementById('repassword').value='';
					}
				}
			</script>
			
		</div>
		<div class='paTB5'>
			<?php if ($error["pin"]["error"]){ ?> <div class="block paTRBL3 errortext"><?php echo $error["pin"]["error"];?></div>	<?php } ?>
			<input type="number" name="pin" value="<?php echo $pin; ?>" placeholder="Pin(optional, required to edit)"  class='<?php echo $error["pin"]["class"];?> block' style='width:100%;'>
		</div>	
		<div class='paTB5'>			
			<?php if ($error["username"]["error"]){ ?><div class="block paTRBL3 errortext"><?php echo $error["username"]["error"];?></div> <?php } ?>
			<input type="text" name="username" value="<?php echo $username; ?>" placeholder="Display Name"  class='<?php echo $error["username"]["class"];?> block'>
		</div>
		<div class='paTB5'>
			<?php if ($error["capvalue"]["error"]){ ?><div class="block paTRBL3 errortext" style="width:245px"><?php echo $error["capvalue"]["error"];?></div>	<?php } ?>		
			<center>
			<table id="capbox" class='<?php echo $error["capvalue"]["class"];?>'>
				<tr>
					<td><img id='capimage' src='/cap/cap.php'></td>
					<td><img id="caprefresh" title='Refresh' src='/images/refresh.png' width="50" height="50" onclick="document.getElementById('capimage').src='/cap/cap.php?+Math.random()'; document.getElementById('capvalue').value='';"></td>
				</tr>
				<tr>
					<td colspan=2><input id='capvalue' class='block maTRBL0' style='width:100%;' type='text' name='capvalue' placeholder='Type the above text here!' autocomplete='off'>
					</td>
				</tr>
			</table>
			</center>
		</div>
		
		<input type="submit" name="create" value="Create">
	</form>
</div>