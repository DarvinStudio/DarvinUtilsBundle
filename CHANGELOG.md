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

6.4.5:

- add title case translations command;

- convert .en translations to title case.

To convert translations file to title case use

```shell
$ /usr/bin/env php bin/console darvin:utils:translations:title-case <pathname>
```

6.4.6: Remove "symfony/symfony" from dependencies.

6.4.7: Force make services public by default.

6.5.0: Add "format_price" Twig filter.

6.5.3: Add "format" option to price formatter.

7.0.0:
 
- change dependency versions;

- remove outdated docs;

- add entity resolver.

7.0.3: Increase priority of override entities compiler pass.

7.0.4: Refactor stringifying booleans.

7.0.5: Add compress response event subscriber.

7.1.0: Move mailer to Mailer bundle.

7.1.1: Add "email_data()" and "table_data()" macros.

7.1.2: Rename "email_data()" macro to "div_data()". Remove "property()" macro.
