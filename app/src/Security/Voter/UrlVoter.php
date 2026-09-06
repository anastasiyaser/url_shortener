<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security\Voter;

use App\Entity\Url;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Url voter.
 */
final class UrlVoter extends Voter
{
    /**
     * Delete permission.
     */
    public const DELETE = 'URL_DELETE';

    /**
     * Edit permission.
     */
    public const EDIT = 'URL_EDIT';

    /**
     * View permission.
     */
    public const VIEW = 'URL_VIEW';

    /**
     * Determines if this voter supports the attribute and subject.
     *
     * @param string $attribute Attribute
     * @param mixed  $subject   Subject
     *
     * @return bool Result
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::DELETE, self::EDIT, self::VIEW], true)
            && $subject instanceof Url;
    }

    /**
     * Perform a single access check operation.
     *
     * @param string         $attribute Attribute
     * @param mixed          $subject   Subject
     * @param TokenInterface $token     Token
     *
     * @return bool Result
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Grant full access to administrator users.
        if ($user instanceof User && in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        if (!$subject instanceof Url) {
            return false;
        }

        return match ($attribute) {
            self::EDIT => $this->canEdit($subject, $user),
            self::DELETE => $this->canDelete($subject, $user),
            self::VIEW => $this->canView($subject, $user),
            default => false,
        };
    }

    /**
     * Checks if user can edit url.
     *
     * @param Url                $url  Url entity
     * @param UserInterface|null $user User
     *
     * @return bool Result
     */
    private function canEdit(Url $url, ?UserInterface $user): bool
    {
        return $user instanceof User && $url->getUser() === $user;
    }

    /**
     * Checks if user can delete url.
     *
     * @param Url                $url  Url entity
     * @param UserInterface|null $user User
     *
     * @return bool Result
     */
    private function canDelete(Url $url, ?UserInterface $user): bool
    {
        return $user instanceof User && $url->getUser() === $user;
    }

    /**
     * Checks if a user can view a url.
     *
     * @param Url                $url  Url entity
     * @param UserInterface|null $user User
     *
     * @return bool Result
     */
    private function canView(Url $url, ?UserInterface $user): bool
    {
        if (null === $url->getUser()) {
            return true;
        }

        return $user instanceof User && $url->getUser() === $user;
    }
}
