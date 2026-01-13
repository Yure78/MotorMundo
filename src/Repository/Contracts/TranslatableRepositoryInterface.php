<?php 
interface TranslatableRepositoryInterface
{
    public function findOne(int $entityId, string $lang): ?object;
    public function upsert(object $translation): void;
}

