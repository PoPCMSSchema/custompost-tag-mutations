<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\ObjectType\AbstractGenericTagsMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractObjectMutationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CustomPostMutationPayloadObjectTypeFieldResolver extends AbstractObjectMutationPayloadObjectTypeFieldResolver
{
    private ?GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver = null;

    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        if ($this->genericCustomPostObjectTypeResolver === null) {
            /** @var GenericCustomPostObjectTypeResolver */
            $genericCustomPostObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
            $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
        }
        return $this->genericCustomPostObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractGenericTagsMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getObjectFieldName(): string
    {
        return 'customPost';
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            $this->getObjectFieldName() => $this->getGenericCustomPostObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
