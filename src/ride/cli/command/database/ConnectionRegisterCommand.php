<?php

namespace ride\cli\command\database;

use ride\cli\command\AbstractCommand;

use ride\library\database\DatabaseManager;
use ride\library\database\Dsn;

/**
 * Command to register a new database connection
 */
class ConnectionRegisterCommand extends AbstractCommand {

    /**
     * Initializes the command
     * @return null
     */
    protected function initialize() {
        $this->setDescription('Register a database connection');

        $this->addArgument('name', 'Name for the connection');
        $this->addArgument('dsn', 'DSN of the connection (protocol://username:password@host:port/database)');
    }

    /**
     * Invokes the command
     * @param \ride\library\database\DatabaseManager $databaseManager
     * @param string $name
     * @param string $dsn
     * @return null
     */
    public function invoke(DatabaseManager $databaseManager, $name, $dsn) {
        $databaseManager->registerConnection($name, new Dsn($dsn));
    }

}