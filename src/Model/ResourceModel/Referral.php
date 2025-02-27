<?php
declare(strict_types=1);

namespace WolfSellers\Referral\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Referral
 *
 * Resource model for the referral entity.
 *
 * @category  WolfSellers
 * @package   WolfSellers_Referral
 * @author    Abraham Ruiz <abrahamruiz.hakum@gmail.com>
 */
class Referral extends AbstractDb
{
    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('wolfsellers_referral', 'entity_id');
    }
}
