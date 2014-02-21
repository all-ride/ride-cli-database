<?php

namespace ride\cli\command\database;

/**
 * Command to show the installed connections
 */
class DatabaseConnectionCommand extends AbstractDatabaseCommand {

    /**
     * Constructs a new database command
     * @return null
     */
    public function __construct() {
    	parent::__construct('database connection', 'Shows an overview of the database connections.');
    }

    /**
     * Executes the command
	 * @return null
     */
    public function execute() {
        $defaultConnectionName = $this->databaseManager->getDefaultConnectionName();

        $connections = $this->databaseManager->getConnections();
        foreach ($connections as $name => $connection) {
        	if ($name == $defaultConnectionName) {
        		$default = ' *';
        	} else {
        		$default = '';
        	}

            $this->output->writeLine($name . ': ' . $connection->getDsn() . $default);
        }
    }

}