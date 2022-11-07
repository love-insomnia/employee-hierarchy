<?php

declare(strict_types=1);

namespace App\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

abstract class DoctrineRepository
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    abstract function getEntityName(): string;

    /**
     * @return EntityManagerInterface
     */
    public function em(): EntityManagerInterface
    {
        return $this->em;
    }

    /**
     * @param $entity
     * @return void
     */
    protected function persist($entity): void
    {
        $this->em()->persist($entity);
        $this->em()->flush($entity);
    }

    protected function repository(): \Doctrine\ORM\EntityRepository|\Doctrine\Persistence\ObjectRepository
    {
        return $this->em()
            ->getRepository($this->getEntityName());
    }
}
