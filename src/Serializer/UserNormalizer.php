<?php

namespace App\Serializer;

use App\Entity\User;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator('api_platform.jsonld.normalizer.item')]
class UserNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    public function __construct(
        private readonly NormalizerInterface $decorated,
        private readonly Security $security
    ) {
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        if (
            $object instanceof User &&
            isset($context['operation']) &&
            $context['operation']->getMethod() === 'GET' &&
            $this->security->getUser() === $object
        ) {
            $context['groups'][] = 'token:read';
        }

        return $this->decorated->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            User::class => true,
        ];
    }

    public function setSerializer(SerializerInterface $serializer): void
    {
        if ($this->decorated instanceof SerializerAwareInterface) {
            $this->decorated->setSerializer($serializer);
        }
    }
}