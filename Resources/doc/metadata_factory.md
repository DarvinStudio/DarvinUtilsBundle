Metadata factory
================

## Описание

Metadata factory - фабрика метаданных на базе аннотаций.

## Использование

Пример использования:

```php
$doctrineMetadata = $this->getContainer()->get('doctrine.orm.entity_manager')->getClassMetadata('AppBundle:Page');
$metadata = $this->getContainer()->get('darvin_utils.mapping.metadata_factory')->getMetadata($doctrineMetadata);
```

## Добавление драйвера аннотации

Metadata factory использует драйверы аннотаций для формирования метаданных. Для использования новой аннотации необходимо
 добавить ее драйвер:

1. Создаем класс, реализующий "Darvin\Utils\Mapping\AnnotationDriver\AnnotationDriverInterface" или наследующийся от
 "Darvin\Utils\Mapping\AnnotationDriver\AbstractDriver".

2. Объявляем класс сервисом и помечаем его тегом "darvin_utils.annotation_driver".
