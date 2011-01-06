#! /bin/bash
#
# generated from copyBuilder.php
#
echo "begin copy";
joomlaDir="/home/benni/workspace/eclipseGalileoPHP/joomla";
targetDir="/home/benni/workspace/eclipseGalileoPHP/com_wbsdirmgr";

# make a filelist here
# files[0] = "" ; 
# toFil[0] = ..

files[0]="/components/com_wbsdirmgr/index.html";
toFil[0]="/com/site/index.html";

files[1]="/components/com_wbsdirmgr/wbsdirmgr.php";
toFil[1]="/com/site/wbsdirmgr.php";

files[2]="/components/com_wbsdirmgr/lib/wbsdirmgrlib.php";
toFil[2]="/com/site/lib/wbsdirmgrlib.php";

files[3]="/components/com_wbsdirmgr/views/index.html";
toFil[3]="/com/site/views/index.html";

files[4]="/components/com_wbsdirmgr/views/wbsdirmgr/index.html";
toFil[4]="/com/site/views/wbsdirmgr/index.html";

files[5]="/components/com_wbsdirmgr/views/wbsdirmgr/tmpl/default_toolbar.php";
toFil[5]="/com/site/views/wbsdirmgr/tmpl/default_toolbar.php";

files[6]="/components/com_wbsdirmgr/views/wbsdirmgr/tmpl/default.xml";
toFil[6]="/com/site/views/wbsdirmgr/tmpl/default.xml";

files[7]="/components/com_wbsdirmgr/views/wbsdirmgr/tmpl/default_file.php";
toFil[7]="/com/site/views/wbsdirmgr/tmpl/default_file.php";

files[8]="/components/com_wbsdirmgr/views/wbsdirmgr/tmpl/index.html";
toFil[8]="/com/site/views/wbsdirmgr/tmpl/index.html";

files[9]="/components/com_wbsdirmgr/views/wbsdirmgr/tmpl/default_folder.php";
toFil[9]="/com/site/views/wbsdirmgr/tmpl/default_folder.php";

files[10]="/components/com_wbsdirmgr/views/wbsdirmgr/tmpl/default.php";
toFil[10]="/com/site/views/wbsdirmgr/tmpl/default.php";

files[11]="/components/com_wbsdirmgr/views/wbsdirmgr/tmpl/default_upload.php";
toFil[11]="/com/site/views/wbsdirmgr/tmpl/default_upload.php";

files[12]="/components/com_wbsdirmgr/views/wbsdirmgr/view.html.php";
toFil[12]="/com/site/views/wbsdirmgr/view.html.php";

files[13]="/components/com_wbsdirmgr/views/wbsdirout/index.html";
toFil[13]="/com/site/views/wbsdirout/index.html";

files[14]="/components/com_wbsdirmgr/views/wbsdirout/tmpl/default.xml";
toFil[14]="/com/site/views/wbsdirout/tmpl/default.xml";

files[15]="/components/com_wbsdirmgr/views/wbsdirout/tmpl/index.html";
toFil[15]="/com/site/views/wbsdirout/tmpl/index.html";

files[16]="/components/com_wbsdirmgr/views/wbsdirout/tmpl/default.php";
toFil[16]="/com/site/views/wbsdirout/tmpl/default.php";

files[17]="/components/com_wbsdirmgr/views/wbsdirout/tmpl/default_jsout.php";
toFil[17]="/com/site/views/wbsdirout/tmpl/default_jsout.php";

files[18]="/components/com_wbsdirmgr/views/wbsdirout/view.html.php";
toFil[18]="/com/site/views/wbsdirout/view.html.php";

files[20]="/components/com_wbsdirmgr/js/wbsdirmgr.js";
toFil[20]="/com/site/js/wbsdirmgr.js";

files[21]="/components/com_wbsdirmgr/js/jquery-1.4.2.min.js";
toFil[21]="/com/site/js/jquery-1.4.2.min.js";

files[22]="/components/com_wbsdirmgr/css/index.html";
toFil[22]="/com/site/css/index.html";

files[23]="/components/com_wbsdirmgr/css/wbsdirmgr.css";
toFil[23]="/com/site/css/wbsdirmgr.css";

files[24]="/components/com_wbsdirmgr/controller.php";
toFil[24]="/com/site/controller.php";

files[25]="/components/com_wbsdirmgr/img/icons/btn_donateCC_LG.gif";
toFil[25]="/com/site/img/icons/btn_donateCC_LG.gif";

files[26]="/components/com_wbsdirmgr/img/icons/null.gif";
toFil[26]="/com/site/img/icons/null.gif";

files[27]="/components/com_wbsdirmgr/img/icons/folder.png";
toFil[27]="/com/site/img/icons/folder.png";

