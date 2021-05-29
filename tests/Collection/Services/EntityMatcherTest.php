<?php

namespace Mrself\Attributes\Tests\Collection\Services;

use Mrself\Attributes\Collection\AttributeCollection;
use Mrself\Attributes\Collection\Collection;
use Mrself\Attributes\Services\EntityMatcher;
use Mrself\Attributes\Tests\Mocks\HasParentEntity;
use Mrself\Attributes\Tests\Test;

class EntityMatcherTest extends Test
{
    public function testItReturnsTrueIfSourceAndTargetAreEmpty()
    {
        $matcher = EntityMatcher::make(AttributeCollection::from(), AttributeCollection::from());
        $this->assertTrue($matcher->checkMatch());
    }

    public function testItReturnsFalseIfTargetHasOneExtraEntity()
    {
        $entity = new class extends HasParentEntity {
            public function getId(): int
            {
                return 1;
            }
        };
        $target = AttributeCollection::from([$entity]);
        $matcher = EntityMatcher::make(AttributeCollection::from(), $target);

        $this->assertFalse($matcher->checkMatch());
    }

    public function testItReturnsTrueIfSourceHasOneExtraEntity()
    {
        $entity = new class extends HasParentEntity {
            public function getId(): int
            {
                return 1;
            }
        };
        $source = AttributeCollection::from([$entity]);
        $matcher = EntityMatcher::make($source, AttributeCollection::from());

        $this->assertTrue($matcher->checkMatch());
    }

    public function testItReturnsTrueIfSourceMatchesTarget()
    {
        $entity = new class extends HasParentEntity {
            public function getId(): int
            {
                return 1;
            }
        };
        $source = AttributeCollection::from([$entity]);
        $target = AttributeCollection::from([$entity]);
        $matcher = EntityMatcher::make($source, $target);

        $this->assertTrue($matcher->checkMatch());
    }

    public function testItReturnsTrueIfTarget()
    {
        $entity = new class extends HasParentEntity {
            public function getId(): int
            {
                return 1;
            }
        };
        $source = AttributeCollection::from([$entity]);
        $target = AttributeCollection::from([$entity]);
        $matcher = EntityMatcher::make($source, $target);

        $this->assertTrue($matcher->checkMatch());
    }
}
