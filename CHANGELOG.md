6.3.1: Add "mailer.prepend_host" bundle configuration parameter.

6.3.2: Pass "@router.default" instead of "@router" to the service "darvin_utils.routing.route_manager.cached" for
 compatibility with "jms/i18n-routing-bundle".

6.4.0: Always use locale "en" in the number and integer form view transformers.

6.4.2: Add generic tree sorter.

6.4.3: Init commands only in "dev" environment.

6.4.4: Mailer: allow to configure from name:

```yaml
darvin_utils:
    mailer:
        from_name: Почта России
```

6.4.5: Add title case translations command.

To convert translations file to title case use

```shell
$ /usr/bin/env php bin/console darvin:utils:translations:title-case <pathname>
```
