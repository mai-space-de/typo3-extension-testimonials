<?php

declare(strict_types=1);

namespace Maispace\MaiTestimonials\Controller;

use Maispace\MaiBase\Controller\AbstractActionController;
use Maispace\MaiBase\Controller\Traits\AppendDataToPluginVariablesTrait;
use Maispace\MaiBase\Controller\Traits\PageRendererTrait;
use Maispace\MaiTestimonials\Domain\Repository\TestimonialRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Page\PageRenderer;

class TestimonialsController extends AbstractActionController
{
    use AppendDataToPluginVariablesTrait;
    use PageRendererTrait;

    public function __construct(
        private readonly TestimonialRepository $testimonialRepository,
    ) {}

    public function injectPageRenderer(PageRenderer $pageRenderer): void
    {
        $this->pageRenderer = $pageRenderer;
    }

    public function injectAssetCollector(AssetCollector $assetCollector): void
    {
        $this->assetCollector = $assetCollector;
    }

    public function listAction(): ResponseInterface
    {
        $settings = $this->getSettings();

        $testimonials = $this->resolveTestimonials($settings);

        $this->addJsFile(
            'mai_testimonials',
            'EXT:mai_testimonials/Resources/Public/JavaScript/testimonials.js',
        );

        $this->view->assignMultiple([
            'testimonials' => $testimonials,
            'settings' => $settings,
        ]);

        return $this->htmlResponse();
    }

    public function sliderAction(): ResponseInterface
    {
        $settings = $this->getSettings();

        $testimonials = $this->resolveTestimonials($settings);

        $this->addJsFile(
            'mai_testimonials',
            'EXT:mai_testimonials/Resources/Public/JavaScript/testimonials.js',
        );

        $this->view->assignMultiple([
            'testimonials' => $testimonials,
            'settings' => $settings,
        ]);

        return $this->htmlResponse();
    }

    public function singleAction(): ResponseInterface
    {
        $settings = $this->getSettings();

        $testimonials = $this->resolveTestimonials($settings);
        $testimonial = $testimonials->getFirst();

        $this->view->assignMultiple([
            'testimonial' => $testimonial,
            'settings' => $settings,
        ]);

        return $this->htmlResponse();
    }

    private function resolveTestimonials(array $settings): \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
    {
        $pageUids = $this->resolveStoragePageUids();
        $categoryUid = (int) ($settings['categoryUid'] ?? 0);

        if ($pageUids !== [] && $categoryUid > 0) {
            return $this->testimonialRepository->findFromPagesByCategoryUid($pageUids, $categoryUid);
        }

        if ($pageUids !== []) {
            return $this->testimonialRepository->findFromPages($pageUids);
        }

        if ($categoryUid > 0) {
            return $this->testimonialRepository->findByCategoryUid($categoryUid);
        }

        return $this->testimonialRepository->findAll();
    }

    private function resolveStoragePageUids(): array
    {
        $pages = $this->settings['pages'] ?? '';
        if (empty($pages)) {
            return [];
        }

        return array_filter(
            array_map('intval', explode(',', (string) $pages)),
            static fn(int $uid): bool => $uid > 0,
        );
    }
}
