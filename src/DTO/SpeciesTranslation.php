<?php
declare(strict_types=1);

final class SpeciesTranslation
{
    public function __construct(
        public int $speciesId,
        public string $languageCode,
        public string $name,
        public ?string $description
    ) {}
}

