<?php
declare(strict_types=1);

namespace WolfSellers\Referral\Block;

use Magento\Framework\View\Element\Html\Link as MagentoLink;
use Magento\Customer\Model\SessionFactory as CustomerSessionFactory;

/**
 * Class Link
 *
 * Block for rendering a referral link, visible only to non-logged-in users.
 *
 * @category  WolfSellers
 * @package   WolfSellers_Referral
 * @author    Abraham Ruiz <abrahamruiz.hakum@gmail.com>
 */
class Link extends MagentoLink
{
    /**
     * @var CustomerSessionFactory
     */
    protected CustomerSessionFactory $customerSessionFactory;

    /**
     * Link constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param CustomerSessionFactory $customerSessionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        CustomerSessionFactory $customerSessionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSessionFactory = $customerSessionFactory;
    }

    /**
     * Render the referral link only if the user is not logged in.
     *
     * @return string
     */
    public function _toHtml()
    {
        $customerSession = $this->customerSessionFactory->create();
        if ($customerSession->isLoggedIn()) {
            return '';
        }

        return parent::_toHtml();
    }
}
