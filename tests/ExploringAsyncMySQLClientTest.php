<?php


namespace EcomDev\AsyncMySQLBatcher;

use Amp\Mysql;
use EcomDev\MySQLTestUtils\DatabaseFactory;
use PHPUnit\Framework\TestCase;

class ExploringAsyncMySQLClientTest extends TestCase
{
    static private $database;

    static public function setUpBeforeClass(): void
    {
        self::$database = (new DatabaseFactory())->createDatabase();
        self::$database->loadFixture(__DIR__ . '/fixtures/schema.sql');
        self::$database->loadCsv('product', __DIR__ . '/fixtures/data/product.csv');
        self::$database->loadCsv('product_int', __DIR__ . '/fixtures/data/product_int.csv');
        self::$database->loadCsv('product_decimal', __DIR__ . '/fixtures/data/product_decimal.csv');
        self::$database->loadCsv('product_varchar', __DIR__ . '/fixtures/data/product_varchar.csv');
        self::$database->loadCsv('product_text', __DIR__ . '/fixtures/data/product_text.csv');
    }

    static public function tearDownAfterClass(): void
    {
        self::$database = null;
    }

    /** @test */
    public function tryReadProducts()
    {
        $configuration = self::$database->provideConnectionOptions();

        $mysqlConfig = (new Mysql\ConnectionConfig($configuration['host']))
            ->withUser($configuration['user'])
            ->withPassword($configuration['password'])
            ->withDatabase($configuration['database'])
        ;

        \Amp\Loop::run(function () use ($mysqlConfig) {
            /* If you want ssl, pass as second argument an array with ssl options (an empty options array is valid too); if null is passed, ssl is not enabled either */
            $db = yield Mysql\connect($mysqlConfig);

            $result = yield $db->query('SELECT * FROM product');

            while (yield $result->advance()) {
                $row = $result->getCurrent();
                \var_dump($row);
            }

            echo "Character set changed\n";

            /* optional, as connection will automatically close when destructed. */
            $db->close();
        });

    }

}