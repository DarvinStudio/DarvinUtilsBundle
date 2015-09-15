Sluggable
=========

## Описание

Sluggable - функционал генерации slug'ов.

## Использование

**1. Помечаем необходимые свойства сущности аннотацией "Darvin\Utils\Mapping\Annotation\Slug".**

Аргументы аннотации:

- **sourcePropertyPaths** - массив путей до свойств-источников;
- **separator** - разделитель частей slug'а (по умолчанию - "/").

Особенности:

- путь до свойства источника может содержать только одно отношение (обозначается символом ".": например, сущность
 содержит связанную сущность в свойстве "category", тогда путь до свойства "title" этой сущности будет выглядеть как
 "category.title"), за исключением последнего пути - тот вообще не должен содержать отношений;
- минимум одна составляющая slug'а должна быть не пуста;
- рекомендуется использовать аннотацию совместно с [аннотацией](transliteratable.md)
 "Darvin\Utils\Mapping\Annotation\Transliteratable".

Пример использования аннотации:

```php

use Darvin\Utils\Mapping\Annotation as Darvin;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Page
{
    /**
     * @var \AppBundle\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category")
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Darvin\Slug(sourcePropertyPaths={"category.slug", "title"}, separator="/")
     */
    private $slug;
}
```

**2. Event subscriber "darvin_utils.slug.subscriber" автоматически сгенерирует slug и присвоит его свойству Page::$slug
 (в приведенном выше примере).**

После обновления slug'ов subscriber вызывает событие "Darvin\Utils\Event\Events::POST_SLUGS_UPDATE"
 ("darvin_utils.post_slugs_update"). Класс события - "Darvin\Utils\Event\SlugsUpdateEvent".