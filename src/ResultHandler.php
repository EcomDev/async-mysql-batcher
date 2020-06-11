<?php


namespace EcomDev\AsyncMySQLBatcher;

interface ResultHandler
{
    public function processRow(array $row): void;
}