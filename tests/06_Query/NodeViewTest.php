<?php
namespace PHPCR\Tests\Query;

require_once('QueryBaseCase.php');

/**
 * test the query result node view $ 6.11.2
 */
class NodeViewTest extends QueryBaseCase
{
    public $nodeIterator;

    public function setUp()
    {
        parent::setUp();

        $this->nodeIterator = $this->query->execute()->getNodes();
    }

    public function testIterator()
    {
        $count = 0;

        foreach ($this->nodeIterator as $node) {
            $this->assertInstanceOf('PHPCR\NodeInterface', $node); // Test if the return element is an istance of node
            $count++;
        }

        $this->assertEquals(8, $count, 'wrong number of elements in iterator');
    }

    public function testSeekable()
    {
        $seekKey = '/tests_general_base/index.txt/jcr:content';
        $seekPosition = 6;

        $nodes = array();
        $i = 0;
        foreach ($this->nodeIterator as $nodeName => $node) {
            if ($i++ == $seekPosition) {
                $seekNode = $node;
            }
        }

        // note that in php 5.3.3, the array iterator gets the seek wrong and wants a string position instead of a number. according to the doc, we test for the correct behaviour here.
        $this->nodeIterator->seek($seekPosition);
        $this->assertEquals($seekKey, $this->nodeIterator->key());
        $this->assertEquals($seekNode, $this->nodeIterator->current());
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testSeekableOutOfBounds()
    {
        $this->nodeIterator->seek(30);
    }

    public function testCountable()
    {
        $nodes = array();
        foreach ($this->nodeIterator as $node) {
            $nodes[] = $node;
        }

        $this->assertEquals(count($nodes), $this->nodeIterator->count());
    }
}
