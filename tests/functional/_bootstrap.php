<?php
// Here you can initialize variables that will be available to your tests


// configure database
\siestaphp\Config::getInstance("siesta.test.config.json");

\siestaphp\driver\ConnectionFactory::getConnection()->enableForeignKeyChecks();



