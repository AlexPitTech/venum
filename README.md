### vEnum Collection

The **PitTech\vEnum** library provides a convenient and powerful tool for working with enumerations (Enum, Object Constants) in PHP. 
It offers an interface that allows extending enums with additional metadata and managing them. The library supports filtering and transformations.

### Key Features of the Library:

1. **Creating Enums with Metadata:**
   - Each enum element can be enriched with values (payload, label, tags, and active status).
   - For example, in the `Animals` enum, each element (e.g., `LEO`, `JAGUAR`) has a unique identifier (payload) and a set of tags that allow grouping elements by specific criteria.

2. **Filtering Elements:**
   - The library allows filtering enum elements by tags. For example, you can retrieve only those elements that belong to the "cats" category (`CATS`).

3. **Working with Active and Inactive Elements:**
   - Enum elements can be marked as active or inactive. This is useful, for instance, when an entity type is no longer relevant but still exists in the database.

4. **Searching and Transforming Values:**
   - The library provides methods for searching values by identifiers and transforming arrays of identifiers into corresponding enum values.

5. **Flexibility in Usage:**
   - The library can work with both Enums and classic objects or interfaces where constants are used as enum elements.

### Installation via composer

```cmd

composer require pit-tech/v-enum

```


### Example of Usage:

```php
enum Animals: string
{
    use EnumTrait;

    // several groups of animals
    const SPOTTED = 'spotted';
    const HAS_MANE = 'hasMane';
    const CATS = 'cats';
    const GIRAFFIDAE = 'giraffidae';

    // 123 - is external id  
    #[vEnum(123, tags: [self::CATS, self::HAS_MANE])]
    case LEO = 'leo'; // 'leo' - is local unique id of animal 

    #[vEnum(124, tags: [self::CATS, self::SPOTTED])]
    case JAGUAR = 'jaguar';

    #[vEnum(125, tags: [self::CATS])]
    case PANTHER = 'panther';

    // Guepard - is title of the enum value 
    #[vEnum(126, title: 'Guepard', [self::CATS, self::SPOTTED])]
    case GUEPARD = 'guepard';

    #[vEnum(127, tags: [self::GIRAFFIDAE, self::SPOTTED])]
    case GIRAFFE = 'giraffe';

}

```

- In this example, an `Animals` enum is created, where each element has a unique identifier (e.g., `123` for `LEO`) and a set of tags (e.g., `CATS` and `HAS_MANE`).

```php
    // get all external ids of animals belonging to the cats group and having spots
    Animals::filter([Animals::CATS, Animals::SPOTTED => true])->payloads();
    // get all external ids of animals belonging to the cats group and having spots
    Animals::filter([Animals::HAS_MANE])->values();
    
    // enums can be mapped with callback and transform to entries in your API
    Animals::map(fn(vEnum $vEnum) => [
        'label' => $vEnum->label ?? ucfirst($vEnum->value). ' as default',
        'value' => $vEnum->value,
    ])
}
```

### Advantages of the library:

- **Flexibility:** Using PHP Attributes, it allows adding additional parameters to enums.
- **Convenience:** Simplifies working with enums by providing ready-made methods for searching and filtering.
- **Integration:** Easily integrates with existing projects and testing frameworks.

The **PitTech\vEnum** library is a powerful tool for PHP developers who want to improve the structure and readability of their code when working with enums.

