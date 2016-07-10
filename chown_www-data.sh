#!/bin/bash
cd /var/www
chown www-data:www-data -R .
find . -type d -exec chmod 755 {} +
find . -type f -exec chmod 644 {} +
