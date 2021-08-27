<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Event;

use Countable;
use IteratorAggregate;

/**
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
 */
final class TestDataCollection implements Countable, IteratorAggregate
{
    /**
     * @psalm-var list<TestData>
     */
    private array $data;

    /**
     * @psalm-param list<TestData> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(...$data);
    }

    private function __construct(TestData ...$data)
    {
        $this->data = $data;
    }

    /**
     * @psalm-return list<TestData>
     */
    public function asArray(): array
    {
        return $this->data;
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function getIterator(): TestDataCollectionIterator
    {
        return new TestDataCollectionIterator($this);
    }
}