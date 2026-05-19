<?php

declare(strict_types=1);

namespace Maispace\MaiTestimonials\Tests\Unit\Domain\Model;

use Maispace\MaiTestimonials\Domain\Model\Testimonial;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

final class TestimonialTest extends TestCase
{
    // ── Default values ──────────────────────────────────────────────────────

    #[Test]
    public function defaultQuoteIsEmptyString(): void
    {
        $subject = new Testimonial();
        self::assertSame('', $subject->getQuote());
    }

    #[Test]
    public function defaultAuthorNameIsEmptyString(): void
    {
        $subject = new Testimonial();
        self::assertSame('', $subject->getAuthorName());
    }

    #[Test]
    public function defaultAuthorRoleIsEmptyString(): void
    {
        $subject = new Testimonial();
        self::assertSame('', $subject->getAuthorRole());
    }

    #[Test]
    public function defaultOrganisationIsEmptyString(): void
    {
        $subject = new Testimonial();
        self::assertSame('', $subject->getOrganisation());
    }

    #[Test]
    public function constructorInitializesPortraitAsObjectStorage(): void
    {
        $subject = new Testimonial();
        self::assertInstanceOf(ObjectStorage::class, $subject->getPortrait());
    }

    #[Test]
    public function constructorInitializesCategoriesAsObjectStorage(): void
    {
        $subject = new Testimonial();
        self::assertInstanceOf(ObjectStorage::class, $subject->getCategories());
    }

    #[Test]
    public function constructorCreatesEmptyPortraitStorage(): void
    {
        $subject = new Testimonial();
        self::assertCount(0, $subject->getPortrait());
    }

    #[Test]
    public function constructorCreatesEmptyCategoriesStorage(): void
    {
        $subject = new Testimonial();
        self::assertCount(0, $subject->getCategories());
    }

    // ── initializeObject ────────────────────────────────────────────────────

    #[Test]
    public function initializeObjectCreatesFreshPortraitStorage(): void
    {
        $subject = new Testimonial();
        $original = $subject->getPortrait();
        $subject->initializeObject();
        self::assertInstanceOf(ObjectStorage::class, $subject->getPortrait());
        self::assertNotSame($original, $subject->getPortrait());
    }

    #[Test]
    public function initializeObjectCreatesFreshCategoriesStorage(): void
    {
        $subject = new Testimonial();
        $original = $subject->getCategories();
        $subject->initializeObject();
        self::assertInstanceOf(ObjectStorage::class, $subject->getCategories());
        self::assertNotSame($original, $subject->getCategories());
    }

    #[Test]
    public function portraitStorageIsEmptyAfterInitializeObject(): void
    {
        $subject = new Testimonial();
        $subject->initializeObject();
        self::assertCount(0, $subject->getPortrait());
    }

    #[Test]
    public function categoriesStorageIsEmptyAfterInitializeObject(): void
    {
        $subject = new Testimonial();
        $subject->initializeObject();
        self::assertCount(0, $subject->getCategories());
    }

    // ── quote getter / setter ───────────────────────────────────────────────

    #[Test]
    public function setQuoteStoresTheValue(): void
    {
        $subject = new Testimonial();
        $subject->setQuote('A great product!');
        self::assertSame('A great product!', $subject->getQuote());
    }

    #[Test]
    public function setQuoteOverwritesPreviousValue(): void
    {
        $subject = new Testimonial();
        $subject->setQuote('First quote');
        $subject->setQuote('Second quote');
        self::assertSame('Second quote', $subject->getQuote());
    }

    #[Test]
    public function setQuoteAcceptsEmptyString(): void
    {
        $subject = new Testimonial();
        $subject->setQuote('Non-empty');
        $subject->setQuote('');
        self::assertSame('', $subject->getQuote());
    }

    // ── authorName getter / setter ──────────────────────────────────────────

    #[Test]
    public function setAuthorNameStoresTheValue(): void
    {
        $subject = new Testimonial();
        $subject->setAuthorName('Jane Doe');
        self::assertSame('Jane Doe', $subject->getAuthorName());
    }

    #[Test]
    public function setAuthorNameOverwritesPreviousValue(): void
    {
        $subject = new Testimonial();
        $subject->setAuthorName('First Author');
        $subject->setAuthorName('Second Author');
        self::assertSame('Second Author', $subject->getAuthorName());
    }

    #[Test]
    public function setAuthorNameAcceptsEmptyString(): void
    {
        $subject = new Testimonial();
        $subject->setAuthorName('Jane Doe');
        $subject->setAuthorName('');
        self::assertSame('', $subject->getAuthorName());
    }

    // ── authorRole getter / setter ──────────────────────────────────────────

    #[Test]
    public function setAuthorRoleStoresTheValue(): void
    {
        $subject = new Testimonial();
        $subject->setAuthorRole('CEO');
        self::assertSame('CEO', $subject->getAuthorRole());
    }

    #[Test]
    public function setAuthorRoleOverwritesPreviousValue(): void
    {
        $subject = new Testimonial();
        $subject->setAuthorRole('Manager');
        $subject->setAuthorRole('Director');
        self::assertSame('Director', $subject->getAuthorRole());
    }

    #[Test]
    public function setAuthorRoleAcceptsEmptyString(): void
    {
        $subject = new Testimonial();
        $subject->setAuthorRole('CEO');
        $subject->setAuthorRole('');
        self::assertSame('', $subject->getAuthorRole());
    }

    // ── organisation getter / setter ────────────────────────────────────────

    #[Test]
    public function setOrganisationStoresTheValue(): void
    {
        $subject = new Testimonial();
        $subject->setOrganisation('ACME Corp');
        self::assertSame('ACME Corp', $subject->getOrganisation());
    }

    #[Test]
    public function setOrganisationOverwritesPreviousValue(): void
    {
        $subject = new Testimonial();
        $subject->setOrganisation('First Company');
        $subject->setOrganisation('Second Company');
        self::assertSame('Second Company', $subject->getOrganisation());
    }

    #[Test]
    public function setOrganisationAcceptsEmptyString(): void
    {
        $subject = new Testimonial();
        $subject->setOrganisation('ACME Corp');
        $subject->setOrganisation('');
        self::assertSame('', $subject->getOrganisation());
    }

    // ── portrait getter / setter ────────────────────────────────────────────

    #[Test]
    public function setPortraitStoresTheObjectStorage(): void
    {
        $subject = new Testimonial();
        $storage = new ObjectStorage();
        $subject->setPortrait($storage);
        self::assertSame($storage, $subject->getPortrait());
    }

    #[Test]
    public function twoInstancesHaveIndependentPortraitStorages(): void
    {
        $subject1 = new Testimonial();
        $subject2 = new Testimonial();
        self::assertNotSame($subject1->getPortrait(), $subject2->getPortrait());
    }

    // ── categories getter / setter ──────────────────────────────────────────

    #[Test]
    public function setCategoriesStoresTheObjectStorage(): void
    {
        $subject = new Testimonial();
        $storage = new ObjectStorage();
        $subject->setCategories($storage);
        self::assertSame($storage, $subject->getCategories());
    }

    #[Test]
    public function twoInstancesHaveIndependentCategoryStorages(): void
    {
        $subject1 = new Testimonial();
        $subject2 = new Testimonial();
        self::assertNotSame($subject1->getCategories(), $subject2->getCategories());
    }

    // ── getFirstPortrait ────────────────────────────────────────────────────

    #[Test]
    public function getFirstPortraitReturnsNullWhenPortraitStorageIsEmpty(): void
    {
        $subject = new Testimonial();
        self::assertNull($subject->getFirstPortrait());
    }
}
