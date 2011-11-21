#!/bin/sh
cd /var/www/html/www.worldwideinterweb.com/htdocs
git add .
git add -u
committime="auto_commit_"`eval date +%Y-%m-%d.%T`" from admin"
echo "git commit -a -m '$committime'"
git commit -a -m '$committime'
git push origin master
