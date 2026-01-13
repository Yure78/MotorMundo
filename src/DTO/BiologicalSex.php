<?php
declare(strict_types=1);

final class BiologicalSex
{
    public function __construct(
        public ?int $id,
        public string $code,
        public bool $canGestate,
        public bool $canFertilize
    ) {}
}
