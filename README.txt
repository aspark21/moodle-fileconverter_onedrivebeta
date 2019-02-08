ABOUT
==========
The 'Microsoft OneDrive BETA' document converter was developed by
    Neill Magill and modified to use the beta API by Alistair Spark

It is based on the Google Drive document converter plugin.

This module may be distributed under the terms of the General Public License
(see http://www.gnu.org/licenses/gpl.txt for details)

PURPOSE
==========
The Onedrive BETA API supports some file types which the 1.0 stable API doesn't, this plugin uses the beta API just for those file types which the stable API doesn't currently support.
It should be used in conjunction with the stable onedrive converter plugin https://moodle.org/plugins/fileconverter_onedrive for files which the stable API supports.
Can be helpful if you want to reduce further the reliance on unoconv.
An alternative document conversion plugin that makes use of Microsoft OneDrive.

INSTALLATION
==========
The Microsoft OneDrive document converter follows the standard installation procedure.

1. Create folder <path to your moodle dir>/files/converter/onedrivebeta.
2. Extract files from folder inside archive to created folder.
3. Visit page Site administration ► Notifications to complete installation.

ENABLING THE CONVERTER
==========
Visit Site administration ► Plugins ► Document converters ► Manage document converters to enable the plugin

You will need to ensure that it is:

1. Configured to use a Microsoft OAuth 2 service.
2. Working by using the 'Test this converter is working properly' link on the settings page.
