Metadata factory
================

## Описание

Metadata factory - фабрика расширенных метаданных на базе аннотаций.

## Использование

Пример использования:

```php
$extendedMetadata = $this->getContainer()->get('darvin_utils.mapping.metadata_factory')->getExtendedMetadata('AppBundle:Page');
```

## Добавление драйвера аннотации

Metadata factory использует драйверы аннотаций для формирования метаданных. Для использования новой аннотации необходимо
 добавить ее драйвер:

**1. Создаем класс, реализующий "Darvin\Utils\Mapping\AnnotationDriver\AnnotationDriverInterface" или наследующийся от
 "Darvin\Utils\Mapping\AnnotationDriver\AbstractDriver".**

**2. Объявляем класс сервисом и помечаем его тегом "darvin_utils.annotation_driver".**

Если класс драйвера наследуется от
 "Darvin\Utils\Mapping\AnnotationDriver\AbstractDriver", то сервис может наследоваться от абстрактного сервиса
 "darvin_utils.mapping.annotation_driver.abstract".

Примеры:

```yaml
parameters:
    darvin_utils.mapping.annotation_driver.clonable.class: Darvin\Utils\Mapping\AnnotationDriver\ClonableDriver

    darvin_utils.mapping.annotation_driver.custom_object.class: Darvin\Utils\Mapping\AnnotationDriver\CustomObjectDriver

services:
    darvin_utils.mapping.annotation_driver.clonable:
        class:  "%darvin_utils.mapping.annotation_driver.clonable.class%"
        parent: darvin_utils.mapping.annotation_driver.abstract
        tags:
            - { name: darvin_utils.annotation_driver }

    darvin_utils.mapping.annotation_driver.custom_object:
        class: "%darvin_utils.mapping.annotation_driver.custom_object.class%"
        arguments:
            - "@annotation_reader"
        tags:
            - { name: darvin_utils.annotation_driver }
```
