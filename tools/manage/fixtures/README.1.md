Files System Fixtures
========

Fixtures are snapshots of database tables and custom filesystem
content.  They can be saved and loaded for development testing, for backup of
real customer content in production, for migrating customers to different 
infrastructure and for doing demonstrations.


Bundle Format
--------

Each fixture is a tar[ed] and gziped file with the following layout.

| File/Folder   | Required | Desc                                    |
|---------------|----------|-----------------------------------------|
| db/           | [ ]      | Database SQL files (*.sql and/or *.sh)  |
| fs/           | [ ]      | Filesystem content from /home/customer  |
| manifest.json | [*]      | JSON file describing the fixture.       |


Usage
--------

The recommend usage of these fixtures is with the `manage` tool.
Specifically with the `fixture` subcommand a la `tools/bin/manage fixture`.

### Loading a fixture

The load a fixture use the `manage-fixture` `load` subcommand with the name
of the file (sans extension).  For example to load a fixture located in
`fixtures/[person].tgz`...

```sh
tools/bin/manage fixture load person
```

### Saving a fixture (snapshotting)

TBD


Manifest Format
--------

The `manifest.json` file describes the contents of a fixture.  This includes
the name of the credit union, the ID (customer code), schema version for checking
compatiblity and other metadata used to help describe the fixture.

Example manifest:

```javascript
{
    "name": "PERSON",
    "id": "person",
    "schema": 1,
    "version": 1,
    "created": "2016-07-20",
    "modified": "2016-07-25"
}
```

#### Fields

 * **name**: The friendly full name of the customer
 * **id**: The customer id/code used in the ?id=... query argument
 * **schema**: The numeric schema version this bundle is compatible with.
               As the appliction evolves this number may increment to indicate
               breaks in backwards compability.
 * **verison**: The version of the bundle.  Primarily used as an informative
                marker to track changes to a bundle.
