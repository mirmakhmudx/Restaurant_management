<?php

namespace App\States;

class ConfirmedState extends OrderState
{
    public function getStatus(): string { return 'confirmed'; }
    public function getLabel(): string  { return 'Confirmed — Awaiting Kitchen'; }
    public function canConfirm(): bool       { return false; }
    public function canStartPreparing(): bool { return true; }
    public function canMarkReady(): bool      { return false; }
    public function canServe(): bool          { return false; }
    public function canBill(): bool           { return false; }
    public function canCancel(): bool         { return true; }
}
