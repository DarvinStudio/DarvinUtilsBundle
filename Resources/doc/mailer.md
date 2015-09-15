Mailer
======

## Описание

Mailer - сервис отправки электронной почты.

## Использование

Пример использования:

```php
$this->getContainer()->get('darvin_utils.mailer')->send(
    'email.hello.subject',
    'Hello, world!',
    'world@example.com',
    array(
        '%name%' => 'world',
    ),
    'text/plain'
);
```

Особенности:

- в качестве аргумента "subject" можно передавать строку перевода, при этом можно использовать аргумент "subjectParams"
 для передачи параметров перевода (см. пример выше);
- имя отправителя задается параметром "darvin_utils.mailer.from" [конфигурации](configuration.md) бандла;
- кодировка письма задается параметром "darvin_utils.mailer.charset" [конфигурации](configuration.md) бандла.
