Stringifier
===========

## Описание

Stringifier - сервис приведения свойств объектов Doctrine к строке.

## Использование

Пример использования:

```php
$page = new Page();
$page->setTags(array('hello', 'world'));
$tagsString = $this->getContainer()->get('darvin_utils.stringifier.doctrine')->stringify($page->getTags(), Type::SIMPLE_ARRAY);
echo $tagsString; // '["hello","world"]'
```

Особенности:

- значением аргумента "dataType" должна являться константа класса "Doctrine\DBAL\Types\Type".
