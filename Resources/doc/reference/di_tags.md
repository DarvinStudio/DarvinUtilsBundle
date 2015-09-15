DI-теги
=======

### darvin_utils.annotation_driver:

- **что помечается** - драйвер аннотаций;
- **для чего используется** - для инъекции драйвера аннотаций в [Metadata factory](../metadata_factory.md)
 "darvin_utils.mapping.metadata_factory";
- **требования** - класс драйвера должен реализовывать "Darvin\Utils\Mapping\AnnotationDriver\AnnotationDriverInterface"
 или наследоваться от "Darvin\Utils\Mapping\AnnotationDriver\AbstractDriver".

### darvin_utils.slug_handler:

- **что помечается** - обработчик slug'ов;
- **для чего используется** - для инъекции обработчика slug'ов в [Sluggable](../sluggable.md) event subscriber
 "darvin_utils.slug.subscriber";
- **требования** - класс обработчика должен реализовывать "Darvin\Utils\Slug\SlugHandlerInterface".
