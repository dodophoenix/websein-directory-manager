#!/bin/bash

# build xml file list
# find tinymce/ -type f 'examples' |egrep -v .svn |awk '{printf "<file>%s</file>\n",$0}'>fileList.txt
#
# this script builds a file list for joomla intaller
# call it by bash 
# ./devel.sh absPathSiteDir absPathAdminDir componentName
# the .svn ,exmaple dirs and componentname.xml file are excluded 
#
# adjust vars below for your project
# 
#########################################################################
########################################################################
# 
# Dirname of the component 
#
baseDir="com"

#
# path to site 
#
siteDir="site"
#
# path to admin dir 
#
adminDir="admin"

#
# component name without com_ it will lead to excluded componentname.xml
#
componentName=mediaManagerExtended


########################################################################
####################### Begin Script ###################################

# Function that checks if param is available and exits if not if available it returns the params value
function checkParam {
	
	if [ -z "$2" ] ; then
		# only one param submitted 
		echo $1;
		exit;
	fi
	
	if [ -z "$1" ] ; then 
		# both submitted but first is empty
		echo "$2";
		exit;	
	fi
}

siteDir=$1;
adminDir=$2;
componentName=$3;

checkParam $1 "missing Param 1: absolute path to site directory"
checkParam $2 "missing Param 2: absolute path to admin directory"
checkParam $3 "missing Param 4: missing component name"

echo "Generating file List"
echo " site: $siteDir";
echo " admin:$adminDir";
echo " name: $componentName";

# Get The absolute path of script
current=`echo $(dirname $(readlink -f $0))`;
echo " --------------------------------"
echo "Building list based on: $current"

cd $siteDir
echo " from: `pwd`";
echo " to:   $current/fileListSite.txt"
find -type f |egrep -v .svn |egrep -v $componentName.xml|awk '{printf "<filename>%s</filename>\n",$0}'| sed 's/.\///'>$current/fileListSite.txt
echo ""

cd $adminDir
echo " from: `pwd`";
echo " to:   $current/fileListAdmin.txt"
find -type f |egrep -v .svn |egrep -v $componentName.xml|awk '{printf "<filename>%s</filename>\n",$0}'| sed 's/.\///'>$current/fileListAdmin.txt
echo "done"
