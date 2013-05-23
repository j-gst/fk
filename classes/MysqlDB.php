<?php namespace classes;

/*
 * @autor: Gerrit Storm
 * 
 * Datenbankanbindung 
 */
 
 
/**
 * Generic MySQL Class
 */
class MysqlDB {


    /****************
     ** properties **
     ****************/

    /**
     * database handle
     * @var mysqli
     */
    private $dbh;

    /**
     * last Result
     * @var mysqli_result|false
     */
    private $lastResult;

    /**
     * The Record currently in use
     * @var array|false
     */
    private $Record;

    /**
     * MySQL Error Number
     * @var int
     */
    private $errno = 0;

    /**
     * MySQL error string
     * @var string
     */
    private $errStr = '';

    /**
     * Collection of all errors occured
     * @var array
     */
    private $errors = array();
    
    /**
     * Do I trigger the errors?
     * @var bool
     */
    private $showErrors;

    /**
     * Am I connected?
     * @var boolean
     */
    private $connected;

    /**
     * Do I currently do a transaction?
     * @var int
     */
    private $transactionState = 0;

    /**
     * Constant to represent the MySQL DATETIME format as a string to parse in date()
     * @var string
     */
    const MYSQL_DATETIME_FORMAT = "Y-m-d H:i:s";

    /**
     * Constant to represent a DATE in MySQL. It is presented as the Date at 00:00:00.
     * @var string
     */
    const MYSQL_DATE_FORMAT = "Y-m-d 00:00:00";

    /*******************************
     ** Construct and deconstruct **
     *******************************/

    /**
     * Construct the object and connect to the database.
     * @param string $db_host The database-host
     * @param string $db_user The database-user
     * @param string $db_pass The database-password
     * @param string $db_database The database to use
     * @param bool $showErrors Should possible errors be shown as PHP Warnings?
     */
    public function __construct($db_host, $db_user, $db_pass, $db_database, $showErrors = false) {
        $this->connected = $this->connect($db_host, $db_user, $db_pass, $db_database);
        $this->showErrors = $showErrors;
    }

    /**
     * close connection to MySQL DB
     */
    public function __destruct() {
        if($this->transactionState) {
            $this->rollbackTransaction();
        }
        
        if ($this->dbh) {
            $this->dbh->close();
        }
    }


    /********************************
     ** wrapper for the properties **
     ********************************/

    /**
     * get error code
     * @return int
     */
    public function getErrno() {
        return $this->dbh->errno;
    }

    /**
     * get error text
     * @return string
     */
    public function getError() {
        return $this->dbh->error;
    }

    /**
     * return a list of all errors with timestamps
     * @return array an array of arrays with the form
     * array("no" => int, "str" => string, "time" => timestamp)
     */
    public function getErrorList() {
        return $this->errors;
    }

    /**
     * Return the last record
     * @return array|false
     */
    public function getRecord() {
        return $this->Record;
    }


    /*************************
     ** connection handling **
     *************************/

    /**
     * Connect to the database
     * @param string $db_host The database-host
     * @param string $db_user The database-user
     * @param string $db_pass The database-password
     * @param string $db_database The database to use
     * @return boolean Operation successful? 
     */
    private function connect($db_host, $db_user, $db_pass, $db_database) {
        $this->dbh = new \mysqli($db_host, $db_user, $db_pass, $db_database);

        return !$this->error($this->dbh->connect_errno, $this->dbh->connect_error);
    }

    /*******************************
     ** methods to perform querys **
     *******************************/

    /**
     * generic MySQL query
     * uses mysqli_real_escape_string on arguments
     * @param string $query query string for use with printf()
     * @param array $args (optional) array of arguments for printf()
     * @return boolean true on success, false on failure
     * @example $this->query("SELECT %s, %s FROM person WHERE id = %d", array("name", "surname", $id)
     * @see http://php.net/sprintf for documentation on PHP printf formatting
     */
    public function query($query, $args = array()) {
        $this->Record = false;
        $this->free_result();

        if(!$this->connected) {
            return false;
        }

        // build query string with sprintf()
        if (is_array($args) && count($args) > 0) {
            // uses mysqli_real_escape_string
            $args = $this->sanitize($args);
            $query = vsprintf($query, $args);
        }
        else {
            $query = str_replace('%%', '%', $query);
        }
		
        $this->lastResult = $this->dbh->query($query);
		 
		
        if($this->lastResult === false) {
            if($this->dbh->errno) {
                $this->error($this->dbh->errno, $this->dbh->error);
            }
            else {
                $this->error(-1, "An error occurred");
            }
            return false;
        }
        else {
            return true;
        }
    }



