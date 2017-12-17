# JSON Parsing (Draft)

JSON ([RFC 7159]) is the intended serialization of the [JSON Feed] format. As such, SimplePie NG has been designed from the beginning with support for multiple feed serialization types.

## High-Level Internals

Internally, we use [json_decode()](http://php.net/manual/en/function.json-decode.php) to parse the JSON data into an object. The internal JSON parser has been the very-fast [jsond] since [PHP 7.0](https://wiki.php.net/rfc/jsond).

TBD.

.. reviewer-meta::
   :written-on: 2017-12-17
   :proofread-on: 2017-12-17

  [JSON Feed]: https://jsonfeed.org
  [jsond]: https://github.com/bukka/php-jsond
  [RFC 7159]: https://tools.ietf.org/html/rfc7159
