<?php
	$servername="localhost"; $username="root"; $password="Iw2aMsrn."; $dbname="arcvania;port=3308"; $dbname_2="arcvania";
	$servername_2="localhost:3308";

	$GLOBALS["servername"]=$servername; $GLOBALS["username"]=$username;	$GLOBALS["password"]=$password;	$GLOBALS["dbname"]=$dbname;
	$GLOBALS["servername_2"]=$servername_2;
	$GLOBALS["dbname_2"]=$dbname_2;

	function connection(&$dbname, &$servername, &$username, &$password){	//sets 4 parameters if not set already
		if ($servername==NULL){$servername=$GLOBALS["servername"];}
		if ($username==NULL){$username=$GLOBALS["username"];}
		if ($password==NULL){$password=$GLOBALS["password"];}
		if ($dbname==NULL){$dbname=$GLOBALS["dbname"];}
	}

	function mysqlsafe($string){	//makes the input safe; under construction
		if (get_magic_quotes_gpc()){
			$string = stripslashes($string);
		}
		return mysqli_real_escape_string($string, "'");
	}

	function databasequery		($sql, $dbname=NULL, $servername=NULL, $username=NULL, $password=NULL){	//to create, delete database
		connection($dbname, $servername, $username, $password);
		try {
			$conn = new PDO("mysql:host=$servername", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			// set the PDO error mode to exception
			$conn->exec($sql);														// use exec() because no results are returned
			return array(1,$sql,"Query executed successfully!");
		}
		catch(PDOException $e){
			return array(0,$sql,$e->getMessage());
		}
		$conn = null;
	}

	function singlequery		($sql, $dbname=NULL, $servername=NULL, $username=NULL, $password=NULL){	//to create/drop table, insert data, delete data
		connection($dbname, $servername, $username, $password);
		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->exec($sql);
			return array(1,$sql,"Query executed successfully!");
		}
		catch(PDOException $e){
			return array(0,$sql,$e->getMessage());
		}
		$conn = null;
	}

	function multiplequery		(	   $dbname=NULL, $servername=NULL, $username=NULL, $password=NULL){	//send the sqls as remaining parameters
		connection($dbname, $servername, $username, $password);
		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
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

	function insertrecord		($sql, $dbname=NULL, $servername=NULL, $username=NULL, $password=NULL){
		connection($dbname, $servername, $username, $password);
		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
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

	function updaterecord		($sql, $dbname=NULL, $servername=NULL, $username=NULL, $password=NULL){
		connection($dbname, $servername, $username, $password);
		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
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

	function selectrecord		($sql, $dbname=NULL, $servername=NULL, $username=NULL, $password=NULL){	//get by $result[NO OF ROW][NAME OF ROW]; eg $result[1][id];
		try{
			connection($dbname, $servername, $username, $password);
			$dbname_2=$GLOBALS["dbname_2"];
			$servername_2=$GLOBALS["servername_2"];
			$conn = mysqli_connect($servername_2, $username, $password, $dbname_2);
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
		catch (Exception $e){
			echo "Exception with message: ".$e->message."<br>";
		}
	}

	function preparedstatement	($sql, $dbname=NULL, $servername=NULL, $username=NULL, $password=NULL){
		connection($dbname, $servername, $username, $password);
		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
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
			username VARCHAR(50) NOT NULL,
			password VARCHAR(50) NOT NULL,
			reg_date TIMESTAMP NOT NULL,
			UNIQUE (id),
			UNIQUE (email),
			UNIQUE (username)
			)";
	*/
	//	$sql = "INSERT INTO users (fname, lname, email, country, dob, sex, username, password) 
	//		VALUES ('Paras', 'Sharma', 'shps23@live.com', '1997/12/4', 'male', 'shps23', 'hahaha')";
	//	$sql = "SELECT id, fname, lname FROM users WHERE username='shps23'";
	//	$sql = "DELETE FROM users WHERE username='shps23'";
	//	$sql = "UPDATE users SET fname='Prabesh', lname='sharma' WHERE username='shps23'";
	//	$sql = "SELECT * FROM users LIMIT 30";				//returns 1-30 (incusive) records
	//	$sql = "SELECT * FROM users LIMIT 10 OFFSET 0";	//returns 10 records start from 16 (offset 15)
	//	$sql = "SELECT * FROM users LIMIT 15, 10";			//same but shortcut
	}

	function firsttimedb(){
		$dbname=$GLOBALS["dbname"];
		
		$sql = "CREATE DATABASE $dbname;";
		/*$result=databasequery($sql);
		if ($result[0]!==1){
			echo $result[2];
		}*/
		
		$sql = "CREATE TABLE ipstore (
			id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			ip VARCHAR(15),
			date INT(10),
			page VARCHAR(500),
			user VARCHAR(50),
			UNIQUE (id)
			);";
		$result=singlequery($sql);
		if ($result[0]!==1){
			echo $result[2];
		}
		
		$sql = "CREATE TABLE Users (
				id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				fname VARCHAR(30) NOT NULL,
				lname VARCHAR(30) NOT NULL,
				email VARCHAR(50) NOT NULL,
				country VARCHAR(50) NOT NULL,
				dob VARCHAR(30) NOT NULL,
				sex VARCHAR(7) NOT NULL,
				username VARCHAR(50) NOT NULL,
				password VARCHAR(50) NOT NULL,
				type VARCHAR(10) NOT NULL,
				status VARCHAR(10) NOT NULL,
				reg_date INT(10) NOT NULL,
				UNIQUE (id),
				UNIQUE (email),
				UNIQUE (username)
			);";
		
		$result=singlequery($sql);
		if ($result[0]!==1){
			echo $result[2];
		}
		
		$sql = "CREATE TABLE lastonline (
				id INT(10) UNSIGNED PRIMARY KEY,
				lastonline INT(10) NOT NULL,
				UNIQUE (id)
			);";
		
		$result=singlequery($sql);
		if ($result[0]!==1){
			echo $result[2];
		}
		
		$sql = "CREATE TABLE findusers (
			id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			usrid INT(10) UNSIGNED,
			interests TEXT,
			blockedusers TEXT,
			addedtime INT(10),
			status VARCHAR(10),
			holdby INT(10),
			UNIQUE (usrid)
			);";
		$result=singlequery($sql);
		if ($result[0]!==1){
			echo $result[2];
		}
		
		$sql = "CREATE TABLE oldusernames (
			id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			userid INT(10),
			username VARCHAR(50)
			);";
		$result=singlequery($sql);
		if ($result[0]!==1){
			echo $result[2];
		}

	}
?>