<?php
class DBController {
	//Default for Xampp
	private $host = "localhost";
	//Default for Xampp
	private $user = "root";
	//Default for Xampp
	private $password = "";
	//Database Name
	private $database = "basketdiscounts";
	//Connection
	private $conn;
	
	//Function runs on object construction
	function __construct() {
		$this->conn = $this->connectDB();
	}
	
	//Connects to mySQL database using mysqliconnect with variable parameters. 
	function connectDB() {
		$conn = mysqli_connect($this->host,$this->user,$this->password,$this->database);
		return $conn;
	}
	
	//Allows SQL queries to be ran through the parameter
	function runQuery($query) {
		//store query results to variable
		$result = mysqli_query($this->conn,$query);
		//fetches result row/s and stores in an associative array
		while($row=mysqli_fetch_assoc($result)) {
			$resultset[] = $row;
		}		
		//if any rows have been stored in the array, return them. 
		if(!empty($resultset))
			return $resultset;
	}
	
	//RETURNS NUMBER OF ROWS FOUND IN QUERY (E.G NUMBER OF PRODUCTS)
	function numRows($query) {
		$result  = mysqli_query($this->conn,$query);
		$rowcount = mysqli_num_rows($result);
		return $rowcount;	
	}
}
?>