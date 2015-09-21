Templating provider
===================

## Описание

Templating provider - провайдер сервиса "templating", необходимый для обхода циклических зависимостей сервисов.

## Использование

Если сервис "templating" вызывает циклическую зависимость, для ее обхода можно воспользоваться провайдером этого сервиса:

```php
$templating = $this->getContainer()->get('darvin_utils.templating.provider')->getTemplating();
```
