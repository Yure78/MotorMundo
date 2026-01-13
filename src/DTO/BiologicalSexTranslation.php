<?php
declare(strict_types=1);

final class BiologicalSexTranslation
{
    public function __construct(
        public int $biologicalSexId,
        public string $languageCode,
        public string $label,
        public ?string $description
    ) {}
}
