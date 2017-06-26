# Unit and Integration Testing (Draft)

Firstly, download and install the dependencies.

```bash
composer install --optimize-autoloader
```

## Unit Tests

You can run the unit tests as follows:

```bash
bin/phpunit --testsuite unit
```

When we write unit tests, we are testing that the single piece of code (i.e., function, method) does what it is supposed to be doing. A test should be very small and very focused. We will write lots and lots of these, and we will writes _mocks_ to simulate `Factory` classes.

## Integration Test Schema

You can run the integration tests as follows:

```bash
bin/phpunit --testsuite integration
```

When we write integration tests, we are testing that two pieces of code (e.g., classes) are working together to accomplish what they are supposed to be doing. An integration test may be larger than a unit test. We will write fewer of these, and will back them with known-good data.

## All at Once

You can run both unit and integration tests at the same time with:

```bash
make test
```

.. reviewer-meta::
   :written-on: 2017-06-25
   :proofread-on: 2017-06-25
