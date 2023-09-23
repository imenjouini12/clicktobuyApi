<?php

namespace App\Service\Usr;

use App\Entity\User;



interface UserServiceInterface
{

    public function addUser(User $user);

    public function updateUser(User $user);

    public function deleteUser(User $user);

    public function getAllUsers ();

    public function getUserById(int $userId): ?User;


}






















?>