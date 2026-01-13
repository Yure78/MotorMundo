<?php
declare(strict_types=1);

final class Species
{
    public function __construct(
        public ?int $id,
        public string $code,
        public int $avgLifespan,
        public int $maturityAge
    ) {}
}

