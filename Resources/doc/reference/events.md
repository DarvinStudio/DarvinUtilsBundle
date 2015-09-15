События
=======

### Darvin\Utils\Event\Events::POST_CLONE:
- **строковое представление** - "darvin_utils.post_clone";
- **класс события** - "Darvin\Utils\Event\CloneEvent";
- **кем вызывается** - сервисом "darvin_utils.cloner";
- **когда вызывается** - после клонирования сущности.

### Darvin\Utils\Event\Events::POST_SLUGS_UPDATE:
- **строковое представление** - "darvin_utils.post_slugs_update";
- **класс события** - "Darvin\Utils\Event\SlugsUpdateEvent";
- **кем вызывается** - event subscriber'ом "darvin_utils.slug.subscriber";
- **когда вызывается** - после обновления slug'ов.
