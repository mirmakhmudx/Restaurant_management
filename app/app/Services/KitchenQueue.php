<?php

namespace App\Services;

use App\Contracts\KitchenCommandInterface;

class KitchenQueue
{
    private array $history = [];

    public function dispatch(KitchenCommandInterface $command): void
    {
        $command->execute();
        $this->history[] = $command;
    }

    public function undoLast(): void
    {
        if (empty($this->history)) {
            throw new \UnderflowException('No commands to undo.');
        }
        $command = array_pop($this->history);
        $command->undo();
    }

    public function getHistory(): array
    {
        return array_reverse($this->history);
    }

    public function hasHistory(): bool
    {
        return !empty($this->history);
    }
}
