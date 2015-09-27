<?php

namespace ride\cli\command\database;

use ride\cli\command\AbstractCommand;

use ride\library\database\DatabaseManager;

/**
 * Command to get and set the default database connection
 */
class ConnectionDefaultCommand extends AbstractCommand {

    /**
     * Initializes the command
     * @return null
     */
    protected function initialize() {
        $this->setDescription('Get or set the name of the default database connection');

        $this->addArgument('name', 'Name of the connection', true);
    }

    /**
     * Invokes the command
     * @param \ride\library\database\DatabaseManager $databaseManager
     * @param string $name
     * @return null
     */
    public function invoke(DatabaseManager $databaseManager, $name = null) {
        if ($name) {
            $databaseManager->setDefaultConnectionName($name);
        } else {
            $this->output->writeLine($databaseManager->getDefaultConnectionName());
        }
    }

}