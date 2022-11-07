<?php

namespace App\Handler;

use App\Entity\Employee;
use App\Entity\EmployeeRepositoryRead;

final class EmployeeList
{
    public function __construct(
        private readonly EmployeeRepositoryRead $repositoryRead,
    )
    {
    }

    /**
     * @return Employee[]
     */
    public function handle(?string $name = null): array
    {
        return $this->repositoryRead->list($name);
    }
}