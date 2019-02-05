Transliteratable
================

## Описание

Transliteratable - функционал, позволяющий автоматически транслитерировать значения свойств сущностей.

## Использование

**1. Помечаем нужные свойства сущности аннотацией "Darvin\Utils\Mapping\Annotation\Transliteratable".**

Аргументы аннотации:

- **sanitize** *(опционально, по умолчанию - true)* - нужно ли фильтровать спецсимволы;
- **allowedSymbols** *(опционально, по умолчанию - array("_"))* - массив допустимых спецсимволов, используется только
 при значении true аргумента "sanitize";
- **separator** *(опционально, по умолчанию - "-")* - разделитель слов, используется только при значении true аргумента
 "sanitize".

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
     * @ORM\Column
     * @Darvin\Transliteratable(sanitize=true, allowedSymbols={"_"}, separator="-")
     */
    private $slug;
}
```

**2. Event subscriber "darvin_utils.transliteratable.subscriber" автоматически транслитерирует значение
 свойства "Page::$slug" (в приведенном выше примере).**
