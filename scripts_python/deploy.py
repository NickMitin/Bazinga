# -*- coding: utf-8 -*-
import datetime
from fabric import api as fabric_api
from StringIO import StringIO


fabric_api.env.roledefs = {
    'prod': [
        'xpundel@95.213.138.132',  # master
        'xpundel@95.213.141.149',  # web1
        'xpundel@95.213.188.211'   # web2
    ]
}


def reload_supervisor():
    if fabric_api.env.host == '95.213.138.132':
        fabric_api.sudo('supervisorctl reload')


def reload_nginx():
    fabric_api.sudo('/etc/init.d/nginx restart')


def deploy():
    error = StringIO()
    with fabric_api.cd('/var/www/html/friday'):
        date = datetime.datetime.now()
        fabric_api.run('git status')
        fabric_api.prompt('Press <Enter> to continue or <Ctrl+C> to cancel.')
        fabric_api.run('git checkout -b deploy_%s' % date.strftime('%d_%m_%Y__%H_%M_%S'))
        fabric_api.run('git pull origin master')
        fabric_api.run('git submodule update')

        if fabric_api.env.host == '95.213.138.132':
            fabric_api.run('php console -m migrate', stderr=error)
            reload_supervisor()
