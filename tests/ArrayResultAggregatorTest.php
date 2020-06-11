<?php


namespace EcomDev\AsyncMySQLBatcher;

use PHPUnit\Framework\TestCase;
use function Amp\Dns\query;

class ArrayResultAggregatorTest extends TestCase
    implements ResultHandler
{
    /** @var ResultAggregator */
    private $aggregator;

    private $processedRows = [];

    protected function setUp(): void
    {
        $this->aggregator = new ArrayResultAggregator($this);
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
    public function appendingValueToMainRowResultsInArrayFieldInFlushedData()
    {
        $this->aggregator->mainRecord('SKU1', ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99]);
        $this->aggregator->appendValue('SKU1', 'color', 'Red');
        $this->aggregator->appendValue('SKU1', 'color', 'Blue');
        $this->aggregator->flush();

        $this->assertEquals(
            [
                ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99, 'color' => ['Red', 'Blue']]
            ],
            $this->processedRows
        );
    }

    /** @test */
    public function overridingValueForAppendedFieldReplacesItContents()
    {
        $this->aggregator->mainRecord('SKU1', ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99]);
        $this->aggregator->appendValue('SKU1', 'color', 'Red');
        $this->aggregator->appendValue('SKU1', 'color', 'Blue');
        $this->aggregator->overrideValue('SKU1', 'color', 'RedBlue');
        $this->aggregator->flush();

        $this->assertEquals(
            [
                ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99, 'color' => 'RedBlue']
            ],
            $this->processedRows
        );
    }

    /** @test */
    public function appendsToOverriddenValueOfARow()
    {
        $this->aggregator->mainRecord('SKU1', ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99]);
        $this->aggregator->overrideValue('SKU1', 'color', 'Orange');
        $this->aggregator->appendValue('SKU1', 'color', 'Red');
        $this->aggregator->appendValue('SKU1', 'color', 'Blue');
        $this->aggregator->flush();

        $this->assertEquals(
            [
                ['sku' => 'SKU1', 'type_id' => 'configurable', 'price' => 99.99, 'color' => ['Orange', 'Red', 'Blue']]
            ],
            $this->processedRows
        );
    }

    public function processRow(array $row): void
    {
        $this->processedRows[] = $row;
    }
}