Sluggable
=========

## Описание

Sluggable - функционал генерации slug'ов.

## Использование

**1. Помечаем необходимые свойства сущности аннотацией "Darvin\Utils\Mapping\Annotation\Slug".**

Аргументы аннотации:

- **sourcePropertyPaths** *(требуется)* - массив путей до свойств-источников;
- **separator** *(опционально, по умолчанию - "/")* - разделитель частей slug'а.

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

После обновления slug'ов subscriber вызывает событие "Darvin\Utils\Event\SluggableEvents::SLUGS_UPDATED"
 ("darvin_utils.sluggable.slugs_updated"). Класс события - "Darvin\Utils\Event\SlugsUpdateEvent".

## Добавление обработчика slug'ов

Event subscriber использует обработчики slug'ов для постобработки сгенерированных slug'ов. Чтобы добавить новый обработчик:

**1. Создаем класс, реализующий "Darvin\Utils\Slug\SlugHandlerInterface".**

**2. Объявляем класс сервисом и помечаем его тегом "darvin_utils.slug_handler".**

Пример определения сервиса:

```yaml
parameters:
    darvin_content.slug.handler.unique.class: Darvin\ContentBundle\Slug\UniqueSlugHandler

services:
    darvin_content.slug.handler.unique:
        class: "%darvin_content.slug.handler.unique.class%"
        tags:
            - { name: darvin_utils.slug_handler }
```
