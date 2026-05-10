<?php

declare(strict_types=1);

namespace Maispace\MaiTestimonials\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Testimonial extends AbstractEntity
{
    protected string $quote = '';

    protected string $authorName = '';

    protected string $authorRole = '';

    protected string $organisation = '';

    /**
     * @var ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected ObjectStorage $portrait;

    /**
     * @var ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
     */
    protected ObjectStorage $categories;

    public function __construct()
    {
        $this->portrait = new ObjectStorage();
        $this->categories = new ObjectStorage();
    }

    public function initializeObject(): void
    {
        $this->portrait = new ObjectStorage();
        $this->categories = new ObjectStorage();
    }

    public function getQuote(): string
    {
        return $this->quote;
    }

    public function setQuote(string $quote): void
    {
        $this->quote = $quote;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function setAuthorName(string $authorName): void
    {
        $this->authorName = $authorName;
    }

    public function getAuthorRole(): string
    {
        return $this->authorRole;
    }

    public function setAuthorRole(string $authorRole): void
    {
        $this->authorRole = $authorRole;
    }

    public function getOrganisation(): string
    {
        return $this->organisation;
    }

    public function setOrganisation(string $organisation): void
    {
        $this->organisation = $organisation;
    }

    /**
     * @return ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getPortrait(): ObjectStorage
    {
        return $this->portrait;
    }

    /**
     * @param ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $portrait
     */
    public function setPortrait(ObjectStorage $portrait): void
    {
        $this->portrait = $portrait;
    }

    public function getFirstPortrait(): ?\TYPO3\CMS\Extbase\Domain\Model\FileReference
    {
        $this->portrait->rewind();

        return $this->portrait->current() ?: null;
    }

    /**
     * @return ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
     */
    public function getCategories(): ObjectStorage
    {
        return $this->categories;
    }

    /**
     * @param ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category> $categories
     */
    public function setCategories(ObjectStorage $categories): void
    {
        $this->categories = $categories;
    }
}
