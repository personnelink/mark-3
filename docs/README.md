Personnelink
========
Supplemental documentaion and overview can be found in here.


[Application]
--------
 * The containers used to execute application code (i.e. PHP pages and Perl
   scripts).
 * Build scripts and `Dockerfile` templates used to build containers.
 * The arrangement of containers used to provide all of a services functionality.
   For example, the application layer holds the configuration used to tie a web
   server container to a database container.


[Tools]
--------
The [tools/bin/](../../tools/bin) folder contains a variety of command line
tools for interacting with the application;  Either as utilities to augment a
running stack or by tweaking the local configuration used for a stack.


Infrastructure
--------
The Personnelink infrastructure is a modular design that uses the Linux container
technology [Docker] to isolate dependencies and enforce several [12 factor app]
best practices.  Additional design patterns include but are not limited to the
[immutable server pattern], [blue green deployment] and `deploy from source`
(*ref needed*)

Infrastructure is composed of 4 logical layers that isolate various technical
concerns.  The design attempts to protect each layer from requiring too much
understanding of it's environment to make the entire stack as modular and
portable as possible.  In the future various layers could be implemented in
google cloud compute or in a physical data-center, for example.

#### [Machine Layer]:
 * Provides the service platform for running docker containers.
 * Application agnostic;  Only knows how to run containers and what their run
   state is.

#### [Integrations Layer]:
 * Mapping of SCM services, CI/CD, notifications (i.e. [ChatOps]), monitoring
   and the [machine layer].
 * How services are connected and how pipelines flow.


[12 factor app]: http://12factor.net/
[application]: app/README.md
[blue green deployment]: http://martinfowler.com/bliki/BlueGreenDeployment.html
[chatops]: https://www.pagerduty.com/blog/what-is-chatops/
[docker]: https://www.docker.com/what-docker
[immutable server]: http://martinfowler.com/bliki/ImmutableServer.html
[integrations layer]: infra/integrations.md
[machine layer]: infra/machine.md
[tools]: tools/README.md
