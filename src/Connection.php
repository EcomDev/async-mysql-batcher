<?php


namespace EcomDev\AsyncMySQLBatcher;


interface Connection
{
    public function prepare(string $sql, PrepareHandler $handler): void;

    public function execute(string $commandSql, CommandHandler $handler): void;
}