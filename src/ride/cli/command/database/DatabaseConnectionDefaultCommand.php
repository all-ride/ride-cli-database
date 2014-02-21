<?php

namespace ride\cli\command\database;

/**
 * Command to show the installed connections
 */
class DatabaseConnectionDefaultCommand extends AbstractDatabaseCommand {

    /**
     * Constructs a new database command
     * @return null
     */
    public function __construct() {
    	parent::__construct('database connection default', 'Get or set the name of the default database connection');

    	$this->addArgument('name', 'Name of the connection', true);
    }

    /**
     * Executes the command
	 * @return null
     */
    public function execute() {
    	$name = $this->input->getArgument('name');

    	if ($name) {
    		$this->databaseManager->setDefaultConnectionName($name);
    	} else {
    		$this->output->writeLine($this->databaseManager->getDefaultConnectionName());
    	}
    }

}