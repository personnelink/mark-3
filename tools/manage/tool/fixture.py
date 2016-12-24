"""
DB and filesystem fixtures for credit unions.
"""

import json
import os
import shellish
import shutil
import subprocess
import tarfile
import tempfile

SCHEMA_VERSION = 1
FIXTURE_PATH = '/fixtures'


class Fixture(shellish.Command):
    """ Load and save database fixtures. """

    name = 'fixture'

    def setup_args(self, parser):
        self.add_subcommand(Load)
        self.add_subcommand(Save)


class Load(shellish.Command):
    """ Load a DB and/or FS fixture.

    Typically fixtures are tar/gz bundles located in your project's
    `/fixtures` directory.  The exact layout of these bundles is documented in
    the README.md of this same directory.  In short, each fixture bundle
    represents data associated with a single customer (credit union).  In the
    Personnelink system credit union data consists of custom DB tables prefixed
    with the credit union code, (cruisecu for example) and of customizable
    filesystem assets which are typically located in /home/<customer_code>/.
    Initially the filesystem content is just boilerplate, but can be
    customized for each customer. """

    name = 'load'
    # owner/group id for destination container (php:apache)
    file_owner = 33
    file_group = 33

    def setup_args(self, parser):
        self.add_argument('name', help='Name of fixture file (sans file '
                          'extension)')

    def run(self, args):
        filename = '%s/%s.tgz' % (FIXTURE_PATH, args.name)
        shellish.vtmlprint('<b>Extracting: <blue>%s</blue></b>' % filename)
        with tarfile.open(filename) as bundle:
            with tempfile.TemporaryDirectory() as tmpdir:
                bundle.extractall(tmpdir)
                os.chdir(tmpdir)
                self._run(tmpdir)

    def _run(self, tmpdir):
        with open('./manifest.json') as f:
            manifest = json.load(f)
        shellish.vtmlprint("Name: %s" % manifest['name'])
        shellish.vtmlprint("ID: %s" % manifest['id'])
        shellish.vtmlprint("Bundle Version: %s" % manifest['version'])
        shellish.vtmlprint("Schema Version: %s" % manifest['schema'])
        if manifest['schema'] < SCHEMA_VERSION:
            raise ValueError("Fixture schema is incompatible with this stack "
                             "(%s < %s)" % (manifest['schema'],
                             SCHEMA_VERSION))
        try:
            sqlfiles = sorted(x for x in os.listdir('db')
                              if not x.startswith('.'))
        except FileNotFoundError:
            sqlfiles = []
        if not sqlfiles:
            shellish.vtmlprint('<yellow>No database content found</yellow>')
        else:
            for x in sqlfiles:
                if x.endswith('.sql'):
                    shellish.vtmlprint("Loading DB file: <cyan>%s</cyan>" % x)
                    subprocess.check_call('psql < db/%s' % x, shell=True)
                elif x.endswith('.sh'):
                    subprocess.check_call('db/%s' % x, shell=True)
                else:
                    raise TypeError("Invalid DB file detected: %s" % x)
        self.install_files('fs', '/home/%s' % manifest['id'])
        shellish.vtmlprint("\n<b>Fixture README:</b>\n")
        with open('README.md') as f:
            print(f.read())

    def install_files(self, src, dst):
        """ Copy the file tree into the destination location and correct
        ownership as needed. """
        shutil.copytree(src, dst, symlinks=True)
        shutil.chown(dst, self.file_owner, self.file_group)
        for root, dirs, files in os.walk(dst):
            for x in files + dirs:
                shutil.chown(os.path.join(root, x), self.file_owner,
                             self.file_group)


class Save(shellish.Command):
    """ Create a fixture bundle.

    TBD """

    name = 'save'

    def run(self, args):
        raise NotImplementedError('implement me')
