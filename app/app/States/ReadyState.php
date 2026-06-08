<?php

namespace App\States;

class ReadyState extends OrderState
{
    public function getStatus(): string { return 'ready'; }
    public function getLabel(): string  { return 'Ready — Awaiting Service'; }
    public function canConfirm(): bool       { return false; }
    public function canStartPreparing(): bool { return false; }
    public function canMarkReady(): bool      { return false; }
    public function canServe(): bool          { return true; }
    public function canBill(): bool           { return false; }
    public function canCancel(): bool         { return false; }
}
