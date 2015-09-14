Flash notifier
==============

## Описание

Flash notifier - сервис, упрощающий добавление flash-сообщений.

## Использование

Пример использования сервиса:

```php
$flashNotifier = $this->getContainer()->get('darvin_utils.flash.notifier');

$flashNotifier->done(true, 'All done.');
$flashNotifier->error('Error occurred!');
$flashNotifier->formError();
$flashNotifier->success('All done.');
```
