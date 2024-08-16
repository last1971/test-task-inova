<?php

namespace App\Interfaces;

use App\Helpers\CommandResult;

interface ICommand
{
    public function execute(): CommandResult;
}
