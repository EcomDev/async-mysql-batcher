<?php


namespace EcomDev\AsyncMySQLBatcher;

interface Runtime
{
    public function run(): void;
}