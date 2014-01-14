<?php

namespace pallo\cli\command\database;

use pallo\library\database\Dsn;

/**
 * Command to drop a database of a registered connection
 */
class DatabaseConnectionDropCommand extends AbstractDatabaseCommand {

    /**
     * Constructs a new database command
     * @return null
     */
    public function __construct() {
    	parent::__construct('database connection drop', 'Drops the database of the connection if it exists');

    	$this->addArgument('name', 'Name of the connection', true);
    }

    /**
     * Executes the command
	 * @return null
     */
    public function execute() {
    	$name = $this->input->getArgument('name');
    	$connection = $this->databaseManager->getConnection($name, false);

    	$dsn = $connection->getDsn();
    	$protocol = $dsn->getProtocol();

    	if ($protocol != 'mysql') {
    	    throw new ConsoleException('This command is currently implemented for MySQL connections only');
    	}

    	$driver = $this->databaseManager->getDriver($protocol);

    	$database = $dsn->getDatabase();
    	$dsn = new Dsn(substr((string) $dsn, 0, strlen($database) * -1) . 'mysql');

    	$connection = new $driver($dsn);
    	if ($this->log) {
    	   $connection->setLog($this->log);
    	}

    	$connection->connect();
    	$connection->execute("DROP DATABASE IF EXISTS `$database`");
    	$connection->disconnect();
    }

}