<div align="center"><img src="logo.png" width="500"><br></div>

----

**Highly experimental. Don't use this.**

**SimplePie NG** is a modern, _next-generation_ PHP package for working with syndication feeds. It was re-written from the ground-up to take advantage of the modern features of PHP 7+.

[![Medium](https://img.shields.io/badge/medium-simplepie--ng-blue.svg?style=for-the-badge)](https://medium.com/simplepie-ng)
[![Follow](https://img.shields.io/twitter/follow/simplepie_ng.svg?style=for-the-badge&label=Twitter)](https://twitter.com/intent/follow?screen_name=simplepie_ng)


## Badges
### Health

![PHP Version](https://img.shields.io/packagist/php-v/simplepie/simplepie-ng.svg?style=for-the-badge)
[![Release](https://img.shields.io/github/release/simplepie/simplepie-ng.svg?style=for-the-badge)](https://github.com/simplepie/simplepie-ng/releases/latest)
[![Open Issues](http://img.shields.io/github/issues/simplepie/simplepie-ng.svg?style=for-the-badge)](https://github.com/simplepie/simplepie-ng/issues)
[![Pull Requests](https://img.shields.io/github/issues-pr/simplepie/simplepie-ng.svg?style=for-the-badge)](https://github.com/simplepie/simplepie-ng/pulls)
[![Contributors](https://img.shields.io/github/contributors/simplepie/simplepie-ng.svg?style=for-the-badge)](https://github.com/simplepie/simplepie-ng/graphs/contributors)
[![Repo Size](https://img.shields.io/github/repo-size/simplepie/simplepie-ng.svg?style=for-the-badge)](https://github.com/simplepie/simplepie-ng/pulse/monthly)

### Quality

[![Travis branch](https://img.shields.io/travis/simplepie/simplepie-ng/master.svg?style=for-the-badge&label=Travis%20CI)](https://travis-ci.org/simplepie/simplepie-ng)
[![Code Quality](http://img.shields.io/scrutinizer/g/simplepie/simplepie-ng.svg?style=for-the-badge&label=Scrutinizr)](https://scrutinizer-ci.com/g/simplepie/simplepie-ng)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/1b772338-fd6a-4af1-8f5e-fddffc2b9d43.svg?style=for-the-badge&label=Insight)](https://insight.sensiolabs.com/projects/1b772338-fd6a-4af1-8f5e-fddffc2b9d43)
[![Codacy](https://img.shields.io/codacy/grade/2933d00bd11143baaa6538a9c5fbd9a9/master.svg?style=for-the-badge&label=Codacy)](https://www.codacy.com/app/simplepie/simplepie-ng)


### Social

[![Website](https://img.shields.io/website-up-down-green-red/http/simplepie.org.svg?label=simplepie.org&style=for-the-badge)](http://simplepie.org)
[![Author](http://img.shields.io/badge/author-@skyzyx-blue.svg?style=for-the-badge)](https://twitter.com/skyzyx)
[![Follow](https://img.shields.io/twitter/follow/simplepie_ng.svg?style=for-the-badge&label=Follow%20@simplepie_ng)](https://twitter.com/intent/follow?screen_name=simplepie_ng)
[![Stars](https://img.shields.io/github/stars/simplepie/simplepie-ng.svg?style=for-the-badge&label=GitHub%20Stars)](https://github.com/simplepie/simplepie-ng/stargazers)
[![Beerpay](https://img.shields.io/beerpay/simplepie/simplepie-ng.svg?style=for-the-badge&label=sponsored)](https://beerpay.io/simplepie/simplepie-ng)

### Compliance

[![License](https://img.shields.io/github/license/simplepie/simplepie-ng.svg?style=for-the-badge)](https://github.com/simplepie/simplepie-ng/blob/master/LICENSE.md)

[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fsimplepie%2Fsimplepie-ng.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2Fsimplepie%2Fsimplepie-ng?ref=badge_large)

## How can I contribute?

**You can't** — at least, not yet. I'm still in the _big-bang_ (or _iteration-zero_, or _proof-of-concept_) phase, and I'm not _yet_ looking for code contributions. I'm making progress on the ideas that I have, and things are still wildly experimental and unstable.

If, after reading the existing docs, you have questions or ideas, you can file a feature request or start a discussion thread in GitHub Issues. I'm willing to entertain good ideas provided that they are good for professional-grade software engineers. My goal is _mostly_ spec-compliance, but adapted to the realities of real-world feeds.


## What is SimplePie NG?

**SimplePie [OG](http://www.urbandictionary.com/define.php?term=OG)** was created for PHP 4.3 by a person who was brand-new to programming. That was me. About 6 months into the project, I started receiving a _tremendous_ amount of help from a teenage Scot who had nothing better to do — thanks Geoffrey!

Over the years I've tried to start this project by forking SimplePie and stripping out as much legacy code as possible. Those efforts never made it very far. The original codebase grew rapidly and organically over the first 5 years (before I got burned-out and retired from SimplePie development), is extremely complex, not tested or documented nearly well enough, and has been kept alive since 2010 by way of Frankenstein-like patchwork. As PHP and its community have matured over the years, I've started to experiment with new approaches to handling such a complex set of tasks as SimplePie performs.

**SimplePie NG** is a from-scratch rewrite of SimplePie for PHP 7.2. It starts with a completely different kind of thinking, and more than a decade more experience in software engineering and open-source. It is written with a view of PHP from 2017 and beyond, and is being built in such a way that greater community involvement should be far easier from much earlier in the project's life.

See the [wiki](https://github.com/simplepie/simplepie-ng/wiki) for more details.

## Coding Standards

PSR-1/2/12 are a solid foundation, but are not an entire coding style by themselves. By leveraging tools such as [PHP CS Fixer](http://cs.sensiolabs.org) and [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer), we can automate a large part of our style requirements. The things that we cannot yet automate are documented here:

<https://github.com/simplepie/simplepie-ng-coding-standards>

## Please Support or Sponsor Development

[![Beerpay](https://img.shields.io/beerpay/simplepie/simplepie-ng.svg?style=for-the-badge)](https://beerpay.io/simplepie/simplepie-ng)

SimplePie NG is a labor of love. I have been working on it in my free time since June 2017 because it's a project I love, and I believe our community would benefit from this tool.

If you use SimplePie NG — especially to make money — it would be swell if you could kick down a few bucks. As the project grows, and we start leveraging more services and architecture, it would be great if it didn't all need to come out of my pocket.

You can also sponsor the development of a particular feature. If there's a feature that you want to see implemented, and I believe it's the right fit for SimplePie NG, you can sponsor the development of the feature to get it prioritized.

Your contributions are greatly and sincerely appreciated.