files[28]="/components/com_wbsdirmgr/img/icons/htm.png";
toFil[28]="/com/site/img/icons/htm.png";

files[29]="/components/com_wbsdirmgr/img/icons/mpeg.png";
toFil[29]="/com/site/img/icons/mpeg.png";

files[30]="/components/com_wbsdirmgr/img/icons/mpg.png";
toFil[30]="/com/site/img/icons/mpg.png";

files[31]="/components/com_wbsdirmgr/img/icons/upperdir.png";
toFil[31]="/com/site/img/icons/upperdir.png";

files[32]="/components/com_wbsdirmgr/img/icons/exe.png";
toFil[32]="/com/site/img/icons/exe.png";

files[33]="/components/com_wbsdirmgr/img/icons/txt.png";
toFil[33]="/com/site/img/icons/txt.png";

files[34]="/components/com_wbsdirmgr/img/icons/index.html";
toFil[34]="/com/site/img/icons/index.html";

files[35]="/components/com_wbsdirmgr/img/icons/log_not_found.png";
toFil[35]="/com/site/img/icons/log_not_found.png";

files[36]="/components/com_wbsdirmgr/img/icons/tar.png";
toFil[36]="/com/site/img/icons/tar.png";

files[37]="/components/com_wbsdirmgr/img/icons/mp3.png";
toFil[37]="/com/site/img/icons/mp3.png";

files[38]="/components/com_wbsdirmgr/img/icons/js.png";
toFil[38]="/com/site/img/icons/js.png";

files[39]="/components/com_wbsdirmgr/img/icons/png.png";
toFil[39]="/com/site/img/icons/png.png";

files[40]="/components/com_wbsdirmgr/img/icons/arrow_up.png";
toFil[40]="/com/site/img/icons/arrow_up.png";

files[41]="/components/com_wbsdirmgr/img/icons/xml.png";
toFil[41]="/com/site/img/icons/xml.png";

files[42]="/components/com_wbsdirmgr/img/icons/sql.png";
toFil[42]="/com/site/img/icons/sql.png";

files[43]="/components/com_wbsdirmgr/img/icons/skp.png";
toFil[43]="/com/site/img/icons/skp.png";

files[44]="/components/com_wbsdirmgr/img/icons/pdf.png";
toFil[44]="/com/site/img/icons/pdf.png";

files[45]="/components/com_wbsdirmgr/img/icons/addfolder.png";
toFil[45]="/com/site/img/icons/addfolder.png";

files[46]="/components/com_wbsdirmgr/img/icons/dxf.png";
toFil[46]="/com/site/img/icons/dxf.png";

files[47]="/components/com_wbsdirmgr/img/icons/log_disabled.png";
toFil[47]="/com/site/img/icons/log_disabled.png";

files[48]="/components/com_wbsdirmgr/img/icons/info.png";
toFil[48]="/com/site/img/icons/info.png";

files[49]="/components/com_wbsdirmgr/img/icons/css.png";
toFil[49]="/com/site/img/icons/css.png";

files[50]="/components/com_wbsdirmgr/img/icons/tick.png";
toFil[50]="/com/site/img/icons/tick.png";

files[51]="/components/com_wbsdirmgr/img/icons/unknown.png";
toFil[51]="/com/site/img/icons/unknown.png";

files[52]="/components/com_wbsdirmgr/img/icons/gif.png";
toFil[52]="/com/site/img/icons/gif.png";

files[53]="/components/com_wbsdirmgr/img/icons/jsmallsoftware.png";
toFil[53]="/com/site/img/icons/jsmallsoftware.png";

files[54]="/components/com_wbsdirmgr/img/icons/psd.png";
toFil[54]="/com/site/img/icons/psd.png";

files[55]="/components/com_wbsdirmgr/img/icons/doc.png";
toFil[55]="/com/site/img/icons/doc.png";

files[56]="/components/com_wbsdirmgr/img/icons/dwg.png";
toFil[56]="/com/site/img/icons/dwg.png";

files[57]="/components/com_wbsdirmgr/img/icons/ppt.png";
toFil[57]="/com/site/img/icons/ppt.png";

files[58]="/components/com_wbsdirmgr/img/icons/html.png";
toFil[58]="/com/site/img/icons/html.png";

files[59]="/components/com_wbsdirmgr/img/icons/jpeg.png";
toFil[59]="/com/site/img/icons/jpeg.png";

files[60]="/components/com_wbsdirmgr/img/icons/tif.png";
toFil[60]="/com/site/img/icons/tif.png";

files[61]="/components/com_wbsdirmgr/img/icons/zip.png";
toFil[61]="/com/site/img/icons/zip.png";

