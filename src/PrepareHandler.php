<?php


namespace EcomDev\AsyncMySQLBatcher;


interface PrepareHandler
{
    public function statementIsReady(string $statementId, Statement $statement): void;

    public function statementIsInvalid(string $statementId, \Throwable $reason): void;
}