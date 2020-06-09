<?php


namespace EcomDev\AsyncMySQLBatcher;

interface QueryHandler
{
    public function failedQuery(\Throwable $reason): void;

    public function resultSet(int $resultIndex): void;

    public function resultRow(array $row): void;

    public function resultEnd(): void;
}