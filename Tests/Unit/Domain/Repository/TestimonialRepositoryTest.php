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
}
