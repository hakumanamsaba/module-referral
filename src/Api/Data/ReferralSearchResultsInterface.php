<?php
declare(strict_types=1);

namespace WolfSellers\Referral\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface ReferralSearchResultsInterface
 *
 * Interface for handling referral search results.
 *
 * @category  WolfSellers
 * @package   WolfSellers_Referral
 * @author    Abraham Ruiz <abrahamruiz.hakum@gmail.com>
 */
interface ReferralSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get the list of referral items.
     *
     * @return \WolfSellers\Referral\Api\Data\ReferralInterface[]
     */
    public function getItems();

    /**
     * Set the list of referral items.
     *
     * @param \WolfSellers\Referral\Api\Data\ReferralInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
