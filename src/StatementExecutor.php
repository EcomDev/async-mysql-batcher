<?php


namespace EcomDev\AsyncMySQLBatcher;

interface StatementExecutor
{
    public function executeCommand(array $parameters, CommandHandler $handler): void;

    public function executeQuery(array $parameters, QueryResultHandler $handler): void;
}