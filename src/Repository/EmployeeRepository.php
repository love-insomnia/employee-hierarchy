<?php

declare(strict_types=1);

namespace App\Repository;

use App\Doctrine\DoctrineRepository;
use App\Entity\Employee;
use App\Entity\EmployeeRepositoryPersist;
use App\Entity\EmployeeRepositoryRead;
use Doctrine\ORM\Query\ResultSetMapping;

final class EmployeeRepository extends DoctrineRepository implements EmployeeRepositoryRead, EmployeeRepositoryPersist
{
    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return Employee::class;
    }

    /**
     * @return Employee[]
     */
    public function list(?string $name): array
    {

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(Employee::class, 'e');
        $rsm->addFieldResult('e', 'id', 'id');
        $rsm->addFieldResult('e', 'parent_id', 'parentId');
        $rsm->addFieldResult('e', 'name', 'name');
        $rsm->addFieldResult('e', 'path', 'path');

        if (isset($name)) {
            $sql = "
                WITH RECURSIVE r AS (
                    SELECT id, parent_id, name, CAST (name as text) as path
                        FROM employee
                        WHERE name = ?
                        UNION ALL
                    
                    SELECT employee.id, employee.parent_id, employee.name, CAST (r.path || ' -> '|| employee.name as text)
                    FROM employee
                             JOIN r
                                  ON employee.parent_id = r.id
                )
                
                SELECT * FROM r ORDER BY parent_id DESC LIMIT 1;
            ";
            $query = $this->em()
                ->createNativeQuery($sql, $rsm);
            $query->setParameter(1, $name);
            return $query->getResult();
        }

        $sql = "
        WITH RECURSIVE r AS (
            SELECT id, parent_id, name, CAST (name as text) as path
            FROM employee
            WHERE parent_id IS NULL
            UNION ALL
        
            SELECT employee.id, employee.parent_id, employee.name, CAST (r.path || ' -> ' || employee.name as text)
            FROM employee
                     INNER JOIN r
                          ON employee.parent_id = r.id
        )
        
        SELECT * FROM r;
        ";

        $query = $this->em()
            ->createNativeQuery($sql, $rsm);

        return $query->getResult();
    }

    /**
     * @param int $id
     *
     * @return Employee|null
     */
    public function getById(int $id): ?Employee
    {
        return $this->repository()->find($id);
    }

    /**
     * @param string $name
     *
     * @return Employee|null
     */
    public function getByName(string $name): ?Employee
    {
        return $this->repository()->findOneBy(['name' => $name]);
    }

    /**
     * @param Employee $employee
     *
     * @return void
     */
    public function save(Employee $employee): void
    {
        $this->persist($employee);
    }
}