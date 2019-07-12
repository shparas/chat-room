<?php
	if(file_exists('dbinfo.txt')){
		$string=explode("~_",file_get_contents('dbinfo.txt'));			
		$dbservername=$string[0];
		$dbuser=$string[1];
		$dbpass=$string[2];
		$dbname=$string[3];
		if ($dbservername==''){
			include ('dbmanager.php');
			exit();
		}
	} else {
		include ('dbmanager.php');
		exit();
	}
	
	$GLOBALS["servername"]=$dbservername; $GLOBALS["usr"]=$dbuser;	$GLOBALS["pass"]=$dbpass;	$GLOBALS["dbname"]=$dbname;

	function connection(&$dbname, &$dbservername, &$dbuser, &$dbpass){	//sets 4 parameters if not set already
		if ($dbservername==NULL){$dbservername=$GLOBALS["servername"];}
		if ($dbuser==NULL){$dbuser=$GLOBALS["usr"];}
		if ($dbpass==NULL){$dbpass=$GLOBALS["pass"];}
		if ($dbname==NULL){$dbname=$GLOBALS["dbname"];}
	}

	function mysqlsafe($string){	//makes the input safe; under construction
		if (get_magic_quotes_gpc()){
			$string = stripslashes($string);
		}
		return mysqli_real_escape_string($string, "'");
	}

	function databasequery		($sql, $dbname=NULL, $dbservername=NULL, $dbuser=NULL, $dbpass=NULL){	//to create, delete database
		connection($dbname, $dbservername, $dbuser, $dbpass);
		try {
			$conn = new PDO("mysql:host=$dbservername", $dbuser, $dbpass);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			// set the PDO error mode to exception
			$conn->exec($sql);														// use exec() because no results are returned
			return array(1,$sql,"Query executed successfully!");
		}
		catch(PDOException $e){
			return array(0,$sql,$e->getMessage());
		}
		$conn = null;
	}

	function singlequery		($sql, $dbname=NULL, $dbservername=NULL, $dbuser=NULL, $dbpass=NULL){	//to create/drop table, insert data, delete data
		connection($dbname, $dbservername, $dbuser, $dbpass);
		try {
			$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbuser, $dbpass);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->exec($sql);
			return array(1,$sql,"Query executed successfully!");
		}
		catch(PDOException $e){
			return array(0,$sql,$e->getMessage());
		}
		$conn = null;
	}

	function multiplequery		(	   $dbname=NULL, $dbservername=NULL, $dbuser=NULL, $dbpass=NULL){	//send the sqls as remaining parameters
		connection($dbname, $dbservername, $dbuser, $dbpass);
		try {
			$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbuser, $dbpass);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->beginTransaction();		// begin the transaction
			$sql = func_get_args();			//get all arguments of function;
			//for ($i=0; $i<count($sql)-4; $i++) {
			for ($i=4; $i<count($sql); $i++) {
				$conn->exec($sql[$i]);
				$sqls=varsetcheck($sqls).$sql[$i]."~*~";
			}
			$conn->commit();		// commit the transaction
			return array(1,$sqls,"Queries executed successfully!");
		}
		catch(PDOException $e){
			$conn->rollback();		// roll back the transaction if something failed
			return array(0,$sqls,$e->getMessage());
		}
		$conn = null;
	}

	function insertrecord		($sql, $dbname=NULL, $dbservername=NULL, $dbuser=NULL, $dbpass=NULL){
		connection($dbname, $dbservername, $dbuser, $dbpass);
		try {
			$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbuser, $dbpass);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->exec($sql);
			$last_id = $conn->lastInsertId();
			return array(1,$sql,"Record added successfully and its id is: $last_id");
		}
		catch(PDOException $e){
			return array(0,$sql,$e->getMessage());
		}
		$conn = null;
	}

	function updaterecord		($sql, $dbname=NULL, $dbservername=NULL, $dbuser=NULL, $dbpass=NULL){
		connection($dbname, $dbservername, $dbuser, $dbpass);
		try {
			$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbuser, $dbpass);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare($sql);		// prepare the query
			$stmt->execute();					// execute the query
			$records=$stmt->rowCount();
			return array(1,$sql,"$records successfully updated!");
		}
		catch(PDOException $e){
			return array(0,$sql,$e->getMessage());
		}
		$conn = null;	
	}

	function selectrecord		($sql, $dbname=NULL, $dbservername=NULL, $dbuser=NULL, $dbpass=NULL){	//get by $result[NO OF ROW][NAME OF ROW]; eg $result[1][id];
		connection($dbname, $dbservername, $dbuser, $dbpass);
		$conn = mysqli_connect($dbservername, $dbuser, $dbpass, $dbname);
		$records=array();
		if (!$conn) {
			$records[0]=array(0,$sql,"Failed to connect to the database!");
		}
		$result = mysqli_query($conn, $sql);
		
		if (mysqli_num_rows($result) > 0) {		//gives error as "...expects parameter 1 to..." if $sql is wrong
			while($row = mysqli_fetch_assoc($result)) {
				$records[0]=array(1,$sql,"Records found!");
				$records[]=$row;
			}
		}
		else{
			$records[0]=array(0,$sql,"No Records found!");
		}
		mysqli_close($conn);
		return $records;
	}

	function preparedstatement	($sql, $dbname=NULL, $dbservername=NULL, $dbuser=NULL, $dbpass=NULL){
		connection($dbname, $dbservername, $dbuser, $dbpass);
		try {
			$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbuser, $dbpass);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// prepare sql and bind parameters
			$stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email) 
				VALUES (:firstname, :lastname, :email)");
			$stmt->bindParam(':firstname', $firstname);
			$stmt->bindParam(':lastname', $lastname);
			$stmt->bindParam(':email', $email);
			
			// insert a row
			$firstname = "John";
			$lastname = "Doe";
			$email = "john@example.com";
			$stmt->execute();
		
			// insert another row
			$firstname = "Mary";
			$lastname = "Moe";
			$email = "mary@example.com";
			$stmt->execute();
		
			// insert another row
			$firstname = "Julie";
			$lastname = "Dooley";
			$email = "julie@example.com";
			$stmt->execute();
			
			echo "New records created successfully";
			}
		catch(PDOException $e){
			echo "Error: " . $e->getMessage();
		}
		$conn = null;
	}
	
	function sqlcodelist(){
	//$sql = "CREATE DATABASE newshore";
	/*	$sql = "CREATE TABLE Users (
			id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			fname VARCHAR(30) NOT NULL,
			lname VARCHAR(30) NOT NULL,
			email VARCHAR(50) NOT NULL,
			country VARCHAR(50) NOT NULL,
			dob VARCHAR(50) NOT NULL,
			sex VARCHAR(50) NOT NULL,
			usr VARCHAR(50) NOT NULL,
			pass VARCHAR(50) NOT NULL,
			reg_date TIMESTAMP NOT NULL,
			UNIQUE (id),
			UNIQUE (email),
			UNIQUE (usr)
			)";
	*/
	//	$sql = "INSERT INTO users (fname, lname, email, country, dob, sex, usr, pass) 
	//		VALUES ('Paras', 'Sharma', 'shps23@live.com', '1997/12/4', 'male', 'shps23', 'hahaha')";
	//	$sql = "SELECT id, fname, lname FROM users WHERE usr='shps23'";
	//	$sql = "DELETE FROM users WHERE usr='shps23'";
	//	$sql = "UPDATE users SET fname='Prabesh', lname='sharma' WHERE usr='shps23'";
	//	$sql = "SELECT * FROM users LIMIT 30";				//returns 1-30 (incusive) records
	//	$sql = "SELECT * FROM users LIMIT 10 OFFSET 0";	//returns 10 records start from 16 (offset 15)
	//	$sql = "SELECT * FROM users LIMIT 15, 10";			//same but shortcut
	}

	function firsttimedb(){
		$dbname=$GLOBALS["dbname"];
		
		$sql = "CREATE DATABASE $dbname";
		$result=databasequery($sql);
		if ($result[0]!==1){
			echo $result[2];
		}
		
		$sql = "CREATE TABLE _ipstore (
			id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			ip VARCHAR(15),
			date INT(10),
			page VARCHAR(500),
			roomname VARCHAR(100),
			usr VARCHAR(100),
			UNIQUE (id)
			)";
		$result=singlequery($sql);
		
		$sql = "CREATE TABLE _images (
			id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			userid INT(15),
			username VARCHAR(200),
			type VARCHAR(20),
			filetype VARCHAR(20),
			date INT(10),
			allow VARCHAR(100),
			UNIQUE (id)
			)";
		$result=singlequery($sql);
		
		$sql = "CREATE TABLE _files (
			id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			name VARCHAR(20),
			originalname VARCHAR(200),
			extension VARCHAR(30),
			description VARCHAR(200),
			type VARCHAR(20),
			upfrom VARCHAR(200),
			upfrom2 VARCHAR(200),
			allow VARCHAR(100),
			block VARCHAR(200),
			UNIQUE (id)
			)";
		$result=singlequery($sql);
		
		$sql = "CREATE TABLE _ports(
			id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			portname VARCHAR(100),
			password VARCHAR(100),
			pin INT(10),
			date INT(10),
			expdate INT(10),
			type VARCHAR(50),
			status VARCHAR(50),
			UNIQUE (id)
			)";
		$result=singlequery($sql);
	}
	//firsttimedb();
	
?>