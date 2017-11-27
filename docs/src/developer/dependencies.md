# Development Dependencies

Virtually every engineer I've ever met has had a different preferred way of developing software -- from operating systems, to build tools, to virtualization methods.

Before you can worry about your application dependencies, you need to understand your _runtime dependencies_. The operating system you use for runtime/development makes a difference.

## Core Tools

At a high level, these are the tools you need to run -- or develop for -- SimplePie NG.

* PHP 7.2+ (curl, intl, opcache, mbstring, xml, zip)
* Composer
* Docker (for development)

The better you understand this toolset, the smaller the learning curve you'll experience. The less you understand this toolset, the larger the learning curve you'll experience.

### Docker and Docker Compose

There are a few development-oriented tasks which can be relatively complex, so I have wrapped those tasks up into [Docker] containers so that they always run consistently by avoiding environmental differences.

Docker leverages a feature called "containers" that is built into the Linux kernel. Docker runs natively on modern Linux kernels and operating systems. For macOS and Windows, Docker (the company) provides [toolkits][docker-toolkits] which -- in essence -- boot up miniature Linux VMs to run Docker (the software) through.

There are different pros and cons to leveraging Docker depending on how your environment is configured.

## Optional Tools

There are some optional tools that you can use which take a little more effort to get up and going, but (I would argue) provide an overall better development experience. Some people will completely disagree with me for their own reasons, and that's OK.

### Vagrant

[Vagrant] is a great tool for orchestrating local development _virtual machines_. It is well-supported, and has a large audience of developers.

> “But why would I use Vagrant when I can just run Docker on my Mac?”

This is a great question (and feel free to replace _Mac_ with _Windows machine_ or whatever). As a matter of fact, I've had this exact same discussion with many developers I've worked with.

If you are running Linux (e.g., Ubuntu, CentOS) natively — you wouldn't. Leveraging Vagrant here generally wouldn't make sense. However, if you are a developer with a macOS or Windows machine, there are some things to consider about this approach.

1. **Both leverage virtual machines.** For both [Docker for Mac] and [Docker for Windows], a miniature virtual machine is started first, then the Docker daemon is started inside of that. This is more-or-less exactly the same pattern that Docker-inside-Vagrant uses.

1. **Reduce the complexity.** It can be difficult managing Windows and macOS developers on the same team. One has [Homebrew] while the other does not. [Both](https://github.com/skyzyx/bash-mac) support [Bash](https://msdn.microsoft.com/en-us/commandline/wsl/about), but only if all Windows machines are on Windows 10. Instead of wasting the intermediate VM, why not _leverage_ it? If you can get everyone using the same Vagrant VM, then support scripts, shell commands, and various development/deployment processes become unified. Without Vagrant, it falls entirely on you -- the developer -- to correctly install and configure all of the necessary software.

1. **Disk I/O is faster.** Generally speaking, leveraging NFS or Samba inside a Vagrant VM provides faster disk I/O than file system sharing from VMware or VirtualBox, and is overwhelmingly faster than _Docker for Mac’s_ [osxfs].

> **NOTE:** When I used to work at [WePay](https://wepay.com), we had a well-supported, well-documented Vagrant environment. New engineers were able to ramp up and get their development environment up and running on the first day.

Yes, there's more initial setup. No, the fastest configuration isn't free. But I am a firm believer that running Docker via a Vagrant development environment ultimately provides the best developer experience.

.. reviewer-meta::
   :written-on: 2017-06-25
   :proofread-on: 2017-11-26

  [Docker]: https://www.docker.com
  [Docker for Mac]: https://store.docker.com/editions/community/docker-ce-desktop-mac
  [Docker for Windows]: https://store.docker.com/editions/community/docker-ce-desktop-windows
  [docker-toolkits]: https://www.docker.com/community-edition
  [Homebrew]: https://brew.sh
  [osxfs]: https://docs.docker.com/docker-for-mac/osxfs/
  [Vagrant]: https://www.vagrantup.com
