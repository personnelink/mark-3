"""
Personnelink tool for managing Personnelink customers, aka the credit unions.
"""

import argparse
import shellish
import subprocess
import fixture


class CUManage(shellish.Command):
    """ Manage credit union entities. """

    name = 'cumanage'

    def setup_args(self, parser):
        self.add_subcommand(Setup)
        self.add_subcommand(fixture.Fixture)


class Setup(shellish.Command):
    """ Setup credit union db tables and filesystem boilerplate.

    This will setup various DB tables and records along with creating FS
    scaffolding used for serving up custom content. """

    name = 'setup'

    def setup_args(self, parser):
        self.add_argument('args', nargs=argparse.REMAINDER)

    def run(self, args):
        cmd('perl /tool/setup.pl %s' % args.args)


def cmd(*args):
    cmd = ' '.join(map(str, args))
    try:
        subprocess.check_call(cmd, shell=True)
    except subprocess.CalledProcessError:
        raise SystemExit(1)

CUManage()()
