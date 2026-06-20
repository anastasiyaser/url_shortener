<?php

/**
 * Url voter.
 */

namespace App\Security\Voter;

use App\Entity\Url;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UrlVoter.
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
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::DELETE, self::EDIT, self::VIEW])
            && $subject instanceof Url;
    }

    /**
     * Perform a single access check operation.
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // 🚀 KOŁO RATUNKOWE DLA ADMINA: Jeśli użytkownik jest zalogowany i ma rolę ADMINA -> pozwól na wszystko!
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
     */
    private function canEdit(Url $url, ?UserInterface $user): bool
    {
        // Gość nie może edytować niczego. Zalogowany użytkownik edytuje tylko swoje.
        return $user instanceof User && $url->getUser() === $user;
    }

    /**
     * Checks if user can delete url.
     */
    private function canDelete(Url $url, ?UserInterface $user): bool
    {
        // Gość nie może usuwać niczego. Zalogowany użytkownik usuwa tylko swoje.
        return $user instanceof User && $url->getUser() === $user;
    }

    /**
     * Checks if a user can view a url.
     */
    private function canView(Url $url, ?UserInterface $user): bool
    {
        // WYMÓG PROJEKTU: Jeśli adres nie ma przypisanego usera (został stworzony przez gościa),
        // to każdy (nawet niezalogowany $user === null) ma prawo zobaczyć statystyki tego adresu na liście!
        if ($url->getUser() === null) {
            return true;
        }

        // Jeśli adres należy do konkretnego użytkownika, tylko ten użytkownik może go zobaczyć.
        return $user instanceof User && $url->getUser() === $user;
    }
}
