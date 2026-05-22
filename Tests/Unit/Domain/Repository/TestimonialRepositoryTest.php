<?php

declare(strict_types=1);

namespace Maispace\MaiTestimonials\Tests\Unit\Domain\Repository;

use Maispace\MaiTestimonials\Domain\Repository\TestimonialRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

final class TestimonialRepositoryTest extends TestCase
{
    #[Test]
    public function repositoryExtendsTYPO3BaseRepository(): void
    {
        self::assertTrue(
            is_subclass_of(TestimonialRepository::class, Repository::class),
            TestimonialRepository::class . ' must extend ' . Repository::class,
        );
    }

    #[Test]
    public function defaultOrderingsContainSortingAscending(): void
    {
        $reflection = new \ReflectionClass(TestimonialRepository::class);
        $defaults = $reflection->getDefaultProperties();

        self::assertArrayHasKey('defaultOrderings', $defaults);
        self::assertIsArray($defaults['defaultOrderings']);
        self::assertArrayHasKey('sorting', $defaults['defaultOrderings']);
        self::assertSame(QueryInterface::ORDER_ASCENDING, $defaults['defaultOrderings']['sorting']);
    }

    #[Test]
    public function defaultOrderingsContainExactlyOneSortKey(): void
    {
        $reflection = new \ReflectionClass(TestimonialRepository::class);
        $defaults = $reflection->getDefaultProperties();

        self::assertCount(1, $defaults['defaultOrderings']);
    }

    #[Test]
    public function findAllLimitedMethodHasRequiredLimitParameter(): void
    {
        $method = new \ReflectionMethod(TestimonialRepository::class, 'findAllLimited');
        $params = $method->getParameters();

        self::assertCount(1, $params, 'findAllLimited must have exactly one parameter');
        self::assertSame('limit', $params[0]->getName());
        self::assertFalse($params[0]->isOptional(), 'limit parameter must be required');
        self::assertSame('int', $params[0]->getType()?->getName());
    }

    #[Test]
    public function findByCategoryUidHasOptionalLimitParameter(): void
    {
        $method = new \ReflectionMethod(TestimonialRepository::class, 'findByCategoryUid');
        $params = $method->getParameters();

        self::assertCount(2, $params, 'findByCategoryUid must have exactly two parameters');
        self::assertSame('limit', $params[1]->getName());
        self::assertTrue($params[1]->isOptional(), 'limit must be optional');
        self::assertSame(0, $params[1]->getDefaultValue(), 'limit default must be 0');
        self::assertSame('int', $params[1]->getType()?->getName());
    }

    #[Test]
    public function findFromPagesHasOptionalLimitParameter(): void
    {
        $method = new \ReflectionMethod(TestimonialRepository::class, 'findFromPages');
        $params = $method->getParameters();

        self::assertCount(2, $params, 'findFromPages must have exactly two parameters');
        self::assertSame('limit', $params[1]->getName());
        self::assertTrue($params[1]->isOptional(), 'limit must be optional');
        self::assertSame(0, $params[1]->getDefaultValue(), 'limit default must be 0');
        self::assertSame('int', $params[1]->getType()?->getName());
    }

    #[Test]
    public function findFromPagesByCategoryUidHasOptionalLimitParameter(): void
    {
        $method = new \ReflectionMethod(TestimonialRepository::class, 'findFromPagesByCategoryUid');
        $params = $method->getParameters();

        self::assertCount(3, $params, 'findFromPagesByCategoryUid must have exactly three parameters');
        self::assertSame('limit', $params[2]->getName());
        self::assertTrue($params[2]->isOptional(), 'limit must be optional');
        self::assertSame(0, $params[2]->getDefaultValue(), 'limit default must be 0');
        self::assertSame('int', $params[2]->getType()?->getName());
    }
}
