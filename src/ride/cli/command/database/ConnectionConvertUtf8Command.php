<?php

namespace ride\cli\command\database;

use ride\cli\command\AbstractCommand;

use ride\library\database\definition\definer\Utf8Converter;
use ride\library\database\exception\DatabaseException;
use ride\library\database\DatabaseManager;

/**
 * Command to convert a database to UTF-8
 */
class ConnectionConvertUtf8Command extends AbstractCommand {

    /**
     * Initializes the command
     * @return null
     */
    protected function initialize() {
        $this->setDescription('Converts all tables from the provided database connection to UTF-8');

        $this->addArgument('name', 'Name of the connection', true);
    }

    /**
     * Invokes the command
     * @param \ride\library\database\DatabaseManager $databaseManager
     * @param string $name
     * @return null
     */
    public function invoke(DatabaseManager $databaseManager, $name = null) {
        $connection = $databaseManager->getConnection($name);

        $definer = $databaseManager->getDefiner($connection);
        if (!$definer instanceof Utf8Converter) {
            throw new DatabaseException('No UTF-8 converter available for the provided connection');
        }

        $definer->convertDatabaseToUTF8();
    }

}