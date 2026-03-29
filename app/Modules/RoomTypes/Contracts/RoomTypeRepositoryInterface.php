<?php

namespace RoomTypes\Contracts;

use RoomTypes\Infrastructure\Models\RoomType;
use Illuminate\Database\Eloquent\Collection;

interface RoomTypeRepositoryInterface
{
    public function all(mixed $request = null): mixed;
    public function findById(int $id): ?RoomType;
    public function create(array $data): RoomType;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
