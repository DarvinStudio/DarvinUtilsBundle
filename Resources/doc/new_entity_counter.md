New entity counter
==================

## Описание

New entity counter - сервис подсчета количества новых сущностей.

## Использование

**1. Помечаем свойство, являющееся критерием новизны объекта, аннотацией "Darvin\Utils\Mapping\Annotation\NewObjectFlag".**

Пример использования аннотации:

```php
use Darvin\Utils\Mapping\Annotation as Darvin;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Page
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     * @Darvin\NewObjectFlag
     */
    private $new;
}
```

**2. Используем метод "count()" сервиса "darvin_utils.new_object.counter" для подсчета количества новых сущностей:**

```php
$newPagesCount = $this->getContainer()->get('darvin_utils.new_object.counter')->count('AppBundle:Page');
```

Для проверки возможности подсчета новых сущностей того или иного класса сервис содержит метод "isCountable()".

Методы сервиса доступны в Twig - это функции "utils_count_new_objects()" и "utils_new_objects_countable()" соответственно.
