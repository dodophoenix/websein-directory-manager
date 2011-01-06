<?php
$dir = dirname(__FILE__);


$joomlaDir="/home/benni/workspace/eclipseGalileoPHP/joomla";
$targetDir="/home/benni/workspace/eclipseGalileoPHP/com_wbsdirmgr";

echo $dir;


$localComponentSourceFolder="com";
$localSiteFolder="site";
$localAdminFolder="admin";
$localLangFolder="lang";
$componentName='wbsdirmgr';



$xml = simplexml_load_file($dir.'/'.$localComponentSourceFolder.'/'.$componentName.'.xml');
$siteFiles 	= $xml->xpath('//files[@folder="site"]');
$adminFiles	= $xml->xpath('//files[@folder="admin"]');
$langFiles	= $xml->xpath('//languages');

$componentDir="com_".$componentName;
//echo "\npagefiles\n";
//var_dump($siteFiles);
//echo "\nadminfiles\n";
//var_dump($adminFiles);

/*
 * Output Script file Data
 *
 */


$scriptBegin=<<<TXT
#! /bin/bash
#
# generated from copyBuilder.php
#

joomlaDir="${joomlaDir}";
targetDir="${targetDir}";

# make a filelist here
# files[0] = "" ; 
# toFil[0] = ..


TXT;

ob_start();
echo $scriptBegin;


$cnt=0;
// site files
$siteFiles = $siteFiles[0]->filename;
foreach($siteFiles as $file){
echo 'files['.$cnt.']="/components/'.$componentDir.'/'.$file.'";
toFil['.$cnt.']="/'.$localComponentSourceFolder.'/'.$localSiteFolder.'/'.$file.'";

';
$cnt++;
}
// admin files
$adminFiles = $adminFiles[0]->filename;
foreach($adminFiles as $file){
echo 'files['.$cnt.']="/administrator/components/'.$componentDir.'/'.$file.'";
toFil['.$cnt.']="/'.$localComponentSourceFolder.'/'.$localAdminFolder.'/'.$file.'";

';
$cnt++;
}


// lang files
$langFiles = $langFiles[0]->language;
foreach($langFiles as $file){
	$a=split("\.",$file);
	$langdir  = $a[0];

echo 'files['.$cnt.']="/language/'.$langdir.'/'.$file.'";
toFil['.$cnt.']="/'.$localComponentSourceFolder.'/'.$localLangFolder.'/'.$file.'";

';
$cnt++;
}

// component description xml 
echo 'files['.$cnt.']="/administrator/components/'.$componentDir.'/'.$componentName.'.xml";
toFil['.$cnt.']="/'.$localComponentSourceFolder.'/'.$componentName.'.xml";

';

/*
files[0]="/components/com_generatedcomp/TableMyUserDbo.php";
toFil[0]="/com/site/TableMyUserDbo.php";

files[4]="/administrator/components/com_generatedcomp/controller.php";
toFil[4]="/com/admin/controller.php";
*/

echo '
# do copy
size=${#files[*]}
for ((i=0;i<$size;i++)); do
	echo "cp $joomlaDir${files[${i}]} $targetDir${toFil[${i}]}";  
   	cp "$joomlaDir${files[${i}]}" "$targetDir${toFil[${i}]}";
done
exit;';
$data = ob_get_contents();
echo $data;
$fh = fopen($dir.'/copy.sh','w+');
fputs($fh,$data);
fclose($fh);
