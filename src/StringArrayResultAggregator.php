<?php


namespace EcomDev\AsyncMySQLBatcher;

class StringArrayResultAggregator implements ResultAggregator
{
    /**
     * @var ResultHandler
     */
    private $resultHandler;

    /**
     * @var array
     */
    private $rows = [];

    /**
     * @var string
     */
    private $delimiter = ',';

    public function __construct(ResultHandler $resultHandler)
    {
        $this->resultHandler = $resultHandler;
    }

    public function mainRecord(string $aggregateId, array $row): void
    {
        $this->rows[$aggregateId] = $row;
    }

    public function appendValue(string $aggregateId, string $field, $value): void
    {
        $originalValue = $this->rows[$aggregateId][$field] ?? '';

        $this->rows[$aggregateId][$field] = $originalValue . ($originalValue ? $this->delimiter : '') . $value;
    }

    public function overrideValue(string $aggregateId, string $field, $value): void
    {
        $this->rows[$aggregateId][$field] = $value;
    }

    public function flush(): void
    {
        $rows = $this->rows;
        $this->rows = [];

        foreach ($rows as $row) {
            $this->resultHandler->processRow($row);
        }
    }

    public function withDelimiter(string $delimiter): self
    {
        $aggregator = clone $this;
        $aggregator->delimiter = $delimiter;
        return $aggregator;
    }
}