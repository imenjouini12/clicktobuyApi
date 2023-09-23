<?php

namespace App\Service\Usr;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


class UserService implements UserServiceInterface
{

 private  $entitymanager;

public function __construct(EntityManagerInterface $entityManager)
{
$this->entityManager = $entityManager;

}

public function addUser(User $user)
{
    $this->entityManager->persist($user);
    $this->entityManager->flush();

}

public function updateUser(User $user)
{
    $this->entityManager->flush();
}

public function deleteUser(User $user)
{
    $this->entityManager->remove($user);
    $this->entityManager->flush();

}

public function getAllUsers ()
{

    return $this->entityManager->getRepository(User::class)->findAll();
}

public function getUserById(int $userId): ?User
{
    return $this->entityManager->getRepository(User::class)->findOneById($userId);
}




}









































?>