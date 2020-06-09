<?php


namespace EcomDev\AsyncMySQLBatcher;

interface Statement
{
    public function executeCommand(array $parameters, CommandHandler $handler): void;

    public function executeQuery(array $parameters, QueryHandler $handler): void;
}