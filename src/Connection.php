<?php


namespace EcomDev\AsyncMySQLBatcher;


interface Connection
{
    public function prepare(string $statementSql, PrepareHandler $handler): void;

    public function execute(string $commandSql, CommandHandler $handler): void;

    public function acquire(): self;

    public function release(): self;
}