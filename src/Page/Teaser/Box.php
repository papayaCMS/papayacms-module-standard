<?php
/**
 * A simple CMS box for loading a page teaser
 *
 * @copyright  2013 by papaya Software GmbH - All rights reserved.
 * @link       http://www.papaya-cms.com/
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, version 2
 *
 * You can redistribute and/or modify this script under the terms of the GNU General Public
 * License (GPL) version 2, provided that the copyright and license notes, including these
 * lines, remain unmodified. papaya is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.
 *
 * @package    Papaya-Library
 * @subpackage Modules-Standard
 * @version    $Id: Box.php 39795 2014-05-06 15:35:52Z weinert $
 */

/*
* A simple CMS box for loading a page teaser
*
* @package Papaya-Library
* @subpackage Modules-Standard
*/
class PapayaModuleStandardPageTeaserBox
  extends
  PapayaObject
  implements
  PapayaPluginAppendable,
  PapayaPluginEditable,
  PapayaPluginCacheable {

  /**
   * @var NULL|PapayaPluginEditableContent
   */
  private $_content = NULL;

  /**
   * @var NULL|PapayaUiContentTeasersFactory
   */
  private $_contentTeasers = NULL;

  /**
   * @var PapayaCacheIdentifierDefinition
   */
  private $_cacheDefinition = NULL;

  /**
   * Append the page output xml to the DOM.
   *
   * @see PapayaXmlAppendable::appendTo()
   */
  public function appendTo(PapayaXmlElement $parent) {
    $pageId = $this->content()->get('pageid', '');
    if ($pageId !== '') {
      switch ($this->content()->get('loadingmode', 'id')) {
      case 'parent' :
        $teaser = $this->teaserFactory()->byParent($pageId);
        break;
      case 'id' :
      default :
        $teaser = $this->teaserFactory()->byPageId($pageId);
        break;
      }
      $teaser->appendTo($parent);
    }
  }

  /**
   * The content is an {@see ArrayObject} containing the stored data.
   *
   * @see PapayaPluginEditable::content()
   *
   * @param PapayaPluginEditableContent $content
   *
   * @return PapayaPluginEditableContent
   */
  public function content(PapayaPluginEditableContent $content = NULL) {
    if (isset($content)) {
      $this->_content = $content;
    } elseif (NULL == $this->_content) {
      $this->_content = new PapayaPluginEditableContent();
      $this->_content->callbacks()->onCreateEditor = array($this, 'createEditor');
    }
    return $this->_content;
  }

  /**
   * The editor is used to change the stored data in the administration interface.
   *
   * In this case it the editor creates an dialog from a field definition.
   *
   * @see PapayaPluginEditableContent::editor()
   *
   * @param object $callbackContext
   * @param PapayaPluginEditableContent $content
   *
   * @return PapayaPluginEditor
   */
  public function createEditor($callbackContext, PapayaPluginEditableContent $content) {
    $editor = new PapayaAdministrationPluginEditorFields(
      $content,
      array(
        'loadingmode' => array(
          'caption' => new PapayaUiStringTranslated('Loading Mode'),
          'type' => 'select',
          'parameters' => array(
            'id' => new PapayaUiStringTranslated('Id'),
            'parent' => new PapayaUiStringTranslated('Parent'),
          )
        ),
        'pageid' => array(
          'caption' => new PapayaUiStringTranslated('Page ID'),
          'type' => 'pageid',
          'parameters' => 10
        ),
        'Teaser Image',
        'teaser_image_width' => array(
          'caption' => new PapayaUiStringTranslated('Max width'),
          'mandatory' => FALSE,
          'type' => 'input',
          'parameters' => 15,
        ),
        'teaser_image_height' => array(
          'caption' => new PapayaUiStringTranslated('Max height'),
          'mandatory' => FALSE,
          'type' => 'input',
          'parameters' => 15,
        ),
        'resizemode' => array(
          'caption' => new PapayaUiStringTranslated('Resize mode'),
          'madatory' => TRUE,
          'type' => 'translatedcombo',
          'parameters' => array(
            'max' => 'Maximum',
            'min' => 'Minimum',
            'mincrop' => 'Minimum crop',
            'abs' => 'Absolute',
          ),
        ),
      )
    );
    $editor->papaya($this->papaya());
    return $editor;
  }

  /**
   * Get/set the PapayaUiContentTeasersFactory Object
   *
   * @param PapayaUiContentTeasersFactory $teasers
   * @return PapayaUiContentTeasersFactory
   */
  public function teaserFactory(PapayaUiContentTeasersFactory $teasers = NULL) {
    if (NULL !== $teasers) {
      $this->_contentTeasers = $teasers;
    } elseif (NULL === $this->_contentTeasers) {
      $imageHeight = $this->content()->get('teaser_image_height', 0);
      $imageWidth = $this->content()->get('teaser_image_width', 0);
      $this->_contentTeasers = new PapayaUiContentTeasersFactory($imageWidth, $imageHeight, 'max');
    }
    return $this->_contentTeasers;
  }

  /**
   * Define the code definition parameters for the output.
   *
   * @see PapayaPluginCacheable::cacheable()
   * @param PapayaCacheIdentifierDefinition $definition
   * @return PapayaCacheIdentifierDefinition
   */
  public function cacheable(PapayaCacheIdentifierDefinition $definition = NULL) {
    if (isset($definition)) {
      $this->_cacheDefinition = $definition;
    } elseif (NULL == $this->_cacheDefinition) {
      $this->_cacheDefinition = new PapayaCacheIdentifierDefinitionBoolean(TRUE);
    }
    return $this->_cacheDefinition;
  }

}
