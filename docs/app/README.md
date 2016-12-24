Parent: [Overview README.md](../README.md)


Application Layer
========
The personnelink application layer has a multifaceted web container, a database
container (for dev mode only) and a variety of tools used to introspect and
debug the aforementioned containers.


Web Container
--------
The web container runs **Employee**, **Customer** and **Monitor** subsystems.  It
uses PHP 7 and the Apache web server.  There is also some Perl code to
communicate with the core appliance.

#### Construction
Web code and assets are stored in the [app/web](../../app/web) folder of the
project.  The `Dockerfile` templates used for constructing the web container
are two-fold;  The [base Dockerfile] describes the components used by both the
[web dockerfile] and the [test dockerfile].  When a component is intended for
use by unittests and the web runtime it should be located in the
[base Dockerfile] otherwise the addendums should be placed in the more specific
`Dockerfile`.


#### Configuration
Apache and PHP configuration are kept in the [app/conf](../../app/conf) folder.
They use a similar inheritance scheme as the base/web/test dockerfiles.

It follows that Apache and/or PHP configuration that applies to both web and
test is located in [app/conf/base/apache2](../../app/conf/base/apache2) and 
[app/conf/base/php](../../app/conf/base/php) respectively.  Likewise there is a
web specific conf folder at [app/conf/web](../../app/conf/web) and test
specific folder at [app/conf/test](../../app/conf/test).


DB Container
--------
**NOTE:** _The DB container is only used for developement environments. [RDS]
is used for production and staging._

The database container is based on the offical [MySql docker container].  

#### Initialization
Core schema and initial values for the DB are kept in alpha-numerically sorted
files in the [db/initdb.d](../../db/initdb.d) folder.  When a **fresh** DB
container is started it will load any `.sql` files placed in this folder to
bootstrap the database.


[base dockerfile]: ../../app/Dockerfile.base
[postgresql docker container]: https://hub.docker.com/_/postgres/
[rds]: https://aws.amazon.com/rds/
[test dockerfile]: ../../app/Dockerfile.test
[web dockerfile]: ../../app/Dockerfile.web
