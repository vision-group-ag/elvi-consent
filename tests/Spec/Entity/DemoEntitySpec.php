<?php

namespace Spec\App\Entity;

use App\Entity\DemoEntity;
use PhpSpec\ObjectBehavior;

class DemoEntitySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('id', 'name', true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DemoEntity::class);
    }

    function it_can_be_constructed_with_ommitted_3rd_parameter()
    {
        $this->beConstructedWith('id', 'name');
        $this->shouldHaveType(DemoEntity::class);
    }

    function it_tells_the_id()
    {
        $this->getId()->shouldReturn('id');
    }

    function it_tells_the_name()
    {
        $this->getName()->shouldReturn('name');
    }
    function it_tells_if_it_is_public()
    {
        $this->isPublic()->shouldReturn(true);
    }
}
