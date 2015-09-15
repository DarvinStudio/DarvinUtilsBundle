Accessibility checker
=====================

## Описание

Accessibility checker - сервис проверки доступности объекта для текущего пользователя.

## Использование

Класс объекта, доступность которого будет проверяться, должен реализовывать интерфейс "Darvin\Utils\Security\SecurableInterface".

Пример проверки доступности объекта:

```php
$pageIsAccessible = $this->getContainer()->get('darvin_utils.security.accessibility_checker')->isAccessible($page);
```

Сервис вернет значение "true", если текущий пользователь имеет хотя бы одну из ролей, возвращаемых методом
 "Darvin\Utils\Security\SecurableInterface::getAllowedRoles()".
