<?php
declare(strict_types=1);

namespace WolfSellers\Referral\Block\Customer;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\NoSuchEntityException;
use WolfSellers\Referral\Api\ReferralRepositoryInterface;
use WolfSellers\Referral\Model\Data\Referral as ReferralModel;

/**
 * Class ReferralForm
 *
 * Block for managing the referral form in the frontend.
 *
 * @category  WolfSellers
 * @package   WolfSellers_Referral
 * @author    Abraham Ruiz <abrahamruiz.hakum@gmail.com>
 */
class ReferralForm extends Template
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var ReferralRepositoryInterface
     */
    protected $referralRepository;

    /**
     * ReferralForm constructor.
     *
     * @param Template\Context $context
     * @param CustomerSession $customerSession
     * @param ReferralRepositoryInterface $referralRepository
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CustomerSession $customerSession,
        ReferralRepositoryInterface $referralRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->referralRepository = $referralRepository;
    }

    /**
     * Get the form action URL (Save).
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('referral/customer_referral/save');
    }

    /**
     * Determine if the form is in 'edit' or 'create' mode.
     *
     * @return string
     */
    public function getMode()
    {
        // Configured in layout: <argument name="mode" xsi:type="string">create|edit</argument>
        return $this->getData('mode') ?: 'create';
    }

    /**
     * Get the referral being edited (if in edit mode).
     *
     * @return ReferralModel|null
     */
    public function getReferral()
    {
        if ($this->getMode() === 'edit') {
            // Retrieve from session (stored in Edit.php)
            // or load it by ID if preferred
            $referral = $this->customerSession->getData('current_referral');
            if ($referral) {
                return $referral;
            }
        }
        // Return null if no referral is being edited
        return null;
    }
}
