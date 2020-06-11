<?php


namespace EcomDev\AsyncMySQLBatcher;


interface CommandHandler
{
    public function processCompletion(int $affectedRows): void;

    public function processFailure(\Throwable $error): void;
}