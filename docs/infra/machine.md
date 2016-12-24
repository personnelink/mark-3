Parent: [Overview README.md](../README.md)


Machine Layer
========
The *machine layer*, or *hardware layer*, represents the platforms that run
[docker] containers along with any auxiliary components/services used to enable
application runtime.  In a development environment this is accomplished in its
entirety with a single machine running [Docker].  For production and staging this
is a combination of standard AWS services including the
[EC2 container service (ECS)][ecs] run and manage application's [docker]
containers.

![Machine Diagram](machine.png)

### Application Agnosticism
For both dev and prod platforms the machine layer is agnostic about application
requirements and configuration.  The application code is isolated into
containers and the machine layer ensures that they are running/healthy.  All
forms of update, be it distro packages, library updates, and application fixes
are done by versioning containers.  In other words, the machine layer tracks
versions of *containers* and not *code*.  A deploy consists of uploading a new
set of containers with an updated version number and instructing the ECS
service to phase over to the new containers.  This is done incrementally to
allow for health checking the new containers as they are brought online.  If
for some reason the new container version does not pass health checks
performed by an Amazon ELB, ECS will halt the roll-out to prevent an overall
service outage.

### ECS Details
ECS runs containers on a cluster of EC2 instances which are managed by an
[auto scaling group].  The ASG monitors the health and capacity of the cluster
making adjustments to the number of instances in service as needed.  In
addition, the ASG will add new instances and terminate old instances on a
regular basis as a form of large-scale garbage collection.

> **ECS Instance Notes:**
> - Minimum of 3 availability zones are used
> - Minimum of 4 instances are in service
> - A single cluster is capable of running multiple application stacks (prod,
>   stage, etc)
> - Instances run Amazon's [ECS Optimized AMI]
> - Instances are stateless
> - New machines are continually cycled (daily) into the cluster to keep it
    fresh

### Homogeneous Cluster Nodes
The production machine layer employees a homogeneous cluster design where each
EC2 instance in the cluster is identical.  The instances are bootstrapped at
startup with [personnelink/ecsextender][ecsextender] to extend the stock capabilities of
an ECS cluster.

> **Extensions to ECS**
> - [Consul]: Container discovery, extended health checking (unused) and
>   intra-container configuration (unused)
> - [Registrator]: Automatic mapping of containers to consul services
> - Centralized Logging to [ELK]: Application logs and docker statistics are
>   shipped to Amazon's [Elasticsearch] service


[auto scaling group]: https://aws.amazon.com/autoscaling/
[consul]: https://www.consul.io/
[docker]: https://www.docker.com/what-docker
[ecs optimized ami]: https://aws.amazon.com/marketplace/pp/B00U6QTYI2/
[ecs]: https://aws.amazon.com/ecs/
[ecsextender]: https://github.com/person/ecsextender
[elasticsearch]: https://aws.amazon.com/elasticsearch-service/
[elk]: https://www.elastic.co/webinars/introduction-elk-stack
[registrator]: https://github.com/gliderlabs/registrator
