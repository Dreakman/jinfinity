<?php
/**
 * @version     $Id: imagefolderlist.php 101 2013-08-05 15:42:00Z Anton Wintergerst $
 * @package     Jinfinity Image Folder List Type for Joomla! 1.5+
 * @copyright   Copyright (C) 2013 Jinfinity. All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website     www.jinfinity.com
 * @email       support@jinfinity.com
 */
defined('_JEXEC') or die;

if(version_compare(JVERSION, '1.6.0', 'ge')) {
    jimport('joomla.html.html');
    jimport('joomla.filesystem.folder');
    jimport('joomla.form.formfield');
    jimport('joomla.form.helper');

    JFormHelper::loadFieldClass('list');

    /**
     * Supports an HTML select list of folder
     *
     * @package     Joomla.Platform
     * @subpackage  Form
     * @since       11.1
     */
    class JFormFieldImageFolderList extends JFormFieldList
    {
        /**
         * The form field type.
         *
         * @var    string
         * @since  11.1
         */
        public $type = 'ImageFolderList';

        /**
         * Method to get the field options.
         *
         * @return array The field option objects.
         * @since   11.1
         */
        protected function getOptions()
        {
            // Initialize variables.
            $options = array();

            // Initialize some field attributes.
            $filter			= (string) $this->element['filter'];
            $exclude		= (string) $this->element['exclude'];
            $hideNone		= (string) $this->element['hide_none'];
            $hideDefault	= (string) $this->element['hide_default'];

            // Get the path in which to search for file options.
            $path = (string) $this->element['directory'];

            // Make sure we have absolute filepath
            if (!substr_count($path, JPATH_ROOT))
            {
                $path = JPATH_ROOT.'/'.$path;
            }

            // Prepend some default options based on field attributes.
            if (!$hideNone) {
                $options[] = JHtml::_('select.option', '-1', JText::alt('JOPTION_DO_NOT_USE', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
            }
            if (!$hideDefault) {
                $options[] = JHtml::_('select.option', '', JText::alt('JOPTION_USE_DEFAULT', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
            }

            // Get a list of folders in the search path with the given filter.
            $folders = JFolder::folders($path, $filter, true, true);

                $path = str_replace('\\', '/', $path);
                //find start of local url
                $pos = strpos($path, '/images');

            // Build the options list from the list of folders.
            if (is_array($folders)) {
                foreach ($folders as $folder) {

                    // Check to see if the file is in the exclude mask.
                    if ($exclude) {
                        if (preg_match(chr(1).$exclude.chr(1), $folder)) {
                            continue;
                        }
                    }
                    $folder = substr_replace($folder, '', 0, $pos);
                    $folder = str_replace('\\', '/', $folder);

                    $options[] = JHtml::_('select.option', $folder, $folder);
                }
            }

            // Merge any additional options in the XML definition.
            $options = array_merge(parent::getOptions(), $options);

            return $options;
        }
    }
} else {
    jimport('joomla.html.parameter.element');
    jimport('joomla.filesystem.folder');

    class JElementImageFolderList extends JElement {
        var $_name = 'imagefolderlist';

        function fetchElement($name, $value, &$node, $control_name)
        {
            jimport( 'joomla.filesystem.folder' );

            // Initialize variables.
            $options = array();

            // Initialize some field attributes.
            $filter			= (string) $this->element['filter'];
            $exclude		= (string) $this->element['exclude'];
            $hideNone		= (string) $this->element['hide_none'];
            $hideDefault	= (string) $this->element['hide_default'];

            // Get the path in which to search for file options.
            $path = (string) $this->element['directory'];

            // Make sure we have absolute filepath
            if (!substr_count($path, JPATH_ROOT))
            {
                $path = JPATH_ROOT.'/'.$path;
            }

            // Prepend some default options based on field attributes.
            if (!$hideNone) {
                $options[] = JHtml::_('select.option', '-1', JText::alt('JOPTION_DO_NOT_USE', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
            }
            if (!$hideDefault) {
                $options[] = JHtml::_('select.option', '', JText::alt('JOPTION_USE_DEFAULT', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
            }

            // Get a list of folders in the search path with the given filter.
            $folders = JFolder::folders($path, $filter, true, true);

            $path = str_replace('\\', '/', $path);
            //find start of local url
            $pos = strpos($path, '/images');

            // Build the options list from the list of folders.
            if (is_array($folders)) {
                foreach ($folders as $folder) {

                    // Check to see if the file is in the exclude mask.
                    if ($exclude) {
                        if (preg_match(chr(1).$exclude.chr(1), $folder)) {
                            continue;
                        }
                    }
                    $folder = substr_replace($folder, '', 0, $pos);
                    $folder = str_replace('\\', '/', $folder);

                    $options[] = JHtml::_('select.option', $folder, $folder);
                }
            }

            // Merge any additional options in the XML definition.
            $options = array_merge(parent::getOptions(), $options);

            return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value, $control_name.$name);
        }
    }
}
