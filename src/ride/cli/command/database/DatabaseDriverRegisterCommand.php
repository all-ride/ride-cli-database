<?php

namespace ride\cli\command\database;

/**
 * Command to register a driver
 */
class DatabaseDriverRegisterCommand extends AbstractDatabaseCommand {

    /**
     * Constructs a new database command
     * @return null
     */
    public function __construct() {
    	parent::__construct('database driver register', 'Registers a database driver.');

    	$this->addArgument('protocol', 'Protocol of the driver');
    	$this->addArgument('class', 'Full class name of the driver');
    }

    /**
     * Executes the command
	 * @return null
     */
    public function execute() {
    	$protocol = $this->input->getArgument('protocol');
    	$class = $this->input->getArgument('class');

    	$this->databaseManager->registerDriver($protocol, $class);
    }

}