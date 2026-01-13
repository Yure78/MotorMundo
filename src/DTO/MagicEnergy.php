<?php
declare(strict_types=1);

final class MagicEnergy
{
    public function __construct(
        public ?int $id,
        public string $code
    ) {}
}

