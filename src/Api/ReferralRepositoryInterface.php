<?php
declare(strict_types=1);

namespace WolfSellers\Referral\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use WolfSellers\Referral\Api\Data\ReferralInterface;
use WolfSellers\Referral\Api\Data\ReferralSearchResultsInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Interface ReferralRepositoryInterface
 *
 * Repository interface for managing referrals.
 *
 * @category  WolfSellers
 * @package   WolfSellers_Referral
 * @author    Abraham Ruiz <abrahamruiz.hakum@gmail.com>
 */
interface ReferralRepositoryInterface
{
    /**
     * Get a referral by its ID.
     *
     * @param int $id
     * @return \WolfSellers\Referral\Api\Data\ReferralInterface
     * @throws NoSuchEntityException
     */
    public function getById($id);

    /**
     * Save a referral.
     *
     * @param ReferralInterface $referral
     * @return ReferralInterface
     * @throws CouldNotSaveException
     */
    public function save(ReferralInterface $referral);

    /**
     * Delete a referral.
     *
     * @param ReferralInterface $referral
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ReferralInterface $referral);

    /**
     * Delete a referral by its ID.
     *
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     */
    public function deleteById($id);

    /**
     * Get a list of referrals based on search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return ReferralSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Update a referral by its ID.
     *
     * @param int $id
     * @param ReferralInterface $referral
     * @return ReferralInterface
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     */
    public function updateById(int $id, ReferralInterface $referral): ReferralInterface;
}
