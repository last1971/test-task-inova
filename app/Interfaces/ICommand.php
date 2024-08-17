<?php

namespace App\Interfaces;

use App\Helpers\CommandResult;

/**
 * Command Pattern Interface
 */
interface ICommand
{
    public function execute(): CommandResult;
}
