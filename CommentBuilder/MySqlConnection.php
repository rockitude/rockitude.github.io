<?php

/*****************************************************************
 * @author: Nicholas Parian
 * @class: mySqlConnection
 * @date: August 1, 2008
 * @purpose: provides a My Sql Connection to the Given Database
 ****************************************************************/

class MySqlConnection{


/******************* Constants **************************/


	const FIRST_ROW = 0;
	const FIRST_COLUMN = 0;
	const GET_LAST_INSERT_ID = "Select LAST_INSERT_ID()";
	const SP_CALL = "CALL %s(%s)";
	const SP_STRING_TYPE = "String";
	const SP_NUMBER_TYPE = "Number";
	const SP_DOUBLE_TYPE = "Double";
	const SP_BOOL_TYPE = "Boolean";
	const SP_NULL_TYPE = "Null";
	const SP_NULL = "null";
	const SP_DOUBLE = "%f";
	const SP_STRING = "'%s'";
	const SP_OUT_PARAM_TYPE = "Out";
	const SP_OUT_PARAM = "%s";
	const SP_DEFAULT = "%s";
	const SP_BOOLEAN = "%b";
	const MY_SQL_ENABLE_SP = 65536;
	const PARAM_TYPE_COUNT_MISMATCH = "Error Executing Stored Procedure, Type Count Does Not Match Parameter Count";
	
	
	
	
	
/******************* Global Variables *******************/
		
		
		private $server;
		private $dataBase;
		private $userName;
		private $password;
        private $connectionLink;
		private $gDebug;
		
		

		

/******************* Constructors ***********************/

		
		/******** mySqlConnection : String * String * String * String ********/
		/*
		 * @construct Initalized a new connection the the specified Database
		 */
		public function __construct($server, $dataBase, $userName, $password, $debug = false){
			$this->server = $server;
			$this->dataBase = $dataBase;
			$this->userName = $userName;
			$this->password = $password;
			$this->connectionLink = NULL;
			$this->gDebug = $debug;
		}

		
		
		
		

/********************* Methods *************************/
		
		
		/******** connect : => mySqlConnection ********/
		// Creates a new connection to the Sql Data base
		private function connect(){

			$link = mysql_connect($this->server, 
                                  $this->userName,
					      	      $this->password,
					      	      0,
					      	      MySqlConnection::MY_SQL_ENABLE_SP);
							      
			// connect to the server
			if(!$link)
				throw new Exception("Could not Connect to Host: " . $this->server);
				
			// select the correct database
			if (!mysql_select_db($this->dataBase, $link))
				throw new Exception("Could not Select Database: " . $this->dataBase); 

			// return the connection	
			$this->lastLink = $link;
			return ($link);

		}
		
		
		
		

		/******** disconnect : ==> void ********/
		// disconnects from the MySql Server
		public function disconnect(){
			if($this->connectionLink != NULL){
				mysql_close($this->connectionLink);
				$this->connectionLink = NULL;
				
			}
		}


		
		

		/******** doQuery : String ==> String()() ********/
		// Returns the Selected rows from a querry
		public function doQuery($sqlString, $keepAlive=false){
			$origonalString = $sqlString;
			$rowsReturned = -1;
			$returnedRows = array();

			//open the mySql Connection
			if(is_null($this->connectionLink))
				$connectionLink = $this->connect();
			else
				$connectionLink = $this->connectionLink;

			$this->connectionLink = $connectionLink;

			// Clean The sql String
			//$sqlString = mysql_real_escape_string($sqlString, $connectionLink);

			if($this->gDebug)
				echo $sqlString . "<br />";
			//Querry The Database
			$result = mysql_query($sqlString, $connectionLink);



			if(!$result)
			  throw new Exception("DB Error, could not query the database\n" . $origonalString);

			// Get the number of affected rows
			$rowsReturned = mysql_num_rows($result);

			// copy the result data
			for($x = 0; $x < $rowsReturned; $x++){
				$returnedRows[$x] = mysql_fetch_array($result);
			}

			// Close The Database connection
			if(!$keepAlive)
				$this->disconnect();


			return ($returnedRows);
		}
		
		
		
		

		/******** doScalarQuery : String * Boolean ==> int ********/
		// Returs a integer value for the query
        public function doScalarQuery($sqlString, $keepAlive = false){
			$result = $this->doQuery($sqlString, $keepAlive);
			return (int)($result[MySqlConnection::FIRST_ROW][MySqlConnection::FIRST_COLUMN]);
		}





