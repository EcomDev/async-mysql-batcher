<?php


namespace EcomDev\AsyncMySQLBatcher;

class ArrayResultAggregator implements ResultAggregator
{
    /**
     * @var ResultHandler
     */
    private $resultHandler;

    /**
     * @var array
     */
    private $rows = [];

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
        if (isset($this->rows[$aggregateId][$field])
            && !is_array($this->rows[$aggregateId][$field])) {
            $this->rows[$aggregateId][$field] = [$this->rows[$aggregateId][$field]];
        }

        $this->rows[$aggregateId][$field][] = $value;
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
}