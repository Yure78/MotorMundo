<?php
declare(strict_types=1);

final class AgeGroup
{
    public function __construct(
        public ?int $id,
        public string $code,
        public int $minAge,
        public int $maxAge
    ) {}
}

