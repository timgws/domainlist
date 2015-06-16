# DomainList

A quick class for managing lists of domains. Nothing special.

[![Test Coverage](https://codeclimate.com/github/timgws/domainlist/badges/coverage.svg)](https://codeclimate.com/github/timgws/domainlist/coverage)
[![Code Climate](https://codeclimate.com/github/timgws/domainlist/badges/gpa.svg)](https://codeclimate.com/github/timgws/domainlist)

## Example of adding domains
```php
    $list = new DomainList('ing');

    $list->add([
        $list->c('the awesome   € shop'),
        $list->c('hex-follows-0xaf--0xfacebeef'),
        $list->c('--lol-0xfacebeef')
    ]);

    $domains = $list->get();

    //---
    $domains = [
        'hex-follows-0xaf-0xfacebeef.ing',
        'hexfollows0xaf0xfacebeef.ing',
        'the-awesome-eur-shop.ing',
        'theawesomeeurshop.ing'
    ];
```

This class should validate that domains match [RFC 1035](https://tools.ietf.org/html/rfc1035) valid domains.

