# 01 - Core concepts

## the Core (pun intended)
at the heart of every use of webmaster, is an instance of of `Webmaster\Core`, or a class extending it.

the core handles creation of the container, registering your service definitions, and the site configuration.

## Entrypoints
a single webmaster project might be used in different ways, a public facing website, a background job processor, a command line tool, etc.

for each of these use cases, you can utilise a different "entrypoint", which is a stragegy for bootstrapping the core for that use case.

Webmaster comes with 2 entrypoints out of the box:
- `Webmaster\Entrypoint\Web` - for public facing websites, with routing, request and response handling, etc.
- `Webmaster\Entrypoint\Console` - for command line tools, with input and output handling.

you can also create your own entrypoints by extending `Webmaster\Entrypoint\AbstractEntrypoint`.

Entrypoints use the core to configure themselves and their dependencies.
