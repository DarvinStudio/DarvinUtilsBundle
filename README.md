# DarvinUtilsBundle
This bundle provides "darvin-utils" library integration for Symfony2:

- [**Accessibility checker**](/Resources/doc/accessibility_checker.md) - сервис проверки доступности объекта для текущего
 пользователя;
- [**Authorization checker provider**](/Resources/doc/authorization_checker_provider.md) - провайдер сервиса
 "security.authorization_checker", необходим для обхода циклических зависимостей сервисов;
- [**Cloner**](/Resources/doc/cloner.md) - сервис клонирования сущностей;
- [**Custom entity loader**](/Resources/doc/custom_entity_loader.md) - сервис инициализации сущности в поле другой
 сущности с использованием атрибутов инициализации из свойств последней;
- [**Default value**](/Resources/doc/default_value.md) - функционал, позволяющий автоматически присваивать свойству
 сущности, значение которого не задано, значение другого свойства;
- [**Flash notifier**](/Resources/doc/flash_notifier.md) - сервис, упрощающий добавление flash-сообщений;
- [**Mailer**](/Resources/doc/mailer.md) - сервис отправки электронной почты;
- [**Metadata factory**](/Resources/doc/metadata_factory.md) - фабрика метаданных на базе аннотаций;
- [**New entity counter**](/Resources/doc/new_entity_counter.md) - сервис подсчета количества новых сущностей;
- [**Object namer**](/Resources/doc/object_namer.md) - сервис генерации имени объекта в нотации "under_score" по его
 классу, которое затем можно использовать, например, в строках переводов;
- [**Stringifier**](/Resources/doc/stringifier.md) - сервис приведения свойств объектов Doctrine к строке;
- [**Transliteratable**](/Resources/doc/transliteratable.md) - функционал, позволяющий автоматически транслитерировать
 значения свойств сущностей.

См. также:

- [**Драйверы аннотаций**](/Resources/doc/annotation_drivers.md);
- [**Конфигурация**](/Resources/doc/configuration.md);
- [**Список публичных сервисов**](/Resources/doc/services.md).
