<?php


namespace EcomDev\AsyncMySQLBatcher;


interface CommandHandler
{
    public function successfulCommand(int $affectedRows): void;

    public function failedCommand(\Throwable $error): void;
}