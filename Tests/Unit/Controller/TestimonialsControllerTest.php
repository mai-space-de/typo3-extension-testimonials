<?php

declare(strict_types=1);

namespace Maispace\MaiTestimonials\Tests\Unit\Controller;

use Maispace\MaiTestimonials\Controller\TestimonialsController;
use Maispace\MaiTestimonials\Domain\Repository\TestimonialRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

final class TestimonialsControllerTest extends TestCase
{
    // ── resolveStoragePageUids ──────────────────────────────────────────────

    #[Test]
    public function resolveStoragePageUidsReturnsEmptyArrayWhenPagesSettingIsEmpty(): void
    {
        $controller = $this->createController();
        $result = $this->callResolveStoragePageUids($controller, []);

        self::assertSame([], $result);
    }

    #[Test]
    public function resolveStoragePageUidsReturnsEmptyArrayWhenPagesSettingIsMissing(): void
    {
        $controller = $this->createController();
        $result = $this->callResolveStoragePageUids($controller, ['other' => 'value']);

        self::assertSame([], $result);
    }

    #[Test]
    public function resolveStoragePageUidsReturnsArrayOfIntegersFromCommaSeparatedString(): void
    {
        $controller = $this->createController();
        $result = $this->callResolveStoragePageUids($controller, ['pages' => '10,20,30']);

        self::assertSame([10, 20, 30], $result);
    }

    #[Test]
    public function resolveStoragePageUidsFiltersOutZeroAndNegativeValues(): void
    {
        $controller = $this->createController();
        $result = $this->callResolveStoragePageUids($controller, ['pages' => '10,0,-5,20']);

        self::assertSame([10, 20], array_values($result));
    }

    #[Test]
    public function resolveStoragePageUidsHandlesEmptyCommaSegments(): void
    {
        $controller = $this->createController();
        $result = $this->callResolveStoragePageUids($controller, ['pages' => '10,,20,30']);

        self::assertSame([10, 20, 30], array_values($result));
    }

    // ── resolveTestimonials ─────────────────────────────────────────────────

    #[Test]
    public function resolveTestimonialsReturnsAllWhenNoSettingsProvided(): void
    {
        $repository = $this->createMock(TestimonialRepository::class);
        $repository->expects(self::once())
            ->method('findAll')
            ->willReturn($this->createMock(QueryResultInterface::class));

        $controller = $this->createController($repository);
        $result = $this->callResolveTestimonials($controller, []);

        self::assertInstanceOf(QueryResultInterface::class, $result);
    }

    #[Test]
    public function resolveTestimonialsCallsFindAllLimitedWhenOnlyLimitIsSet(): void
    {
        $repository = $this->createMock(TestimonialRepository::class);
        $repository->expects(self::once())
            ->method('findAllLimited')
            ->with(5)
            ->willReturn($this->createMock(QueryResultInterface::class));

        $controller = $this->createController($repository);
        $result = $this->callResolveTestimonials($controller, ['limit' => '5']);

        self::assertInstanceOf(QueryResultInterface::class, $result);
    }

    #[Test]
    public function resolveTestimonialsCallsFindByCategoryUidWhenOnlyCategoryIsSet(): void
    {
        $repository = $this->createMock(TestimonialRepository::class);
        $repository->expects(self::once())
            ->method('findByCategoryUid')
            ->with(3, 0)
            ->willReturn($this->createMock(QueryResultInterface::class));

        $controller = $this->createController($repository);
        $result = $this->callResolveTestimonials($controller, ['categoryUid' => '3']);

        self::assertInstanceOf(QueryResultInterface::class, $result);
    }

    #[Test]
    public function resolveTestimonialsCallsFindByCategoryUidWithLimitWhenBothSet(): void
    {
        $repository = $this->createMock(TestimonialRepository::class);
        $repository->expects(self::once())
            ->method('findByCategoryUid')
            ->with(3, 10)
            ->willReturn($this->createMock(QueryResultInterface::class));

        $controller = $this->createController($repository);
        $result = $this->callResolveTestimonials($controller, [
            'categoryUid' => '3',
            'limit' => '10',
        ]);

        self::assertInstanceOf(QueryResultInterface::class, $result);
    }

    #[Test]
    public function resolveTestimonialsCallsFindFromPagesWhenPageUidsAreGiven(): void
    {
        $repository = $this->createMock(TestimonialRepository::class);
        $repository->expects(self::once())
            ->method('findFromPages')
            ->with([10, 20], 0)
            ->willReturn($this->createMock(QueryResultInterface::class));

        $controller = $this->createController($repository);
        $result = $this->callResolveTestimonials($controller, ['pages' => '10,20']);

        self::assertInstanceOf(QueryResultInterface::class, $result);
    }

    #[Test]
    public function resolveTestimonialsCallsFindFromPagesByCategoryWhenBothPageUidsAndCategoryGiven(): void
    {
        $repository = $this->createMock(TestimonialRepository::class);
        $repository->expects(self::once())
            ->method('findFromPagesByCategoryUid')
            ->with([10, 20], 3, 0)
            ->willReturn($this->createMock(QueryResultInterface::class));

        $controller = $this->createController($repository);
        $result = $this->callResolveTestimonials($controller, [
            'pages' => '10,20',
            'categoryUid' => '3',
        ]);

        self::assertInstanceOf(QueryResultInterface::class, $result);
    }

    #[Test]
    public function resolveTestimonialsPriorityIsPagesPlusCategoryOverEverythingElse(): void
    {
        $repository = $this->createMock(TestimonialRepository::class);
        $repository->expects(self::once())
            ->method('findFromPagesByCategoryUid')
            ->with([10, 20], 3, 5)
            ->willReturn($this->createMock(QueryResultInterface::class));

        $controller = $this->createController($repository);
        $result = $this->callResolveTestimonials($controller, [
            'pages' => '10,20',
            'categoryUid' => '3',
            'limit' => '5',
        ]);

        self::assertInstanceOf(QueryResultInterface::class, $result);
    }

    // ── helpers ─────────────────────────────────────────────────────────────

    private function createController(
        ?TestimonialRepository $repository = null,
    ): TestimonialsController {
        $repository ??= $this->createMock(TestimonialRepository::class);

        return new TestimonialsController($repository);
    }

    private function callResolveStoragePageUids(
        TestimonialsController $controller,
        array $settings,
    ): array {
        $this->injectSettings($controller, $settings);

        $reflection = new \ReflectionMethod($controller, 'resolveStoragePageUids');
        return $reflection->invoke($controller);
    }

    private function callResolveTestimonials(
        TestimonialsController $controller,
        array $settings,
    ): mixed {
        $this->injectSettings($controller, $settings);

        $reflection = new \ReflectionMethod($controller, 'resolveTestimonials');
        return $reflection->invoke($controller, $settings);
    }

    private function injectSettings(object $controller, array $settings): void
    {
        $reflection = new \ReflectionProperty($controller, 'settings');
        $reflection->setValue($controller, $settings);
    }
}
