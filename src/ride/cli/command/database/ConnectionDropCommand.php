<?php

namespace ride\cli\command\database;

use ride\cli\command\AbstractCommand;

use ride\library\database\DatabaseManager;
use ride\library\database\Dsn;
use ride\library\log\Log;

/**
 * Command to drop a database of a registered connection
 */
class ConnectionDropCommand extends AbstractCommand {

    /**
     * Instance of the log
     * @var \ride\library\log\Log
     */
    private $log;

    /**
     * Sets the instance of the log
     * @param \ride\library\log\Log $log
     * @return null
     */
    public function setLog(Log $log) {
        $this->log = $log;
    }

    /**
     * Initializes the command
     * @return null
     */
    protected function initialize() {
        $this->setDescription('Drops the database of the connection if it exists');

        $this->addArgument('name', 'Name of the connection', true);
    }

    /**
     * Invokes the command
     * @param \ride\library\database\DatabaseManager $databaseManager
     * @param string $name
     * @return null
     */
    public function invoke(DatabaseManager $databaseManager, $name = null) {
        $connection = $databaseManager->getConnection($name, false);

        $dsn = $connection->getDsn();
        $protocol = $dsn->getProtocol();

        if ($protocol != 'mysql') {
            throw new ConsoleException('This command is currently implemented for MySQL connections only');
        }

        $driver = $databaseManager->getDriver($protocol);

        $database = $dsn->getDatabase();
        $dsn = new Dsn(substr((string) $dsn, 0, strlen($database) * -1) . 'mysql');

        $connection = new $driver($dsn);
        if ($this->log) {
           $connection->setLog($this->log);
        }

        $connection->connect();
        $connection->execute("DROP DATABASE IF EXISTS `$database`");
        $connection->disconnect();
    }

}