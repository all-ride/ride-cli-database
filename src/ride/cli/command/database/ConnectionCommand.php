<?php

namespace ride\cli\command\database;

use ride\cli\command\AbstractCommand;

use ride\library\database\DatabaseManager;

/**
 * Command to show the configured database connections
 */
class ConnectionCommand extends AbstractCommand {

    /**
     * Initializes the command
     * @return null
     */
    protected function initialize() {
        $this->setDescription('Shows an overview of the database connections.');
    }

    /**
     * Invokes the command
     * @param \ride\library\database\DatabaseManager $databaseManager
     * @return null
     */
    public function invoke(DatabaseManager $databaseManager) {
        $defaultConnectionName = $databaseManager->getDefaultConnectionName();

        $connections = $databaseManager->getConnections();
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