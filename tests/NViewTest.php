<?php

namespace RedSnapper\NaturalView\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RedSnapper\NaturalView\NView;

class NViewTest extends TestCase
{

    /** @test */
    public function can_load_by_string()
    {

        $view = new NView('<!DOCTYPE div><div xmlns="http://www.w3.org/1999/xhtml"/>');

        $this->assertEquals("<div></div>",$view->show());

        $wholeDocument = <<<EOT
<?xml version="1.0"?>
<!DOCTYPE div>
<div xmlns="http://www.w3.org/1999/xhtml"></div>

EOT;

        $this->assertEquals($wholeDocument,$view->show(true));

    }

    /** @test */
    public function can_load_by_file()
    {
        $view = new NView(__DIR__ . '/fixtures/div.html');
        $this->assertEquals("<div></div>",$view->show());
    }

    /** @test */
    public function can_load_existing_view()
    {
        $existingView = new NView('<!DOCTYPE div><div xmlns="http://www.w3.org/1999/xhtml"/>');
        $view = new NView($existingView);

        $this->assertEquals("<div></div>",$view->show());
    }

    /** @test */
    public function can_only_load_certain_types()
    {
        $this->expectException(InvalidArgumentException::class);
        new NView(1);
    }

    /** @test */
    public function can_get_a_value_by_xpath()
    {
        $view = new NView('<!DOCTYPE div><div xmlns="http://www.w3.org/1999/xhtml" id="main">Hello</div>');

        $this->assertEquals('main', $view->get('/h:div/@id'));
        $this->assertEquals('Hello', $view->get('/h:div/child::text()'));
    }

    /** @test */
    public function can_set_a_value_by_xpath()
    {
        $view = new NView('<!DOCTYPE div><div xmlns="http://www.w3.org/1999/xhtml"/>');

        $view->set('/h:div/@id', 'main');

        $this->assertEquals('<div id="main"></div>', $view->show());
    }

    /** @test */
    public function can_convert_a_string_to_a_node()
    {
        $view = new NView('<!DOCTYPE div><div xmlns="http://www.w3.org/1999/xhtml"/>');

        $view->set('/h:div/child-gap()', $view->strToNode('Hello'));

        $this->assertEquals('<div>Hello</div>', $view->show());
    }

    /** @test */
    public function falls_back_to_a_text_node_when_the_fragment_is_malformed()
    {
        $view = new NView('<!DOCTYPE div><div xmlns="http://www.w3.org/1999/xhtml"/>');

        $view->set('/h:div/child-gap()', $view->strToNode('<b>unclosed'));

        $this->assertEquals('<div>&lt;b&gt;unclosed</div>', $view->show());
    }
}