    /**
     * Perform a query and return the results in a handy assosiative array
     * @param string $query The query to perform
     * @param array $args (optional) the arguments for the printf-formatted query
     * @return array|false An array with the results or false on error
     * @example $this->query("SELECT %s, %s FROM person WHERE id = %d", array("name", "surname", $id)
     * @see MysqlDB::query()
     */
    public function query_array($query, $args = array()) {
	
        if($this->query($query, $args) && $this->lastResult instanceof \mysqli_result) {
            $output = array();
            while($this->next_record()) {
                $output[] = $this->Record;
            }
            return $output;
        }
        else
            return false;
    }

    /**
     * Wrapper method for select statements
     * @param string $table the table to search in
     * @param string|array $cols (optional) a comma seperated list of columns to search for
     * or an array with the columns. Defaults to '*'
     * @param string $where (optional) a printf() formatted string to restrict the select statement
     * @param array $whereArgs (optional) the arguments for $where. These will be sanitized.
     * @param bool $trimFirst (optional) if set to true, only the first record will be returned
     * @return array|false An array with the results or false on error
     * @example $this->select("person", array("name", "surname"), "id = %d", array($id))
     */
    public function select($table, $cols = '*', $where = '', $whereArgs = array(), $trimFirst = false) {
        if(is_array($cols)) {
            $cols = implode(", ", $cols);
        }

        $qry = "SELECT $cols FROM $table";
        if($where) {
            $qry .= " WHERE ".$where;
        }

        if($trimFirst) {
            if($this->query($qry, $whereArgs) && $this->next_record()) {
                return $this->Record;
            }
            else {
                return false;
            }
        }
        else {
            return $this->query_array($qry, $whereArgs);
        }
    }

    /**
     * Insert a row into a table
     * @param string $table The table to fill
     * @param array $insertArgs associative array with the values to insert into the table
     * @param string $types (optional) the types of the Arguments passed to bind_param. Per default, a list of strings is assumed (sss...)
     * @param bool $duplicatekeyupdate (optional) if set to true, the MySQL "ON DUPLICATE KEY UPDATE" clause is invoked
     * for each coloum that was passed in $insertArgs. Defaults to false.
     * @return int|false On success the inserted id, on failure false
     * @example $this->insert("person", array("name" => "John", "surname" => "Doe", "gender" => "m"), "ss")
     * @see http://php.net/mysqli-stmt.bind-param.php for the Syntax of bind_param
     */
    public function insert($table, $insertArgs, $types = "", $duplicatekeyupdate = false) {
        if(!$this->connected) {
            return false;
        }

        $insCount = count($insertArgs);
        
        $query = "INSERT INTO $table (%s) VALUES (%s)";
        if($duplicatekeyupdate) {
            $query .= " ON DUPLICATE KEY UPDATE";
            foreach($insertArgs as $name => $v) {
                $query .= " $name = VALUES($name),";
            }
            $query = rtrim($query, ",");
        }
        
        /* @var mysqli_stmt $stmt */
        $stmt = $this->dbh->prepare(sprintf($query,
            implode(", ", array_keys($insertArgs)),
            implode(", ", array_fill(0, $insCount, '?'))
        ));

        // $types too short or empty? => fill with strings!
        if(strlen($types) < $insCount) {
            $types = str_pad($types, $insCount, 's', STR_PAD_RIGHT);
        }
        if($stmt === false) {
            $this->error($this->dbh->errno, $this->dbh->error);
            return false;
        }

        // bind ALL THE arguments!
        $bind = call_user_func_array(
            array($stmt, "bind_param"),
            array($types) + self::makeArrayValsReferenced($insertArgs)
        );

        if($bind && $stmt->execute()) {
            return $stmt->insert_id;
        }
        else {
            $this->error($stmt->errno, $stmt->error);
            return false;
        }
    }


