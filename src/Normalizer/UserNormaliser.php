<?php

namespace App\Normalizer;

use App\Entity\User;
use ArrayObject;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsDecorator('api_platform.jsonld.normalizer.item')]
class UserNormaliser implements NormalizerInterface, SerializerAwareInterface
{
    public function __construct(
        private readonly NormalizerInterface $decorated,
        private readonly Security            $security
    ) {
    }

    public function normalize(mixed $object, string $format = null, array $context = []): array|ArrayObject|bool|float|int|null|string
    {
        if ($object instanceof User && $this->security->getUser() === $object) {
            $context['groups'][] = 'token:read';
        }

        return $this->decorated->normalize($object, $format, $context);
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $this->decorated->supportsNormalization($data, $format, $context);
    }

    public function setSerializer(SerializerInterface $serializer): void
    {
        if ($this->decorated instanceof SerializerAwareInterface) {
            $this->decorated->setSerializer($serializer);
        }
    }
}