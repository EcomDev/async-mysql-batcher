<?php


namespace EcomDev\AsyncMySQLBatcher;

interface QueryResultHandler
{
    public function receiveRow(array $row): void;

    public function processNextResultSet(): void;

    public function processCompletion(): void;

    public function processFailure(\Throwable $reason): void;
}