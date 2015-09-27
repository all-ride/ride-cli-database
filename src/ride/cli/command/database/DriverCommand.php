<?php

namespace ride\cli\command\database;

use ride\cli\command\AbstractCommand;

use ride\library\database\DatabaseManager;

/**
 * Command to show the installed database drivers
 */
class DriverCommand extends AbstractCommand {

    /**
     * Initializes the command
     * @return null
     */
    protected function initialize() {
        $this->setDescription('Shows an overview of the database drivers.');
    }

    /**
     * Invokes the command
     * @param \ride\library\database\DatabaseManager $databaseManager
     * @return null
     */
    public function invoke(DatabaseManager $databaseManager) {
        $drivers = $databaseManager->getDrivers();

        foreach ($drivers as $protocol => $class) {
            $this->output->writeLine($protocol . ': ' . $class);
        }
    }

}