<?php

declare(strict_types=1);

namespace Maispace\MaiTestimonials\Domain\Repository;

use Maispace\MaiTestimonials\Domain\Model\Testimonial;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class TestimonialRepository extends Repository
{
    protected $defaultOrderings = [
        'sorting' => QueryInterface::ORDER_ASCENDING,
    ];

    public function findAllLimited(int $limit): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->setLimit($limit);

        return $query->execute();
    }

    public function findByCategoryUid(int $categoryUid, int $limit = 0): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->contains('categories', $categoryUid),
        );
        if ($limit > 0) {
            $query->setLimit($limit);
        }

        return $query->execute();
    }

    public function findFromPages(array $pageUids, int $limit = 0): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setStoragePageIds($pageUids);
        if ($limit > 0) {
            $query->setLimit($limit);
        }

        return $query->execute();
    }

    public function findFromPagesByCategoryUid(array $pageUids, int $categoryUid, int $limit = 0): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setStoragePageIds($pageUids);
        $query->matching(
            $query->contains('categories', $categoryUid),
        );
        if ($limit > 0) {
            $query->setLimit($limit);
        }

        return $query->execute();
    }
}
