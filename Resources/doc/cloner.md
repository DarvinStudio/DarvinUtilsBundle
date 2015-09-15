Cloner
======

## Описание

Cloner - сервис клонирования сущностей.

## Использование

**1. Помечаем сущность как клонируемую, используя аннотацию "Darvin\Utils\Mapping\Annotation\Clonable\Clonable".**

С помощью аргумента "copyingPolicy" можно выбрать одну из двух стратегий копирования свойств:

- "NONE" (по умолчанию): копировать значения только тех свойств, которые помечены "Darvin\Utils\Mapping\Annotation\Clonable\Copy";
- "ALL": копировать значения всех свойств, за исключением помеченных аннотацией "Darvin\Utils\Mapping\Annotation\Clonable\Skip".

Особенности:

- идентификатор сущности не должен быть копируемым;
- если значением свойства является сущность, она должна быть также клонируемой.

Пример клонируемой сущности:

```php
use Darvin\Utils\Mapping\Annotation\Clonable as Clonable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @Clonable\Clonable(copyingPolicy="ALL")
 */
class Page
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", unique=true)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Id
     * @Clonable\Skip
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;
}
```

**2. Клонируем сущность с помощью сервиса "darvin_utils.cloner":**

```php
$page = new Page();
$page->setContent('Hello, world!');

$pageClone = $this->getContainer()->get('darvin_utils.cloner')->createClone($page);
```

После клонирования сущности (непосредственного или сущности в свойстве) вызывается событие
 "Darvin\Utils\Event\Events::POST_CLONE" ("darvin_utils.post_clone"). Класс события - "Darvin\Utils\Event\CloneEvent".
