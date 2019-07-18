<?php

namespace Dontdrinkandroot\DoctrineBundle\Security;

use Dontdrinkandroot\Repository\CrudAction;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
abstract class CrudVoter extends Voter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        return
            ($this->getSupportedClass() === $subject || is_a($subject, $this->getSupportedClass()))
            && in_array($attribute, CrudAction::all());
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if ($this->getSupportedClass() === $subject) {
            switch ($attribute) {
                case CrudAction::CREATE:
                    return $this->createGranted();
                case CrudAction::READ:
                    return $this->readGranted();
                    break;
                default:
                    return false;
            }
        }

        assert(is_a($subject, $this->getSupportedClass()));

        switch ($attribute) {
            case CrudAction::CREATE:
                return $this->createGranted($subject);
                break;
            case CrudAction::READ:
                return $this->readGranted($subject);
                break;
            case CrudAction::UPDATE:
                return $this->updateGranted($subject);
                break;
            case CrudAction::DELETE:
                return $this->deleteGranted($subject);
                break;
            default:
                return false;
        }
    }

    protected abstract function getSupportedClass(): string;

    protected function createGranted(object $subject = null): bool
    {
        return false;
    }

    protected function readGranted(object $subject = null): bool
    {
        return true;
    }

    protected function updateGranted(object $subject = null): bool
    {
        return false;
    }

    protected function deleteGranted(object $subject = null): bool
    {
        return false;
    }
}
