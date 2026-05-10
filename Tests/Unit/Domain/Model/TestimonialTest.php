<?php

declare(strict_types=1);

namespace Maispace\MaiTestimonials\Tests\Unit\Domain\Model;

use Maispace\MaiTestimonials\Domain\Model\Testimonial;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class TestimonialTest extends TestCase
{
    private Testimonial $subject;

    protected function setUp(): void
    {
        $this->subject = new Testimonial();
    }

    #[Test]
    public function quoteIsEmptyByDefault(): void
    {
        self::assertSame('', $this->subject->getQuote());
    }

    #[Test]
    public function quoteCanBeSet(): void
    {
        $this->subject->setQuote('A great product!');
        self::assertSame('A great product!', $this->subject->getQuote());
    }

    #[Test]
    public function authorNameIsEmptyByDefault(): void
    {
        self::assertSame('', $this->subject->getAuthorName());
    }

    #[Test]
    public function authorNameCanBeSet(): void
    {
        $this->subject->setAuthorName('Jane Doe');
        self::assertSame('Jane Doe', $this->subject->getAuthorName());
    }

    #[Test]
    public function authorRoleIsEmptyByDefault(): void
    {
        self::assertSame('', $this->subject->getAuthorRole());
    }

    #[Test]
    public function organisationIsEmptyByDefault(): void
    {
        self::assertSame('', $this->subject->getOrganisation());
    }

    #[Test]
    public function portraitIsObjectStorageByDefault(): void
    {
        self::assertInstanceOf(ObjectStorage::class, $this->subject->getPortrait());
    }

    #[Test]
    public function categoriesIsObjectStorageByDefault(): void
    {
        self::assertInstanceOf(ObjectStorage::class, $this->subject->getCategories());
    }

    #[Test]
    public function firstPortraitReturnsNullWhenPortraitIsEmpty(): void
    {
        self::assertNull($this->subject->getFirstPortrait());
    }
}
