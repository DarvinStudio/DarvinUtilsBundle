Authorization checker provider
==============================

## Описание

Authorization checker provider - провайдер сервиса "security.authorization_checker", необходим для обхода циклических
 зависимостей сервисов.

## Использование

Если сервис "security.authorization_checker" вызывает циклическую зависимость, для ее обхода можно воспользоваться
 провайдером этого сервиса:

```php
$authorizationChecker = $this->getContainer()->get('darvin_utils.security.authorization_checker_provider')->getAuthorizationChecker();
```
