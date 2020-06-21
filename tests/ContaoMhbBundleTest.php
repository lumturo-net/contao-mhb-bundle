<?php

/*
 * This file is part of [package name].
 *
 * (c) John Doe
 *
 * @license LGPL-3.0-or-later
 */

namespace Lumturo\ContaoMhbBundle\Tests;

use Lumturo\ContaoMhbBundle\ContaoMhbBundle;
use PHPUnit\Framework\TestCase;

class ContaoMhbBundleTest extends TestCase
{
    public function testCanBeInstantiated()
    {
        $bundle = new ContaoMhbBundle();

        $this->assertInstanceOf('Lumturo\ContaoMhbBundle\ContaoMhbBundle', $bundle);
    }
}
