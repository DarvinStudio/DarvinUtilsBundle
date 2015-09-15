Object namer
============

## Описание

Object namer - сервис генерации имени объекта в нотации "under_score" по его классу, которое затем можно использовать,
 например, в строках переводов.

## Использование

Пример использования:

```php
$pageObjectName = $this->getContainer()->get('darvin_utils.object_namer')->name('AppBundle\\Entity\\Page');
echo $pageObjectName; // 'page'
```
