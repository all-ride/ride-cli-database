<?php

namespace pallo\cli\command\database;

use pallo\library\cli\command\AbstractCommand;
use pallo\library\database\DatabaseManager;
use pallo\library\log\Log;

/**
 * Abstract database command
 */
abstract class AbstractDatabaseCommand extends AbstractCommand {

    /**
     * Instance of the database manager
     * @var pallo\library\database\DatabaseManager
     */
    protected $databaseManager;

    /**
     * Instance of the log
     * @var pallo\library\log\Log
     */
    protected $log;

    /**
     * Sets the instance of the database manager
     * @param pallo\library\database\DatabaseManager $databaseManager
     * @return null
     */
    public function setDatabaseManager(DatabaseManager $databaseManager) {
        $this->databaseManager = $databaseManager;
    }

    /**
     * Sets the instance of the log
     * @param pallo\library\log\Log $log
     * @return null
     */
    public function setLog(Log $log) {
        $this->log = $log;
    }

}