files[62]="/components/com_wbsdirmgr/img/icons/rename.png";
toFil[62]="/com/site/img/icons/rename.png";

files[63]="/components/com_wbsdirmgr/img/icons/addfile.png";
toFil[63]="/com/site/img/icons/addfile.png";

files[64]="/components/com_wbsdirmgr/img/icons/log_found.png";
toFil[64]="/com/site/img/icons/log_found.png";

files[65]="/components/com_wbsdirmgr/img/icons/xls.png";
toFil[65]="/com/site/img/icons/xls.png";

files[66]="/components/com_wbsdirmgr/img/icons/arrow_down.png";
toFil[66]="/com/site/img/icons/arrow_down.png";

files[67]="/components/com_wbsdirmgr/img/icons/delete.png";
toFil[67]="/com/site/img/icons/delete.png";

files[68]="/components/com_wbsdirmgr/img/icons/jpg.png";
toFil[68]="/com/site/img/icons/jpg.png";

files[69]="/components/com_wbsdirmgr/img/icons/gz.png";
toFil[69]="/com/site/img/icons/gz.png";

files[70]="/components/com_wbsdirmgr/img/icons/bmp.png";
toFil[70]="/com/site/img/icons/bmp.png";

files[71]="/components/com_wbsdirmgr/img/icons/warning.png";
toFil[71]="/com/site/img/icons/warning.png";

files[72]="/components/com_wbsdirmgr/img/icons/arrow_right.png";
toFil[72]="/com/site/img/icons/arrow_right.png";

files[73]="/components/com_wbsdirmgr/img/icons/xsl.png";
toFil[73]="/com/site/img/icons/xsl.png";

files[74]="/components/com_wbsdirmgr/models/index.html";
toFil[74]="/com/site/models/index.html";

files[75]="/components/com_wbsdirmgr/models/wbsdirmgr.php";
toFil[75]="/com/site/models/wbsdirmgr.php";

files[76]="/components/com_wbsdirmgr/models/access.php";
toFil[76]="/com/site/models/access.php";

files[77]="/administrator/components/com_wbsdirmgr/index.html";
toFil[77]="/com/admin/index.html";

files[78]="/administrator/components/com_wbsdirmgr/wbsdirmgr.php";
toFil[78]="/com/admin/wbsdirmgr.php";

files[79]="/administrator/components/com_wbsdirmgr/config.xml";
toFil[79]="/com/admin/config.xml";

files[80]="/administrator/components/com_wbsdirmgr/views/index.html";
toFil[80]="/com/admin/views/index.html";

files[81]="/administrator/components/com_wbsdirmgr/views/wbsdirmgr/index.html";
toFil[81]="/com/admin/views/wbsdirmgr/index.html";

files[82]="/administrator/components/com_wbsdirmgr/views/wbsdirmgr/tmpl/default.xml";
toFil[82]="/com/admin/views/wbsdirmgr/tmpl/default.xml";

files[83]="/administrator/components/com_wbsdirmgr/views/wbsdirmgr/tmpl/index.html";
toFil[83]="/com/admin/views/wbsdirmgr/tmpl/index.html";

files[84]="/administrator/components/com_wbsdirmgr/views/wbsdirmgr/tmpl/default.php";
toFil[84]="/com/admin/views/wbsdirmgr/tmpl/default.php";

files[85]="/administrator/components/com_wbsdirmgr/views/wbsdirmgr/view.html.php";
toFil[85]="/com/admin/views/wbsdirmgr/view.html.php";

files[86]="/administrator/components/com_wbsdirmgr/controller.php";
toFil[86]="/com/admin/controller.php";

files[88]="/administrator/components/com_wbsdirmgr/wbsdirmgr.xml";
toFil[88]="/com/wbsdirmgr.xml";

files[87]="/language/de-DE/de-DE.com_wbsdirmgr.ini";
toFil[87]="/com/site/lang/de-DE.com_wbsdirmgr.ini";

files[19]="/administrator/language/de-DE/de-DE.com_wbsdirmgr.ini";
toFil[19]="/com/admin/lang/de-DE.com_wbsdirmgr.ini";

files[88]="/language/en-GB/en-GB.com_wbsdirmgr.ini";
toFil[88]="/com/site/lang/en-GB.com_wbsdirmgr.ini";

files[89]="/administrator/language/en-GB/en-GB.com_wbsdirmgr.ini";
toFil[89]="/com/admin/lang/en-GB.com_wbsdirmgr.ini";

# do copy
size=${#files[*]}
for ((i=0;i<$size;i++)); do
	echo "cp $joomlaDir${files[${i}]} $targetDir${toFil[${i}]}";  
   	cp "$joomlaDir${files[${i}]}" "$targetDir${toFil[${i}]}";
done
exit;