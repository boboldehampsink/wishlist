<?php
namespace verbb\wishlist\gql\types\generators;

use verbb\wishlist\elements\Item;
use verbb\wishlist\gql\arguments\ItemArguments;
use verbb\wishlist\gql\interfaces\ItemInterface;
use verbb\wishlist\gql\types\ItemType;

use Craft;
use craft\gql\base\Generator;
use craft\gql\base\GeneratorInterface;
use craft\gql\base\ObjectType;
use craft\gql\base\SingleGeneratorInterface;
use craft\gql\GqlEntityRegistry;
use craft\gql\TypeLoader;
use craft\gql\TypeManager;
use craft\helpers\Gql as GqlHelper;

class ItemGenerator extends Generator implements GeneratorInterface, SingleGeneratorInterface
{
    // Public Methods
    // =========================================================================

    public static function generateTypes($context = null): array
    {
        $type = static::generateType($context);
        return [$type->name => $type];
    }

    public static function generateType($context): ObjectType
    {
        $context = $context ?: Craft::$app->getFields()->getLayoutByType(Item::class);

        $typeName = Item::gqlTypeNameByContext(null);
        $contentFieldGqlTypes = self::getContentFields($context);
        $itemFields = TypeManager::prepareFieldDefinitions(array_merge(ItemInterface::getFieldDefinitions(), $contentFieldGqlTypes), $typeName);

        return GqlEntityRegistry::getEntity($typeName) ?: GqlEntityRegistry::createEntity($typeName, new ItemType([
            'name' => $typeName,
            'fields' => function() use ($itemFields) {
                return $itemFields;
            },
        ]));
    }
}
