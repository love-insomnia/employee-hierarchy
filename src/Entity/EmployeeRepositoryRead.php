<?php

namespace App\Entity;

interface EmployeeRepositoryRead
{
    public function list(?string $name): array;
    public function getById(int $id): ?Employee;
    public function getByName(string $name): ?Employee;
}