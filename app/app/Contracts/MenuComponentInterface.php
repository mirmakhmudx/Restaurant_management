<?php

namespace App\Contracts;

/**
 * Composite Pattern — Component Interface
 */
interface MenuComponentInterface
{
    public function getName(): string;
    public function getPrice(): float;
    public function getDescription(): string;
    public function isComposite(): bool;
}
