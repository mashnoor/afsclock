#!/usr/bin/python3.6
import sys
import logging
logging.basicConfig(stream=sys.stderr)
sys.path.insert(0,"/var/www/attendancekeeper/detector/")

from admin import app as application
application.secret_key = 'afssecret'
