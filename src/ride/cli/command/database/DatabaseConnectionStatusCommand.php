<?php

namespace ride\cli\command\database;

use ride\library\database\exception\DatabaseException;

/**
 * Command to show the status of a connection
 */
class DatabaseConnectionStatusCommand extends AbstractDatabaseCommand {

    /**
     * Constructs a new database command
     * @return null
     */
    public function __construct() {
    	parent::__construct('database connection status', 'Get the status of a connection');

    	$this->addArgument('name', 'Name of the connection', false);
    }

    /**
     * Executes the command
	 * @return null
     */
    public function execute() {
    	$name = $this->input->getArgument('name');

    	if ($name) {
    	    $connections = array($name => $this->databaseManager->getConnection($name, false));
    	} else {
    		$connections = $this->databaseManager->getConnections();
    	}

    	foreach ($connections as $name => $connection) {
        	try {

        		if (!$connection->isConnected()) {
        			$connection->connect();
        			$connection->disconnect();
        		}

        		$this->output->writeLine($name . ': ' . $connection->getDsn() . ' OK');
        	} catch (DatabaseException $exception) {
        		$this->output->writeLine($name . ': ' . $connection->getDsn() . ' ' . $exception->getMessage());
        	}
    	}
    }

}