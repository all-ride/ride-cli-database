<?php

namespace ride\cli\command\database;

use ride\library\database\Dsn;

/**
 * Command to register a driver
 */
class DatabaseConnectionRegisterCommand extends AbstractDatabaseCommand {

    /**
     * Constructs a new database command
     * @return null
     */
    public function __construct() {
    	parent::__construct('database connection register', 'Register a database connection');

    	$this->addArgument('name', 'Name for the connection');
    	$this->addArgument('dsn', 'DSN of the connection (protocol://username:password@host:port/database)');
    }

    /**
     * Executes the command
	 * @return null
     */
    public function execute() {
    	$name = $this->input->getArgument('name');
    	$dsn = $this->input->getArgument('dsn');

		$this->databaseManager->registerConnection($name, new Dsn($dsn));
    }

}