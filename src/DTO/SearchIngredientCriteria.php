<?php

declare(strict_types=1);

namespace App\DTO;

class SearchIngredientCriteria
{
    public ?string $title = null;
    public ?int $limit = null;
}
