<?php

namespace ride\cli\command\database;

use ride\library\cli\command\AbstractCommand;
use ride\library\database\DatabaseManager;
use ride\library\log\Log;

/**
 * Abstract database command
 */
abstract class AbstractDatabaseCommand extends AbstractCommand {

    /**
     * Instance of the database manager
     * @var ride\library\database\DatabaseManager
     */
    protected $databaseManager;

    /**
     * Instance of the log
     * @var ride\library\log\Log
     */
    protected $log;

    /**
     * Sets the instance of the database manager
     * @param ride\library\database\DatabaseManager $databaseManager
     * @return null
     */
    public function setDatabaseManager(DatabaseManager $databaseManager) {
        $this->databaseManager = $databaseManager;
    }

    /**
     * Sets the instance of the log
     * @param ride\library\log\Log $log
     * @return null
     */
    public function setLog(Log $log) {
        $this->log = $log;
    }

}