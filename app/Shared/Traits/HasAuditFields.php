<?php

namespace App\Shared\Traits;

trait HasAuditFields
{
    public function getCreatedByAttribute(): ?int
    {
        return $this->attributes['created_by'] ?? null;
    }
}
