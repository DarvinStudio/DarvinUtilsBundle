Entity manager provider
=======================

## Описание

Entity manager provider - провайдер сервиса "doctrine.orm.entity_manager", необходимый для обхода циклических
 зависимостей сервисов.

## Использование

Если сервис "doctrine.orm.entity_manager" вызывает циклическую зависимость, для ее обхода можно воспользоваться
 провайдером этого сервиса:

```php
$em = $this->getContainer()->get('darvin_utils.doctrine.orm.entity_manager_provider')->getEntityManager();
```