    /**
     * Update a table using values from an associative array
     * @param string $table The table to fill
     * @param array $updateArgs associative array with the values to update in the table
     * @param string $updateTypes (optional) the types of the Arguments in $updateArgs passed to bind_param.
     * If too short or empty, it assumes that all extra parameters are strings.
     * @param string $where (optional) A string used to limit the number of affected rows (equals MySQL 'WHERE').
     * Use as a printf-formatted string without WHERE at the beginning
     * @param array $whereArgs (optional) a list of arguments to pass to the string in $where
     * @return int|false On succes the number of affected rows, on failure false
     * @example $this->update("person", array("name" => "Jane", "gender" => "f"), "ss", "name = %s", array("John"))
     * @see http://php.net/mysqli-stmt.bind-param.php for the Synthax of bind_param
     */
    public function update($table, $updateArgs, $updateTypes = "", $where = '', $whereArgs = array()) {
        if(!$this->connected) {
            return false;
        }

        $upCount = count($updateArgs);
        $whereArgs = $this->sanitize($whereArgs);

        $query = "UPDATE $table SET ";
        foreach($updateArgs as $key => $val) {
            $query .= "$key = ?, ";
        }
        // delete last ', '
        $query = substr($query, 0, -2);

        if($where) {
            $query .= " WHERE ".vsprintf($where, $whereArgs);
        }

        /* @var mysqli_stmt */
        $stmt = $this->dbh->prepare($query);
        if($stmt === false) {
            $this->error($this->dbh->errno, $this->dbh->error);
            return false;
        }

        // $updateTypes too short or empty? => fill with strings!
        if(strlen($updateTypes) < $upCount) {
            $updateTypes = str_pad($updateTypes, $upCount, 's', STR_PAD_RIGHT);
        }

        // bind ALL THE arguments!
        $bind = call_user_func_array(
            array($stmt, "bind_param"),
            array($updateTypes) + self::makeArrayValsReferenced($updateArgs)
        );

        if($bind && $stmt->execute()) {
            return $stmt->affected_rows;
        }
        else {
            $this->error($stmt->errno, $stmt->error);
            return false;
        }

    }

    /**
     * Delete rows from a table
     * @param string $table The table to delete rows from
     * @param string $where A string used to limit the number of affected rows.
     * Use as a printf-formatted string without WHERE at the beginning
     * @param array $whereArgs (optional) a list of arguments to pass to the string in $where
     * @return int|false On succes the number of affected rows, on failure false
     * @example $this->delete("person", "surname = %s", array("Doe"))
     */
    public function delete($table, $where, $whereArgs = array()) {
        if(!$this->connected) {
            return false;
        }

        $whereArgs = $this->sanitize($whereArgs);

        $stmt = $this->dbh->prepare(
            "DELETE FROM $table WHERE ".
            vsprintf($where, $whereArgs)
        );

        if($stmt === false) {
            $this->error($this->dbh->errno, $this->dbh->error);
            return false;
        }
        elseif($stmt->execute()) {
            return $stmt->affected_rows;
        }
        else {
            $this->error($stmt->errno, $stmt->error);
            return false;
        }
    }
    
    /**
     * Test, if there is a result from the table for a specific search
     * @param string $table The table to test in
     * @param string $where (optional) This is the specific search to test for.
     * If left blank, this test, if there are any results.
     * Use as a printf-formatted string without WHERE at the beginning.
     * @param array $whereArgs (optional) a list of arguments to pass to the string in $where
     * @return boolean
     * @example $this->exists("person", "surname='%s', array("Doe"))
     */
    public function exists($table, $where = "1", $whereArgs = array()) {
        if($this->query("SELECT NULL FROM $table WHERE $where", $whereArgs)) {
            return $this->next_record();
        }
        else {
            return false;
        }
    }
    