		/******** doNonQuery : String ==> int ********/
		// Insert, update or Delete
		// returns the number of rows affected
		public function doNonQuery($sqlString, $keepAlive = false){
			$origonalString = $sqlString;
			$rowsReturned = -1;

			//open the mySql Connection
			if($this->connectionLink == NULL)
				$connectionLink = $this->connect();
			else
				$connectionLink = $this->connectionLink;

			$this->connectionLink = $connectionLink;

			// Clean the Sql String
			//$sqlString = mysql_real_escape_string($sqlString, $connectionLink);

			//Querry The Database
			$result = mysql_query($sqlString, $connectionLink);

			$info = mysql_info ($connectionLink);

			if(!$result)
			  throw new Exception("DB Error, could not Update the database\n" . $origonalString . "\n" . mysql_error());

			// Get the number of affected rows
			$rowsReturned = mysql_affected_rows($connectionLink);

			// Close the Sql Connection
			//mysql_close($connectionLink);
			if(!$keepAlive)
				$this->disconnect();

			// return the number of Row affected
			return ($rowsReturned);
		}
		
		
		
		
		
		/******** executeStoredProcedure : String * Array ==> String()() ********/
		/**
		 * Executed a stored procedure on mySql Database
		 * ToDo: write code for out parameters
		 * @param String $procedureName Name of the stored procudure
		 * @param Array() $params optionalList of parameters
		 * @param Array() $type optionalList of parameter types, default handled as string
		 * 						ToDo, all types to allow for out parameters
		 * 						For now, use the hack, keepAlive = true and query the out parameter 
		 * 						using doQuery()
		 * @param Boolean $keepAlive true to Keep the session Alive
		 */
		public function executeStoredProcedure($procedureName, 
											   $params = array(),
											   $keepAlive = false){
			$stringBuilder = '';
			//iterate through all the parameters
			foreach($params as $key){
				if(strlen($stringBuilder) > 0)
					$stringBuilder .= ', ';
				
				if(is_string($key))
					$type = MySqlConnection::SP_STRING_TYPE;
				elseif (is_numeric($key))
					$type = MySqlConnection::SP_NUMBER_TYPE;
				elseif (is_null($key))
					$type = MySqlConnection::SP_NULL_TYPE;
				elseif(is_bool($key))
					$type = MySqlConnection::SP_BOOL_TYPE;
				//TODO: how to handle out params?	
					
				// we defined types so
				switch ($type){
					case MySqlConnection::SP_STRING_TYPE:
						$stringBuilder .= sprintf(MySqlConnection::SP_STRING, $key);
						break;
					case MySqlConnection::SP_OUT_PARAM_TYPE:
						$stringBuilder .= sprintf(MySqlConnection::SP_OUT_PARAM, $key);
						break;
					case MySqlConnection::SP_NUMBER_TYPE:
						$stringBuilder .= sprintf(MySqlConnection::SP_DEFAULT, $key);
						break;
					case MySqlConnection::SP_NULL_TYPE:
						$stringBuilder .= MySqlConnection::SP_NULL;
						break;
					case MySqlConnection::SP_BOOL_TYPE:
						$stringBuilder .= sprintf(MySqlConnection::SP_BOOLEAN, $key);
						break;
					default:
						$stringBuilder .= sprintf(MySqlConnection::SP_DEFAULT, $type);
				}
			}
			$spCall = sprintf(MySqlConnection::SP_CALL, $procedureName,  $stringBuilder);
			return $this->doQuery($spCall, $keepAlive);
			
		}
		
		
		

		
        /******** getLastInsertId : ==> int ********/
        // returns the last inserted Id from the auto increment column in mySql
		public function getLastInsertId(){
			$sqlQuery = MySqlConnection::GET_LAST_INSERT_ID;
			return $this->doScalarQuery($sqlQuery);
		}
		
		
		
		

		/******** getError : ==> String ********/
		// returns the last Error thrown by the MySql Connection
        public function getError(){
			return mysql_error($this->lastLink);
		}
		public function startTransaction(){
			//TODO: ADD CODE FOR TRANSACTION HERE
			return 1;
		}
		public function endTransaction($transactionId){
			//TODO: ADD CODE FOR TRANSACTIONS HERE
		}
		public function rollbackTransaction($transactionId){
			//TODO: ADD CODE FOR TRANSACTIONS HERE
		}

	}

?>
