<?php

declare(strict_types=1);

namespace Maispace\MaiTestimonials\Domain\Repository;

use Maispace\MaiTestimonials\Domain\Model\Testimonial;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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

    public function createQueryBuilderForPagination(array $pageUids = [], int $categoryUid = 0): QueryBuilder
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_maitestimonials_testimonial');

        $queryBuilder
            ->select('*')
            ->from('tx_maitestimonials_testimonial')
            ->orderBy('sorting', 'ASC');

        if ($pageUids !== []) {
            $queryBuilder
                ->andWhere(
                    $queryBuilder->expr()->in(
                        'pid',
                        $queryBuilder->createNamedParameter($pageUids, Connection::PARAM_INT_ARRAY)
                    )
                );
        }

        if ($categoryUid > 0) {
            $queryBuilder
                ->leftJoin(
                    'tx_maitestimonials_testimonial',
                    'sys_category_record_mm',
                    'mm',
                    $queryBuilder->expr()->eq('mm.uid_foreign', $queryBuilder->quoteIdentifier('tx_maitestimonials_testimonial.uid'))
                )
                ->andWhere(
                    $queryBuilder->expr()->eq('mm.uid_local', $queryBuilder->createNamedParameter($categoryUid, \PDO::PARAM_INT))
                )
                ->andWhere(
                    $queryBuilder->expr()->eq('mm.tablenames', $queryBuilder->createNamedParameter('tx_maitestimonials_testimonial'))
                )
                ->andWhere(
                    $queryBuilder->expr()->eq('mm.fieldname', $queryBuilder->createNamedParameter('categories'))
                );
        }

        return $queryBuilder;
    }
}
