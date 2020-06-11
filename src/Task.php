<?php


namespace EcomDev\AsyncMySQLBatcher;


interface Task
{
    public function execute(Connection $connection, TaskHandler $handler): void;
}