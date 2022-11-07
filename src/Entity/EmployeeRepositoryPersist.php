<?php

namespace App\Entity;

interface EmployeeRepositoryPersist
{
    public function save(Employee $employee): void;
}