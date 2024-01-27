<?php

namespace App\Serializer;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer implements NormalizerInterface
{
    /**
     * see: https://github.com/symfony/maker-bundle/issues/1252#issuecomment-1342477712
     */
    public function __construct(
        #[Autowire(service: 'api_platform.jsonld.normalizer.item')]
        private readonly NormalizerInterface $normalizer,
        private readonly Security            $security
    ) {
    }

    private const ALREADY_CALLED = 'USER_NORMALIZER_ALREADY_CALLED';

    public function normalize(mixed $object, string $format = null, array $context = []): float|int|bool|\ArrayObject|array|string|null
    {
        if (
            $object instanceof User &&
            isset($context['operation']) &&
            $context['operation']->getMethod() === 'GET' &&
            $this->security->getUser() === $object
        ) {
            $context['groups'][] = 'token:read';
        }
        $context[self::ALREADY_CALLED] = true;

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }
        return $data instanceof User;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            User::class => true
        ];
    }
}
