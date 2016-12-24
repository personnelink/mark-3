Parent: [Overview README.md](../README.md)


Integrations Layer
========
Proper execution and upkeep of the Personnelink service depends on a suite of
external and 3rd party services.  The term integration refers to the bindings
from one service to another, such as the notifications sent to a Slack channel
when code is updated, or test fails, etc.  The integrations are typically a
graph of connections between various standalone services which in some cases
form pipelines.

![Integrations Diagram](integrations.png)

### CI/CD
Continuous integration (CI) refers to the process of building and testing a code
base as the changes occur in the SCM (GitHub) system.  Continuous deploy (CD)
is, as the name states, the process for deploying code to a staging or
production stack as soon as the code successfully passes through the
integration pipeline.

### Pipeline
The integration service [CircleCI] monitors changes to the
[Personnelink GitHub repository][personnelink].  Commits are built and tested via
`make test`.  Commits on the [master branch] are deployed to the
[stage stack] in AWS.  Likewise, changes to the [release branch] are
deployed to the [prod stack].  The mapping for this scheme is contained in the
project's [circleci config].  In all cases status notifications are sent to the [Slack Integration Channel].

### Build/Deployment
Build and deployment are [Docker] based.  The build process creates docker
containers as build artifacts.  These containers are used for the testing
process and when validated they are uploaded to Amazon's
[container registry service][ecr].  This ensures the binary being tested uses
the exact bits being used in your final production environment,eliminating
various integrity concerns.


[circleci config]: https://github.com/person/personnelink/blob/master/circle.yml
[circleci]: https://circleci.com
[docker]: https://www.docker.com/what-docker
[ecr]: https://aws.amazon.com/ecr/
[master branch]: https://github.com/person/personnelink/tree/master
[personnelink]: https://github.com/personnelink/mark-3
[prod stack]: https://personnelink.cloud
[release branch]: https://github.com/person/personnelink/tree/release
[slack integration channel]: https://personnelink.slack.com/messages/integration/
[stage stack]: https://stage.personnelink.io
