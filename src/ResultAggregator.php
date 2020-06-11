<?php

namespace EcomDev\AsyncMySQLBatcher;

interface ResultAggregator
{
    public function mainRecord(string $aggregateId, array $row): void;

    public function appendValue(string $aggregateId, string $field, $value): void;

    public function overrideValue(string $aggregateId, string $field, $value): void;

    public function flush(): void;
}