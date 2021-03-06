Default value
=============

## Описание

Default value - функционал, позволяющий автоматически присваивать свойству сущности, значение которого не задано, значение
 другого свойства.

## Использование

**1. Помечаем целевое свойство аннотацией "Darvin\Utils\Mapping\Annotation\DefaultValue".**

Аргументы аннотации:

- **sourcePropertyPath** *(требуется)* - путь до свойства-источника.

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
     * @var string
     *
     * @ORM\Column
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column
     * @Darvin\DefaultValue(sourcePropertyPath="title")
     */
    private $metaTitle;
}
```

**2. Event subscriber "Darvin\Utils\EventListener\DefaultValueSubscriber" автоматически присвоит свойству
 "Page::$metaTitle" значение из "Page::$title", если значение первого будет не задано (в приведенном выше примере).**
