<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\CustomPostTagMutations\Constants\GenericTagCRUDHookNames;
use PoPCMSSchema\TagMutations\MutationResolvers\AbstractMutateTagTermMutationResolver;
use PoPCMSSchema\TaxonomyMutations\Exception\TaxonomyTermCRUDMutationException;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

abstract class AbstractMutateGenericTagTermMutationResolver extends AbstractMutateTagTermMutationResolver
{
    protected function validateCreateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            GenericTagCRUDHookNames::VALIDATE_CREATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            GenericTagCRUDHookNames::VALIDATE_CREATE_OR_UPDATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        parent::validateCreateErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateUpdateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            GenericTagCRUDHookNames::VALIDATE_UPDATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            GenericTagCRUDHookNames::VALIDATE_CREATE_OR_UPDATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        parent::validateUpdateErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCreateOrUpdateTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = parent::getCreateOrUpdateTaxonomyTermData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(GenericTagCRUDHookNames::GET_CREATE_OR_UPDATE_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = parent::getUpdateTaxonomyTermData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(GenericTagCRUDHookNames::GET_UPDATE_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCreateTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = parent::getCreateTaxonomyTermData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(GenericTagCRUDHookNames::GET_CREATE_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function update(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $taxonomyTermID = parent::update($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        App::doAction(
            GenericTagCRUDHookNames::EXECUTE_CREATE_OR_UPDATE,
            $taxonomyTermID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            GenericTagCRUDHookNames::EXECUTE_UPDATE,
            $taxonomyTermID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        return $taxonomyTermID;
    }

    /**
     * @return string|int The ID of the created entity
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function create(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $taxonomyTermID = parent::create($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        App::doAction(GenericTagCRUDHookNames::EXECUTE_CREATE_OR_UPDATE, $taxonomyTermID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        App::doAction(GenericTagCRUDHookNames::EXECUTE_CREATE, $taxonomyTermID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        return $taxonomyTermID;
    }
}
