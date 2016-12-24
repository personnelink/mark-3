Build System
========
The Personnelink build system involves gathering dependencies and creating runtime
environments for testing and development.  It also will have eventualities
for deployment artifacts, perhaps docker images.


### Global environment variables
The build system behavior can be altered with environment variables passed
to the `make` command.  These variables can either be set via 
`export VAR="value"` prior to running `make` or by prefixing the build with
a list of key/value pairs like so, `VAR1="value" VAR2="another value" make`.

**Supported global environment variables:**
 * VERBOSE: When `1` standard output will not be hidden and you can see
   all the details of the build process.


Targets
--------

### `make help`
Show this BUILD.md file.


### `make test`
Perform code verification for this project.  If this target returns non-zero
the code base is considered broken and should not be deployed.  This target
will perform the following steps:

 * Build a docker image for test execution.
 * Mock DB and other external APIs.
 * Run unit tests from  `<project>/app/test/<subsystem>`.


### `make all`
Build a full application stack in the current context.  This is a web server
with bare bones DB backing.  Many aspects may be different from a production
environment depending on the subsystem being verified.  This target will
perform the following steps:

 * Build a docker image for Apache and PHP.
 * Install current `app/web` code to the docker image.
 * Build a PostgreSQL server docker image with minimal development fixture.


### `make run` or `make stack-up`
This target will run the stack on your default docker host.  A stack consists
of a web server and a DB server.  Several options can be used to tweak the
behavior of this prototyping runtime..

**Environment variables:**

 * **DEVMODE**: When set to `1` this option instructs the build system to
   add a host volume mount inside the web container for direct development of
   web content (the app/web folder).  DEVMODE=1 also adds a host volume
   mount at `/project` in both the web and db containers which maps to your
   projects root folder.  This can be used for accessing scratch files with
   interactive tools such as `dbprompt` or `webshell`, etc.

   **NOTE** There is a Makefile shortcut for `DEVMODE=1` via `make dev-run`

 * **AUTOOPEN**: When `1` calls `open $URL` for any stack related command.
   This asks your development machine to open a web browser to the service.


### `make clean`
Clear out all docker images and containers that may have been running or
cached.


### `make home-clean`
Remove credit union directories from <ROOT>/home/...


### `make db-admin`
Run a PHP PG Admin service along side a running stack for DB administration.


### `make db-prompt`
Run a Postgres prompt connected to the active DB container.


### `make db-shell`
Run a bash shell inside the container of a _running_ DB container.
*NOTE:* This only works when a stack is running in your env via `make stack`
or other compatible operations.


### `make web-shell`
Run a bash shell inside the container of a _running_ web container.
*NOTE:* This only works when a stack is running in your env via `make stack`
or other compatible operations.
