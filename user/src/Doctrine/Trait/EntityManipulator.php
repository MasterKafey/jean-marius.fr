<?php

namespace App\Doctrine\Trait;

use App\Doctrine\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionException;

trait EntityManipulator
{
    public function findAll(string $className): iterable
    {
        return $this->findBy($className, []);
    }

    public function findBy(string $className, array $criteria): iterable
    {
        return $this->getEntityManager()->getRepository($className)->findBy($criteria);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findOneBy(string $className, array $criteria): object
    {
        $entity = $this->getEntityManager()->getRepository($className)->findOneBy($criteria);

        if (null === $entity) {
            throw new EntityNotFoundException($className, $criteria);
        }

        return $entity;
    }

    public function save(object $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws ReflectionException
     */
    public function update(object $entity, array|object $data, bool $flush = true): void
    {
        $reflectionClass = new \ReflectionClass($entity);

        if (is_object($data)) {
            $reflectionDataClass = new \ReflectionClass($data);
            $newData = [];
            foreach ($reflectionDataClass->getProperties() as $property) {
                $property->setAccessible(true);
                $newData[$property->getName()] = $property->getValue($data);
            }
            $data = $newData;
        }

        foreach ($data as $propertyName => $newValue) {
            $property = $reflectionClass->getProperty($propertyName);

            $property->setAccessible(true);
            $property->setValue($entity, $newValue);
        }

        $this->save($entity, $flush);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function remove(string $className, int $id, bool $flush = true): void
    {
        $entity = $this->findOneBy($className, ['id' => $id]);

        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public abstract function getEntityManager(): EntityManagerInterface;
}