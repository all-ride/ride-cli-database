<?php

namespace pallo\cli\command\database;

/**
 * Command to unregister a driver
 */
class DatabaseConnectionUnregisterCommand extends AbstractDatabaseCommand {

    /**
     * Constructs a new database command
     * @return null
     */
    public function __construct() {
    	parent::__construct('database connection unregister', 'Unregisters a database connection.');

    	$this->addArgument('name', 'Name for the connection');
    }

    /**
     * Executes the command
	 * @return null
     */
    public function execute() {
    	$name = $this->input->getArgument('name');

		$this->databaseManager->unregisterConnection($name);
    }

}