#!/bin/sh
# This script will set correct permissions for your Joomla 1.5 site under linux.
# you need to run this script as root.
# Made by: Fableman

A=`whoami`
B=`1`
C=`root`

if [ $A = "root" ]
then
echo
else
echo "ERROR: This script need root permissions (type:  su  to become root)"
exit -1
fi

if [ $B = 1 ]
then
echo "Setting Permissions.. for user: $C and user:apache"

chown $C:apache administrator/components
chown $C:apache administrator/language
chown $C:apache administrator/language/en-GB
chown $C:apache administrator/language/overrides
chown $C:apache administrator/manifests/files
chown $C:apache administrator/manifests/libraries
chown $C:apache administrator/manifests/packages
chown $C:apache administrator/modules
chown $C:apache administrator/templates
chown $C:apache components
chown $C:apache images
chown $C:apache images/banners
chown $C:apache images/sampledata
chown $C:apache language
chown $C:apache language/en-GB
chown $C:apache language/overrides
chown $C:apache libraries
chown $C:apache media
chown $C:apache modules
chown $C:apache plugins
chown $C:apache plugins/authentication
chown $C:apache plugins/content
chown $C:apache plugins/editors
chown $C:apache plugins/editors-xtd
chown $C:apache plugins/extension
chown $C:apache plugins/search
chown $C:apache plugins/system
chown $C:apache plugins/user
chown $C:apache tmp
chown $C:apache log
chown $C:apache templates
chown $C:apache cache
chown $C:apache administrator/cache

chmod 770 administrator/components
chmod 770 administrator/language
chmod 770 administrator/language/en-GB
chmod 770 administrator/language/overrides
chmod 770 administrator/manifests/files
chmod 770 administrator/manifests/libraries
chmod 770 administrator/manifests/packages
chmod 770 administrator/modules
chmod 770 administrator/templates
chmod 770 components
chmod 770 images
chmod 770 images/banners
chmod 770 images/sampledata
chmod 770 language
chmod 770 language/en-GB
chmod 770 language/overrides
chmod 770 libraries
chmod 770 media
chmod 770 modules
chmod 770 plugins
chmod 770 plugins/authentication
chmod 770 plugins/content
chmod 770 plugins/editors
chmod 770 plugins/editors-xtd
chmod 770 plugins/extension
chmod 770 plugins/search
chmod 770 plugins/system
chmod 770 plugins/user
chmod 770 tmp
chmod 770 log
chmod 770 templates
chmod 770 cache
chmod 770 administrator/cache
echo "DONE! Now login into your Joomla site as admin and goto Help / System Info  and check that all permission are Writable."
echo "(type: exit    to go back to normal user)"

else
  echo "ERROR: THIS SCRIPT MUST BE RUN AT THE INSTALLATION OF JOOMLA 1.5 "
  echo "ERROR: Your not at the start possition (root) of joompla installation. (ex. cd /home/yourusername/public_html)"
fi
