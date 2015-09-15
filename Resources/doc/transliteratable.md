Transliteratable
================

## Описание

Transliteratable - функционал, позволяющий автоматически транслитерировать значения свойств сущностей.

## Использование

**1. Помечаем нужные свойства сущности аннотацией "Darvin\Utils\Mapping\Annotation\Transliteratable".**

Аргументы аннотации:

- **sanitize** - нужно ли фильтровать спецсимволы (по умолчанию - true);
- **allowedSymbols** - массив допустимых спецсимволов (по умолчанию - array("_")), используется только при значении "true" аргумента "sanitize";
- **separator** - разделитель слов (по умолчанию - "-"), используется только при значении "true" аргумента "sanitize".

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
     * @var string
     *
     * @ORM\Column(type="string")
     * @Darvin\Transliteratable(sanitize=true, allowedSymbols={"_"}, separator="-")
     */
    private $slug;
}
```

**2. On flush event subscriber "darvin_utils.transliteratable.subscriber" автоматически транслитерирует значение
 свойства "Page::$slug" (в приведенном выше примере).**
