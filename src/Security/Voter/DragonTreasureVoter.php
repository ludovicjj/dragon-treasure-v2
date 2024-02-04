<?php

namespace App\Security\Voter;

use App\Entity\DragonTreasure;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DragonTreasureVoter extends Voter
{
    const EDIT = 'EDIT';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if ($attribute === self::EDIT && $subject instanceof DragonTreasure) {
            return true;
        }

        return false;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var DragonTreasure $treasure */
        $treasure = $subject;

        if ($this->security->isGranted("ROLE_ADMIN")) {
            return true;
        }

        return match($attribute) {
            self::EDIT => $this->canEdit($treasure, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canEdit(DragonTreasure $dragonTreasure, User $user): bool
    {
        if (
            $this->security->isGranted("ROLE_TREASURE_EDIT") &&
            $dragonTreasure->getOwner() === $user
        ) {
            return true;
        }

        return false;
    }
}