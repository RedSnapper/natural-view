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



}