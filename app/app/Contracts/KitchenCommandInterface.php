<?php

namespace App\Contracts;

/**
 * Command Pattern — Interface for all kitchen commands.
 */
interface KitchenCommandInterface
{
    public function execute(): void;
    public function undo(): void;
    public function getDescription(): string;
}