    /**
     * Start a transaction
     */
    public function startTransaction() {
        if(!$this->transactionState) {
            $this->dbh->autocommit(false);
        }
        $this->transactionState++;
    }
    
    /**
     * Rollback the changes in the currect transaction
     * @param boolean $stop (optional) if I should halt the current transaction. Defaults to true.
     */
    public function rollbackTransaction($stop = true) {
        if($this->transactionState) {
            $this->dbh->rollback();
            
            if($stop) {
                $this->commitTransaction();
            }
        }
    }
    
    /**
     * Commit and stop the current transaction
     */
    public function commitTransaction() {
        if($this->transactionState) {
            $this->dbh->commit();
            if(!--$this->transactionState) {
                $this->dbh->autocommit(true);
            }
        }
    }


    /********************
     ** helper methods **
     ********************/

    /**
     * Handle all errors that could occur
     * @param int $errno the error number to handle. The Mysqli*-classes leave 0 when there was no error.
     * @param string $errStr the error string. The Mysqli*-classes pass null when there was no error
     * @return boolean true on error, false otherwise
     */
    private function error($errno, $errStr) {
        if($errno) {
            $this->errno = $errno;
            $this->errStr = $errStr;
            $this->errors[] = array("no" => $errno, "str" => $errStr, "time" => time());
            if($this->showErrors) {
                trigger_error("Database-Error: '$errStr'", E_USER_WARNING);
                
                //ob_start('trim'); debug_print_backtrace();
                //trigger_error("Backtrace:\n".ob_get_clean(), E_USER_NOTICE);
            }
            
            return true;
        }
        else {
            $this->errno = 0;
            $this->errStr = "";
            return false;
        }

    }


    /**
     * Forward the result-pointer by one. To retrieve the whole record, call getRecord(),
     * to retrieve a single column, call fetch() or f()
     * @param int $mode (optional) How to store the record? (defaults to MYSQLI_ASSOC). 
     *   Possible values:<ul>
     *   <li>MYSQLI_ASSOC: store an associative array</li>
     *   <li>MYSQLI_NUM: store a numeric array</li>
     *   <li>MYSQLI_BOTH: store both of the above in a single array</li></ul>
     * @return boolean was there a next record?
     * @see MysqlDB::getRecord()
     * @see MysqlDB::fetch()
     */
    public function next_record($mode = MYSQLI_ASSOC) {
        if($this->lastResult instanceof \mysqli_result
            && $this->Record = $this->lastResult->fetch_array($mode))
        {
            return true;
        }
        else
            return false;
    }

    /**
     * Fetch a value from the current result row
     * @param string $value the column to fetch
     * @return multitype|false the content of the column if it exists. False or failure.
     */
    public function fetch($value) {
        if($this->Record && isset($this->Record[$value])) {
            return $this->Record[$value];
        }
        else
            return false;
    }

    /**
     * Alias for 'fetch'
     * @param string $value
     * @return multitype|false
     * @see MysqlDB::fetch()
     */
    public function f($value) {
        return $this->fetch($value);
    }


    /**
     * Free the result of the last performed query
     */
    private function free_result() {
        if($this->lastResult instanceof \mysqli_result) {
            $this->lastResult->free();
        }
    }

    /**
     * clean array with arguments for mysql
     * uses mysqli_real_escape_string
     * @param array $args array of arguments
     * @return array of escaped arguments
     */
    private function sanitize($args) {
        foreach ($args as $key => $arg) {
            if(is_string($arg)) {
                $args[$key] = $this->dbh->real_escape_string($arg);
            }
            elseif(!isset($arg)) {
                $args[$key] = 'NULL';
            }
            else {
                $args[$key] = $arg;
            }
        }
        return $args;
    }


    /**
     * Helper: to use call_user_func_array with bind_params,
     * one needs to reference the items in the array
     * @param array $array the array to parse
     * @return array the parsed array
     */
    private static function makeArrayValsReferenced($array) {
        $refs = array();
        foreach($array as $key => $value)
            $refs[$key] = &$array[$key];
        return $refs;
    }
}
?>
