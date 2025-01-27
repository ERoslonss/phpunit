<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework\MockObject;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Medium;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use PHPUnit\TestFixture\MockObject\AbstractClass;
use ReflectionProperty;

#[Group('test-doubles')]
#[Medium]
#[RequiresPhpExtension('soap')]
#[TestDox('getMockForAbstractClass()')]
final class GetMockForAbstractClassTest extends TestCase
{
    public function testCreatesMockObjectForAbstractClassAndAllowsConfigurationOfAbstractMethods(): void
    {
        $mock = $this->getMockForAbstractClass(AbstractClass::class);

        $mock->expects($this->once())->method('doSomethingElse')->willReturn(true);

        $this->assertTrue($mock->doSomething());
    }

    public function testCreatesMockObjectForAbstractClassAndDoesNotAllowConfigurationOfConcreteMethods(): void
    {
        $mock = $this->getMockForAbstractClass(AbstractClass::class);

        try {
            $mock->expects($this->once())->method('doSomething');
        } catch (MethodCannotBeConfiguredException $e) {
            $this->assertSame('Trying to configure method "doSomething" which cannot be configured because it does not exist, has not been specified, is final, or is static', $e->getMessage());

            return;
        } finally {
            $this->resetMockObjects();
        }

        $this->fail();
    }

    private function resetMockObjects(): void
    {
        (new ReflectionProperty(TestCase::class, 'mockObjects'))->setValue($this, []);
    }
}
