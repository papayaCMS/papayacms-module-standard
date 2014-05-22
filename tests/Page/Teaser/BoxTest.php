<?php
require_once(dirname(__FILE__).'/../../bootstrap.php');

class PapayaModuleStandardPageTeaserBoxTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleStandardPageTeaserBox::appendTo
   */
  public function testAppendTo() {
    $box = new PapayaModuleStandardPageTeaserBox();

    $dom = new PapayaXmlDocument();
    $dom->appendElement('content');

    $contentMock = $this
      ->getMockBuilder('PapayaPluginEditableContent')
      ->getMock();
    $contentMock
      ->expects($this->any())
      ->method('get')
      ->will(
        $this->returnValueMap(
          array(
            array('pageid', '', NULL, 17),
            array('loadingmode', 'id', NULL, 'id')
          )
        )
      );
    $box->content($contentMock);

    $teaserMock = $this
      ->getMockBuilder('PapayaUiContentTeasers')
      ->disableOriginalConstructor()
      ->getMock();
    $teaserMock
      ->expects($this->once())
      ->method('appendTo')
      ->with($this->equalTo($dom->documentElement));

    $teaserFactoryMock = $this
      ->getMockBuilder('PapayaUiContentTeasersFactory')
      ->disableOriginalConstructor()
      ->getMock();
    $teaserFactoryMock
      ->expects($this->once())
      ->method('byPageId')
      ->with($this->equalTo('17'))
      ->will($this->returnValue($teaserMock));
    $box->teaserFactory($teaserFactoryMock);

    $box->appendTo($dom->documentElement);
  }

  /**
   * @covers PapayaModuleStandardPageTeaserBox::appendTo
   */
  public function testAppendToWithParentId() {
    $box = new PapayaModuleStandardPageTeaserBox();

    $dom = new PapayaXmlDocument();
    $dom->appendElement('content');

    $contentMock = $this
      ->getMockBuilder('PapayaPluginEditableContent')
      ->getMock();
    $contentMock
      ->expects($this->any())
      ->method('get')
      ->will(
        $this->returnValueMap(
          array(
            array('pageid', '', NULL, 17),
            array('loadingmode', 'id', NULL, 'parent')
          )
        )
      );
    $box->content($contentMock);

    $teaserMock = $this
      ->getMockBuilder('PapayaUiContentTeasers')
      ->disableOriginalConstructor()
      ->getMock();
    $teaserMock
      ->expects($this->once())
      ->method('appendTo')
      ->with($this->equalTo($dom->documentElement));

    $teaserFactoryMock = $this
      ->getMockBuilder('PapayaUiContentTeasersFactory')
      ->disableOriginalConstructor()
      ->getMock();
    $teaserFactoryMock
      ->expects($this->once())
      ->method('byParent')
      ->with($this->equalTo('17'))
      ->will($this->returnValue($teaserMock));
    $box->teaserFactory($teaserFactoryMock);

    $box->appendTo($dom->documentElement);
  }

  /**
   * @covers PapayaModuleStandardPageTeaserBox::content
   */
  public function testSetContent() {
    $box = new PapayaModuleStandardPageTeaserBox();
    $content = new PapayaPluginEditableContent(array());

    $this->assertSame($content, $box->content($content));
    $this->assertAttributeSame($content, '_content', $box);
  }

  /**
   * @covers PapayaModuleStandardPageTeaserBox::content
   * @covers PapayaModuleStandardPageTeaserBox::createEditor
   */
  public function testContentEditorGetImplicitCreate() {
    $box = new PapayaModuleStandardPageTeaserBox();
    $this->assertInstanceOf(
      'PapayaAdministrationPluginEditorFields', $box->content()->editor()
    );
  }

  /**
   * @covers PapayaModuleStandardPageTeaserBox::teaserFactory
   */
  public function testGetContentTeaserFactory() {
    $box = new PapayaModuleStandardPageTeaserBox();

    $this->assertInstanceOf('PapayaUiContentTeasersFactory', $box->teaserFactory());
    $this->assertAttributeInstanceOf('PapayaUiContentTeasersFactory', '_contentTeasers', $box);
  }

  /**
   * @covers PapayaModuleStandardPageTeaserBox::teaserFactory
   */
  public function testSetContentTeaserFactory() {
    $box = new PapayaModuleStandardPageTeaserBox();
    $teaserFactory = new PapayaUiContentTeasersFactory();

    $this->assertSame($teaserFactory, $box->teaserFactory($teaserFactory));
    $this->assertAttributeSame($teaserFactory, '_contentTeasers', $box);
  }


}
