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

/**
 * Interface UserServiceInterface.
 */
interface UserServiceInterface
{
    /**
     * Register user.
     *
     * @param User   $user          User entity
     * @param string $plainPassword Plain password
     */
    public function register(User $user, string $plainPassword): void;

    /**
     * Update user profile.
     *
     * @param User $user User entity
     */
    public function updateProfile(User $user): void;

    /**
     * Change user password.
     *
     * @param User   $user          User entity
     * @param string $plainPassword Plain password
     */
    public function changePassword(User $user, string $plainPassword): void;
}
