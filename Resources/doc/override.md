Override
========

## Configuration

```yaml
darvin_utils:
    override:
        DarvinECommerceBundle:
            order:
                entities:
                    - Order\Order
                templates:
                    - admin/order
                    - order
            product:
                entities:
                    - Product
                templates:
                    - product
```

## Usage

### Override all functionality

```shell script
$ bin/console darvin:utils:override product 
```

### Override specific functionality

```shell script
$ bin/console darvin:utils:override product admin
```

### Specify bundle if subject name is ambiguous

```shell script
$ bin/console darvin:utils:override product -b DarvinECommerceBundle
```
