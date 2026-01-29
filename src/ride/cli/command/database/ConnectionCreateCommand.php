<?php

namespace ride\cli\command\database;

use ride\cli\command\AbstractCommand;

use ride\library\cli\exception\CliException;
use ride\library\database\DatabaseManager;
use ride\library\database\Dsn;
use ride\library\log\Log;

/**
 * Command to create the database of a registered connection
 */
class ConnectionCreateCommand extends AbstractCommand {

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
        $this->setDescription('Creates the database of the connection if it does not exist');

        $this->addArgument('name', 'Name of the connection', false);
        $this->addArgument('charset', 'Default charset for the database (default utf8)', false);
        $this->addArgument('collation', 'Default collation for the database (default utf8mb4_0900_ai_ci)', false);
    }

    /**
     * Invokes the command
     * @param \ride\library\database\DatabaseManager $databaseManager
     * @param string $name
     * @param string $charset
     * @param string $collation
     * @return null
     */
    public function invoke(DatabaseManager $databaseManager, $name = null, $charset = 'utf8', $collation = 'utf8mb4_0900_ai_ci') {
        $connection = $databaseManager->getConnection($name, false);

        $dsn = $connection->getDsn();
        $protocol = $dsn->getProtocol();

        if ($protocol != 'mysql') {
            throw new CliException('This command is currently implemented for MySQL connections only');
        }

        $driver = $databaseManager->getDriver($protocol);

        $database = $dsn->getDatabase();
        $dsn = new Dsn(substr((string) $dsn, 0, strlen($database) * -1) . 'information_schema');

        $connection = new $driver($dsn);
        if ($this->log) {
           $connection->setLog($this->log);
        }

        $connection->connect();
        $connection->execute("CREATE DATABASE IF NOT EXISTS `$database` DEFAULT CHARACTER SET = '$charset' COLLATE '$collation'");
        $connection->disconnect();
    }

}
