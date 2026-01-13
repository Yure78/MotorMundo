<?php
declare(strict_types=1);

final class MagicEnergyTranslation
{
    public function __construct(
        public int $magicEnergyId,
        public string $languageCode,
        public string $name,
        public ?string $description
    ) {}
}

