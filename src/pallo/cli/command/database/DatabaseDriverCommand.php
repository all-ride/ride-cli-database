<?php

namespace pallo\cli\command\database;

/**
 * Command to show the installed drivers
 */
class DatabaseDriverCommand extends AbstractDatabaseCommand {

    /**
     * Constructs a new database command
     * @return null
     */
    public function __construct() {
    	parent::__construct('database driver', 'Shows an overview of the database drivers.');
    }

    /**
     * Executes the command
	 * @return null
     */
    public function execute() {
        $drivers = $this->databaseManager->getDrivers();

        foreach ($drivers as $protocol => $class) {
            $this->output->writeLine($protocol . ': ' . $class);
        }
    }

}