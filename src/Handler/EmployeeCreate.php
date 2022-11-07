<?php

namespace App\Handler;

use App\Entity\Employee;
use App\Entity\EmployeeRepositoryPersist;
use App\Entity\EmployeeRepositoryRead;
use App\Reader\Csv;
use Symfony\Component\HttpFoundation\File\UploadedFile;


final class EmployeeCreate
{
    public function __construct(
        private readonly EmployeeRepositoryPersist $repositoryPersist,
        private readonly EmployeeRepositoryRead $repositoryRead,
        private readonly Csv $reader
    ) {
    }

    /**
     * @param UploadedFile $file
     *
     * @return void
     */
    public function handle(UploadedFile $file): void
    {
        $data = $this->reader->read($file);
        foreach ($data as $value) {
            $name = $value[0] ?? null;
            $parentName = $value[1] ?? null;
            $newEmployee = new Employee();

            if ($parentName) {
                $employee = $this->repositoryRead->getByName($parentName);
                $id = $employee->getId();
                $newEmployee->setName($name);
                $newEmployee->setParentId($id);
            } else {
                $newEmployee->setName($name);
            }

            $this->repositoryPersist->save($newEmployee);
        }
    }
}