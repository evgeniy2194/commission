<?php

namespace App\Commands;

abstract class BaseCommand
{
    protected function getArguments(): array
    {
        return $GLOBALS["argv"];
    }

    protected function output(string $message)
    {
        echo $message;
    }
}