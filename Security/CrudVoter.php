<?php

namespace Dontdrinkandroot\DoctrineBundle\Security;

use Dontdrinkandroot\Repository\CrudOperation;
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
    protected function supports($attribute, $subject): bool
    {
        return self::handlesCrudFor($attribute, $subject, $this->getSupportedClass());
    }

    public static function handlesCrudFor($attribute, $subject, string $subjectClass): bool
    {
        return is_a($subject, $subjectClass, true) && in_array($attribute, CrudOperation::all(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        if ($this->getSupportedClass() === $subject) {
            switch ($attribute) {
                case CrudOperation::CREATE:
                    return $this->createGranted();
                case CrudOperation::READ:
                    return $this->readGranted();
                default:
                    return false;
            }
        }

        assert(is_a($subject, $this->getSupportedClass()));
        switch ($attribute) {
            case CrudOperation::CREATE:
                return $this->createGranted($subject);
            case CrudOperation::READ:
                return $this->readGranted($subject);
            case CrudOperation::UPDATE:
                return $this->updateGranted($subject);
            case CrudOperation::DELETE:
                return $this->deleteGranted($subject);
            default:
                return false;
        }
    }

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

    protected abstract function getSupportedClass(): string;
}
