<?php

namespace ride\cli\command\database;

use ride\library\cli\exception\CliException;
use ride\library\database\Dsn;

/**
 * Command to create the database of a registered connection
 */
class DatabaseConnectionCreateCommand extends AbstractDatabaseCommand {

    /**
     * Constructs a new database command
     * @return null
     */
    public function __construct() {
    	parent::__construct('database connection create', 'Creates the database of the connection if it does not exist');

    	$this->addArgument('name', 'Name of the connection', false);
    	$this->addArgument('charset', 'Default charset for the database (default utf8)', false);
    	$this->addArgument('collation', 'Default collation for the database (default utf8_general_ci)', false);
    }

    /**
     * Executes the command
	 * @return null
     */
    public function execute() {
    	$name = $this->input->getArgument('name');
        $charset = $this->input->getArgument('charset', 'utf8');
        $collation = $this->input->getArgument('collation', 'utf8_general_ci');

    	$connection = $this->databaseManager->getConnection($name, false);

    	$dsn = $connection->getDsn();
    	$protocol = $dsn->getProtocol();

    	if ($protocol != 'mysql') {
    	    throw new CliException('This command is currently implemented for MySQL connections only');
    	}

    	$driver = $this->databaseManager->getDriver($protocol);

    	$database = $dsn->getDatabase();
    	$dsn = new Dsn(substr((string) $dsn, 0, strlen($database) * -1) . 'mysql');

    	$connection = new $driver($dsn);
    	if ($this->log) {
    	   $connection->setLog($this->log);
    	}

    	$connection->connect();
    	$connection->execute("CREATE DATABASE IF NOT EXISTS `$database` DEFAULT CHARACTER SET = '$charset' COLLATE '$collation'");
    	$connection->disconnect();
    }

}