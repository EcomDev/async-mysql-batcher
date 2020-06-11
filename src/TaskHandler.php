<?php


namespace EcomDev\AsyncMySQLBatcher;

interface TaskHandler
{
    public function taskHasFinished(Task $task): void;

    public function taskHasFailed(Task $task, \Throwable $reason): void;
}