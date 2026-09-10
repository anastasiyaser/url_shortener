<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Service for managing user entities and operations.
 */
class UserService implements UserServiceInterface
{
    /**
     * Constructor.
     *
     * @param UserRepository              $userRepository UserRepository instance
     * @param UserPasswordHasherInterface $passwordHasher Password hasher service
     */
    public function __construct(private readonly UserRepository $userRepository, private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    /**
     * Register user.
     *
     * @param User   $user          User entity
     * @param string $plainPassword Plain password
     */
    public function register(User $user, string $plainPassword): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        $this->userRepository->save($user);
    }

    /**
     * Update user profile.
     *
     * @param User $user User entity
     */
    public function updateProfile(User $user): void
    {
        $this->userRepository->save($user);
    }

    /**
     * Change user password.
     *
     * @param User   $user          User entity
     * @param string $plainPassword Plain password
     */
    public function changePassword(User $user, string $plainPassword): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        $this->userRepository->save($user);
    }
}
