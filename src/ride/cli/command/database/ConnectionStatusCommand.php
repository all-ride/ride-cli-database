<?php

namespace ride\cli\command\database;

use ride\cli\command\AbstractCommand;

use ride\library\database\exception\DatabaseException;
use ride\library\database\DatabaseManager;

/**
 * Command to show the status of a database connection
 */
class ConnectionStatusCommand extends AbstractCommand {

    /**
     * Initializes the command
     * @return null
     */
    protected function initialize() {
        $this->setDescription('Get the status of a connection');

        $this->addArgument('name', 'Name of the connection', false);
    }

    /**
     * Invokes the command
     * @param \ride\library\database\DatabaseManager $databaseManager
     * @param string $name
     * @return null
     */
    public function invoke(DatabaseManager $databaseManager, $name = null) {
        if ($name) {
            $connections = array($name => $databaseManager->getConnection($name, false));
        } else {
            $connections = $databaseManager->getConnections();
        }

        foreach ($connections as $name => $connection) {
            try {
                if (!$connection->isConnected()) {
                    $connection->connect();
                    $connection->disconnect();
                }

                $this->output->writeLine($name . ': ' . $connection->getDsn() . ' OK');
            } catch (DatabaseException $exception) {
                $this->output->writeLine($name . ': ' . $connection->getDsn() . ' ' . $exception->getMessage());
            }
        }
    }

}