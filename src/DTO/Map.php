<?php
declare(strict_types=1);

final class Map
{
    public function __construct(
        public ?int $id,
        public string $name,
        public ?string $seed,
        public ?string $description,
        public ?string $createdAt = null
    ) {}
}
