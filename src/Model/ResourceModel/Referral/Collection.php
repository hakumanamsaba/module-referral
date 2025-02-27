<?php
declare(strict_types=1);

namespace WolfSellers\Referral\Model\ResourceModel\Referral;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use WolfSellers\Referral\Model\Data\Referral as ReferralModel;
use WolfSellers\Referral\Model\ResourceModel\Referral as ReferralResource;

/**
 * Class Collection
 *
 * Collection for referral entities.
 *
 * @category  WolfSellers
 * @package   WolfSellers_Referral
 * @author    Abraham Ruiz <abrahamruiz.hakum@gmail.com>
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Initialize collection.
     */
    protected function _construct()
    {
        $this->_init(ReferralModel::class, ReferralResource::class);
    }
}
