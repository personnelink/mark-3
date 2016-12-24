"""
Log dump tool for personnelink application and infrastructure.
"""

import shellish
import subprocess


class Logs(shellish.Command):
    """ Dump or follow logs.

    This tool dumps or follows (ie tail -f) logs from Elasticsearch.
    In Personnelink this is application and infrastructure logs. """

    name = 'logs'
    type_filters = {
        'all': None,
        'web': 'image_tag:personnelink_web',
        'db': 'image_tag:personnelink_db',
        'app': '(image_tag:personnelink_db OR image_tag:personnelink_web)',
        'consul': 'image_tag:consul',
        'ecs': 'image_tag:amazon-ecs-agent'
    }
    elktail_cmd = r'docker run personnelink/elktail ' \
                  r'--url http://logs.infra.personnelink.io:80 ' \
                  r'-i logging-* -t timestamp ' \
                  r'-f "%timestamp %level %host_addr %host %image_tag:%image_version ' \
                  r'%message"'

    def setup_args(self, parser):
        self.add_argument('--type', '-t', choices=self.type_filters,
                          default='all', help='Limit log dump to a specific '
                         'type.')
        self.add_argument('--show-healthchecks', action='store_true',
                          help="Don't hide HealthChecker http logs")
        self.add_argument('--stack', '-s', help='Only show logs for a '
                          'particular stack (ie. prod, stage, etc).')
        self.add_argument('-n', default=50, type=int, help='Number of lines '
                          'to fetch initially.')
        self.add_argument('-f', action='store_true', help='Follow logs.')

    def run(self, args):
        params = ['']
        query = []
        if not args.f:
            params.append('--list-only')
        params.append('-n %d' % args.n)
        if args.stack:
            query.append('image_version:%s' % args.stack)
        if not args.show_healthchecks:
            query.append('!message:HealthChecker')
        type_filter = self.type_filters[args.type]
        if type_filter:
            query.append(type_filter)
        params.append('"%s"' % ' AND '.join(query))
        self.elktail(params)

    def elktail(self, params):
        cmd = self.elktail_cmd + ' '.join(map(str, params))
        try:
            subprocess.check_call(cmd, shell=True)
        except subprocess.CalledProcessError:
            raise SystemExit(1)

Logs()()
