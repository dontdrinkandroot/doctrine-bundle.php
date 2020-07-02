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
                    break;
                default:
                    return false;
            }
        }

        assert(is_a($subject, $this->getSupportedClass(), true));
        switch ($attribute) {
            case CrudOperation::CREATE:
                return $this->createGranted($subject);
                break;
            case CrudOperation::READ:
                return $this->readGranted($subject);
                break;
            case CrudOperation::UPDATE:
                return $this->updateGranted($subject);
                break;
            case CrudOperation::DELETE:
                return $this->deleteGranted($subject);
                break;
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
