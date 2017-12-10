# Tools

## entities.php

This is used for generating a list of HTML 5 entities directly from the W3C's source list. This is not meant to be run by end users, but instead is used by the `Makefile` command:

```bash
make entities
```

## reporter.php

This is used when calling `make lint` to display the errors discovered by PHP Code_Sniffer. This is not meant to be run by end users.
