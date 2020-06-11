<?php


namespace EcomDev\AsyncMySQLBatcher;


interface PrepareHandler
{
    public function statementPrepared(StatementExecutor $statement): void;

    public function statementFailed(\Throwable $reason): void;
}