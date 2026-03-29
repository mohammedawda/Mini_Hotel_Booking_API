<?php

namespace Hotels\Contracts;

use Hotels\Infrastructure\Models\Hotel;
use Illuminate\Database\Eloquent\Collection;

interface HotelRepositoryInterface
{
    public function all(mixed $request = null): mixed;
    public function findById(int $id): ?Hotel;
    public function create(array $data): Hotel;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
