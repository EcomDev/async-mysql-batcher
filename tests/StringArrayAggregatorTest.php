<?php

namespace EcomDev\AsyncMySQLBatcher;

use PHPUnit\Framework\TestCase;

class StringArrayAggregatorTest extends TestCase implements ResultHandler
{
    /** @var StringArrayResultAggregator */
    private $aggregator;

    private $processedRows = [];

    protected function setUp(): void
    {
        $this->aggregator = new StringArrayResultAggregator($this);
    }

    public function processRow(array $row): void
    {
        $this->processedRows[] = $row;
    }

    /** @test */
    public function gathersMultipleRowsOnFlush()
    {
        $this->aggregator->mainRecord('SKU1', ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99]);
        $this->aggregator->mainRecord('SKU2', ['sku' => 'SKU2', 'type_id' => 'simple', 'price' => 89.99]);

        $this->aggregator->flush();

        $this->assertEquals(
            [
                ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99],
                ['sku' => 'SKU2', 'type_id' => 'simple', 'price' => 89.99],
            ],
            $this->processedRows
        );
    }

    /** @test */
    public function doesNotNotifyProcessedRowsIfFlushWasNotInvoked()
    {
        $this->aggregator->mainRecord('SKU1', ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99]);
        $this->aggregator->mainRecord('SKU2', ['sku' => 'SKU2', 'type_id' => 'simple', 'price' => 89.99]);

        $this->assertEquals([], $this->processedRows);
    }

    /** @test */
    public function specifyingMainRecordWithTheSameAggregateIdOverridesExistingRowData()
    {
        $this->aggregator->mainRecord('SKU1', ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99]);
        $this->aggregator->mainRecord('SKU1', ['sku' => 'SKU1', 'type_id' => 'simple', 'price' => 89.99]);

        $this->aggregator->flush();

        $this->assertEquals(
            [
                ['sku' => 'SKU1', 'type_id' => 'simple', 'price' => 89.99]
            ],
            $this->processedRows
        );
    }

    /** @test */
    public function notifiesOfReceivedRowsOnlyOnce()
    {
        $this->aggregator->mainRecord('SKU1', ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99]);
        $this->aggregator->mainRecord('SKU2', ['sku' => 'SKU2', 'type_id' => 'simple', 'price' => 89.99]);

        $this->aggregator->flush();
        $this->aggregator->flush();

        $this->assertEquals(
            [
                ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99],
                ['sku' => 'SKU2', 'type_id' => 'simple', 'price' => 89.99],
            ],
            $this->processedRows
        );
    }

    /** @test */
    public function appendingValueToMainRowResultsInCommaSeparatedFieldInFlushedRow()
    {
        $this->aggregator->mainRecord('SKU1', ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99]);
        $this->aggregator->appendValue('SKU1', 'color', 'Red');
        $this->aggregator->appendValue('SKU1', 'color', 'Blue');
        $this->aggregator->flush();

        $this->assertEquals(
            [
                ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99, 'color' => 'Red,Blue']
            ],
            $this->processedRows
        );
    }

    /** @test */
    public function overridingAppendedValueResultsInOnlyOverridenValueBeingVisible()
    {
        $this->aggregator->mainRecord('SKU1', ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99]);
        $this->aggregator->appendValue('SKU1', 'color', 'Red');
        $this->aggregator->appendValue('SKU1', 'color', 'Blue');
        $this->aggregator->overrideValue('SKU1', 'color', 'Magenta');
        $this->aggregator->flush();

        $this->assertEquals(
            [
                ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99, 'color' => 'Magenta']
            ],
            $this->processedRows
        );
    }

    /** @test */
    public function allowsToConfigureCustomDelimiterForAnAppendedValue()
    {
        $this->aggregator = $this->aggregator->withDelimiter(':beer:');

        $this->aggregator->mainRecord('SKU1', ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99]);
        $this->aggregator->appendValue('SKU1', 'color', 'Red');
        $this->aggregator->appendValue('SKU1', 'color', 'Blue');
        $this->aggregator->flush();

        $this->assertEquals(
            [
                ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99, 'color' => 'Red:beer:Blue']
            ],
            $this->processedRows
        );
    }


}