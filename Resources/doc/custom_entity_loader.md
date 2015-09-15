Custom entity loader
====================

## Описание

Custom entity loader - сервис инициализации сущности в поле другой сущности с использованием атрибутов инициализации из
 свойств последней.

## Использование

**1. Помечаем свойство, в котором будет инициализирована сущность, аннотацией "Darvin\Utils\Mapping\Annotation\CustomObject".**

Аргументы аннотации:

- **class** - название класса инициализируемой сущности;
- **classPropertyPath** - путь до свойства, в котором хранится название класса инициализируемой сущности;
- **initProperty** - название свойства, по которому сущность будет инициализирована;
- **initPropertyValuePath** - путь до свойства, в котором хранится значение, по которому сущность будет инициализирована.

Особенности:

- первые два аргумента взаимоисключающие;
- свойство в "initProperty" должно быть уникальным (например, идентификатором).

Пример использования аннотации:

```php
use AppBundle\Entity\Post\Post;
use Darvin\Utils\Mapping\Annotation as Darvin;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Page
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $postId;

    /**
     * @var \AppBundle\Entity\Post\Post
     *
     * @Darvin\CustomObject(class="AppBundle\Entity\Post\Post", initProperty="id", initPropertyValuePath="postId")
     */
    private $post;
}
```

**2. Используем сервис "darvin_utils.custom_object.loader.entity" для инициализации сущности:**

```php
$page = new Page();
$page->setPostId(1);

$this->getContainer()->get('darvin_utils.custom_object.loader.entity')->loadForObject($page);
```

или

```php
$this->getContainer()->get('darvin_utils.custom_object.loader.entity')->loadForObjects(array($page));
```

Сервис осуществит поиск сущности "AppBundle\Entity\Post\Post" по значению свойства "id", взятому из "Page::$postId".
Если объект будет найден, ссылка на него будет присвоена свойству "Page::$post" (в приведенном выше примере).
