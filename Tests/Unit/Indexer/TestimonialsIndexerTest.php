<?php

declare(strict_types=1);

namespace Maispace\MaiTestimonials\Tests\Unit\Indexer;

use Maispace\MaiTestimonials\Domain\Model\Testimonial;
use Maispace\MaiTestimonials\Indexer\TestimonialsIndexer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TestimonialsIndexerTest extends TestCase
{
    private TestimonialsIndexer $subject;

    protected function setUp(): void
    {
        $this->subject = new TestimonialsIndexer();
    }

    #[Test]
    public function getTypeReturnsTestimonial(): void
    {
        self::assertSame('testimonial', $this->subject->getType());
    }

    #[Test]
    public function supportsTestimonialsTable(): void
    {
        self::assertTrue($this->subject->supports('tx_maitestimonials_testimonial'));
    }

    #[Test]
    public function doesNotSupportOtherTables(): void
    {
        self::assertFalse($this->subject->supports('tx_mainews_news'));
        self::assertFalse($this->subject->supports('pages'));
        self::assertFalse($this->subject->supports('tt_content'));
    }

    #[Test]
    public function getIconReturnsExpectedValue(): void
    {
        self::assertSame('content-testimonials', $this->subject->getIcon('testimonial'));
    }

    #[Test]
    public function buildContentStripsHtmlTags(): void
    {
        $testimonial = new Testimonial();
        $testimonial->setQuote('<p>Great service! <strong>Highly recommended.</strong></p>');
        $testimonial->setOrganisation('ACME Corp');

        $content = $this->invokeBuildContent($testimonial);

        self::assertStringNotContainsString('<p>', $content);
        self::assertStringNotContainsString('<strong>', $content);
        self::assertStringContainsString('Great service!', $content);
        self::assertStringContainsString('Highly recommended.', $content);
        self::assertStringContainsString('ACME Corp', $content);
    }

    #[Test]
    public function buildContentReturnsEmptyStringForNonTestimonialRecord(): void
    {
        $content = $this->invokeBuildContent(new \stdClass());

        self::assertSame('', $content);
    }

    #[Test]
    public function buildContentOmitsOrganisationWhenEmpty(): void
    {
        $testimonial = new Testimonial();
        $testimonial->setQuote('Amazing experience!');

        $content = $this->invokeBuildContent($testimonial);

        self::assertSame('Amazing experience!', $content);
    }

    #[Test]
    public function formatResultReturnsSearchResultWithCorrectType(): void
    {
        $solrDoc = [
            'title_s' => 'John Doe',
            'content_t' => 'Great organization, wonderful work!',
            'url_s' => '/testimonials',
            'score' => 4.5,
        ];

        $result = $this->subject->formatResult($solrDoc);

        self::assertSame('testimonial', $result->type);
        self::assertSame('John Doe', $result->title);
        self::assertSame('/testimonials', $result->url);
        self::assertSame('content-testimonials', $result->icon);
        self::assertSame(4.5, $result->score);
    }

    #[Test]
    public function formatResultDefaultsToEmptyStringsWhenFieldsAreMissing(): void
    {
        $result = $this->subject->formatResult([]);

        self::assertSame('', $result->title);
        self::assertSame('', $result->url);
        self::assertSame(0.0, $result->score);
        self::assertNull($result->date);
    }

    private function invokeBuildContent(object $record): string
    {
        $reflection = new \ReflectionMethod($this->subject, 'buildContent');
        $reflection->setAccessible(true);

        /** @var string $result */
        return $reflection->invoke($this->subject, $record);
    }
}
