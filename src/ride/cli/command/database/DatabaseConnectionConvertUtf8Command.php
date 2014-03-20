<?php

namespace ride\cli\command\database;

use ride\library\database\definition\definer\Utf8Converter;

/**
 * Command to convert a database to UTF-8
 */
class DatabaseConnectionConvertUtf8Command extends AbstractDatabaseCommand {

    /**
     * Constructs a new database command
     * @return null
     */
    public function __construct() {
    	parent::__construct('database connection convert utf8', 'Converts all tables from the provided database connection to UTF-8');

    	$this->addArgument('name', 'Name of the connection', true);
    }

    /**
     * Executes the command
     * @return null
     */
    public function execute() {
    	$name = $this->input->getArgument('name');
    	$connection = $this->databaseManager->getConnection($name);

        $definer = $this->databaseManager->getDefiner($connection);
        if (!$definer instanceof Utf8Converter) {
            throw new Exception('No UTF-8 converter available for the provided connection');
        }

        $definer->convertDatabaseToUTF8();
    }

}