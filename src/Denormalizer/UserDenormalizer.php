<?php

declare(strict_types=1);

namespace App\Denormalizer;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UserDenormalizer implements DenormalizerAwareInterface, DenormalizerInterface
{
    use DenormalizerAwareTrait;

    public const ALREADY_CALLED = 'USER_DENORMALIZER_ALREADY_CALLED';

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private Security $security
    ) {
    }

    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        if (!isset($context[self::ALREADY_CALLED]) && User::class === $type) {
            return true;
        }

        return false;
    }

    /**
     * @throws ExceptionInterface
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        $context[self::ALREADY_CALLED] = true;
        $user = $this->security->getUser();

        if (null !== $user) {
            if (isset($data['password'])) {
                $data['password'] = $this->passwordHasher->hashPassword($user, $data['password']);
            }
        }

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }
}