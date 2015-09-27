<?php

namespace ride\cli\command\database;

use ride\cli\command\AbstractCommand;

use ride\library\database\DatabaseManager;

/**
 * Command to unregister a database connection
 */
class ConnectionUnregisterCommand extends AbstractCommand {

    /**
     * Initializes the command
     * @return null
     */
    protected function initialize() {
        $this->setDescription('Unregisters a database connection.');

        $this->addArgument('name', 'Name for the connection');
    }

    /**
     * Invokes the command
     * @param \ride\library\database\DatabaseManager $databaseManager
     * @param string $name
     * @return null
     */
    public function invoke(DatabaseManager $databaseManager, $name) {
        $databaseManager->unregisterConnection($name);
    }

}