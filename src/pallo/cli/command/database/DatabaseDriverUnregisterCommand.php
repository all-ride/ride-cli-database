<?php

namespace pallo\cli\command\database;

/**
 * Command to unregister a driver
 */
class DatabaseDriverUnregisterCommand extends AbstractDatabaseCommand {

    /**
     * Constructs a new database command
     * @return null
     */
    public function __construct() {
    	parent::__construct('database driver unregister', 'Unregisters a database driver.');

    	$this->addArgument('protocol', 'Protocol of the driver');
    }

    /**
     * Executes the command
	 * @return null
     */
    public function execute() {
    	$protocol = $this->input->getArgument('protocol');

        $this->databaseManager->unregisterDriver($protocol);
    }

}