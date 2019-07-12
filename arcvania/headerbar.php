<div id="headercover">
	<div id="header">
	<?php
		if(varsetcheck($_SESSION["signedin"])==0){
	?>
		<div class="headerelement">
			<a href="index.php">
				<div class="headerelementinside">
					<img class="headericon" title="Home" src="images/home.png" alt="Home" width="50px" height="30px">
				</div>
			</a>
		</div>
		<div class="headerelement">
			<a href="wut.php">
				<img class="headericon" title="About" src="images/about.png" alt="About" width="50px" height="30px">
			</a>
		</div>
	<?php
		}
		else{
	?>
		<div class="headerelement">
			<a href="index.php">
				<img class="headericon" title="Home" src="images/home.png" alt="Home" width="50px" height="30px">
			</a>
		</div>
		<div class="headerelement">
			<a href="msg.php">
				<img class="headericon" title="Messages" src="images/friends.png" alt="Message" width="50px" height="30px">
			</a>
		</div>
		<div class="headerelement">
			<a href="setpro.php">
				<img class="headericon" title="Set Profile" src="images/user.png" alt="Set Profile" width="50px" height="30px">
			</a>
		</div>
		<div class="headerelement">
			<a href="signout.php">
				<img class="headericon" title="Sign Out" src="images/about.png" alt="Sign Out" width="50px" height="30px">
			</a>
		</div>

		<div class="headerelement" style="float:right; width: 260px; text-align: left; border-bottom-left-radius:10px; border-bottom-right-radius: 10px;">
			<label for="search">
				<img class="headericon" title="Search" src="images/search.png" alt="Find People" style="display:inline-block;float:left;">
				<form action="search.php" method="GET">
					<input id="search" type="text" name="searchid" placeholder="Search" style="width:auto;" value="<?php echo varsetcheck($_GET['searchid'])?>">
				</form>
			</label>
		</div>
	<?php
		}
	?>
	</div>	
</div>