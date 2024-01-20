<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\DragonTreasure;
use Symfony\Bundle\SecurityBundle\Security;

class TreasureOwnerProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly ProcessorInterface $decorated,
        private readonly Security           $security
    ) {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): DragonTreasure {
        if ($data instanceof DragonTreasure && $data->getOwner() === null) {
            $user = $this->security->getUser();
            $data->setOwner($user);
        }

        return $this->decorated->process($data, $operation, $uriVariables, $context);
    }
}