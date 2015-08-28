# -*- coding: utf-8 -*-
from tornado import web, ioloop, websocket
import json
from fabric import api

import tornado.ioloop
import tornado.web

class SyncHandler(tornado.web.RequestHandler):
    def get(self):
	web1_ip = "95.213.141.149"
	web2_ip = "95.213.188.211"
	rsync_command = "rsync -avH --numeric-ids {path} {host}:{path}"
	rsync_command2 = "rsync -avH --numeric-ids {path} {host}:{pathRemote}"
	images_path = "/var/www/html/friday/www/images/content/"
	files_path = "/var/www/html/friday/www/file/content/"
	js_path = "/var/www/html/friday/www/scripts/site/data/"

	self.write(api.local(rsync_command.format(**{'path':images_path, 'host':web1_ip})))
	self.write(api.local(rsync_command.format(**{'path':files_path, 'host':web1_ip})))
	self.write(api.local(rsync_command.format(**{'path':js_path, 'host':web1_ip})))

	self.write(api.local(rsync_command.format(**{'path':images_path, 'host':web2_ip})))
	self.write(api.local(rsync_command.format(**{'path':files_path, 'host':web2_ip})))
	self.write(api.local(rsync_command.format(**{'path':js_path, 'host':web2_ip})))

	self.write(api.local(rsync_command2.format(**{'path':images_path, 'pathRemote':"/var/www/html/landing/www/images/content/", 'host':web1_ip})))
	self.write(api.local(rsync_command2.format(**{'path':files_path, 'pathRemote':"/var/www/html/landing/www/file/content/", 'host':web1_ip})))
	self.write(api.local(rsync_command2.format(**{'path':js_path, 'pathRemote':"/var/www/html/landing/www/scripts/site/data/", 'host':web1_ip})))

	self.write(api.local(rsync_command2.format(**{'path':images_path, 'pathRemote':"/var/www/html/landing/www/images/content/", 'host':web2_ip})))
	self.write(api.local(rsync_command2.format(**{'path':files_path, 'pathRemote':"/var/www/html/landing/www/file/content/", 'host':web2_ip})))
	self.write(api.local(rsync_command2.format(**{'path':js_path, 'pathRemote':"/var/www/html/landing/www/scripts/site/data/", 'host':web2_ip})))

	self.write('\nok');

application = tornado.web.Application([
    (r"/sync/", SyncHandler),
])

if __name__ == "__main__":
    application.listen(8887)
    tornado.ioloop.IOLoop.current().start()
