<div class='paTRBL10' style='overflow:hidden'>
	
	<form id="signin" method="POST" action="">
		<?php if (varsetcheck($error["1"]["1"])){ ?> <div class="errortext block paTRBL3"><?php echo $error["1"]["1"];?></div>	<?php } ?>
		<input type="text" name="pname" value="<?php echo varsetcheck($pname);?>" placeholder="Port Name" autofocus class='<?php echo varsetcheck($error["pname"]["class"]);?>  block paTRBL5 maB10'>
		<input type="password" name="pword" value="<?php echo varsetcheck ($pword);?>" placeholder="Password(if any)" class='<?php echo varsetcheck($error["pword"]["class"]);?> block paTRBL5 maB10'>
		
		<?php if (varsetcheck($error["1"]["2"])){ ?> <div class="errortext block paTRBL3"><?php echo $error["1"]["2"];?></div>	<?php } ?>				
		<input type="text" name="uname" value="<?php echo varsetcheck($uname);?>" placeholder="Display Name" class='<?php if(varsetcheck($error["1"]["2"])) echo "formerror ";?>block paTRBL5 maB10'>

		<input class='block' type="submit" name="join" value="Lets Join">
		
	</form>
	
</div>