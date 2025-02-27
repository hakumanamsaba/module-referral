<?php
declare(strict_types=1);

namespace WolfSellers\Referral\Block\Customer;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\SessionFactory as CustomerSessionFactory;
use WolfSellers\Referral\Api\ReferralRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class Referral
 *
 * Block for managing customer referrals in the frontend.
 *
 * @category  WolfSellers
 * @package   WolfSellers_Referral
 * @author    Abraham Ruiz <abrahamruiz.hakum@gmail.com>
 */
class Referral extends Template
{
    /**
     * @var CustomerSessionFactory
     */
    protected $customerSessionFactory;

    /**
     * @var ReferralRepositoryInterface
     */
    protected $referralRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * Referral constructor.
     *
     * @param Template\Context $context
     * @param CustomerSessionFactory $customerSessionFactory
     * @param ReferralRepositoryInterface $referralRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CustomerSessionFactory $customerSessionFactory,
        ReferralRepositoryInterface $referralRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSessionFactory = $customerSessionFactory;
        $this->referralRepository = $referralRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Retrieve the list of referrals for the logged-in customer.
     *
     * @return \WolfSellers\Referral\Api\Data\ReferralInterface[]
     */
    public function getReferrals()
    {
        $session = $this->customerSessionFactory->create();
        $customerId = $session->getCustomerId();
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('customer_id', $customerId, 'eq')
            ->create();

        $list = $this->referralRepository->getList($searchCriteria);
        return $list->getItems();
    }

    /**
     * Get the URL to add a new referral.
     *
     * @return string
     */
    public function getAddUrl()
    {
        return $this->getUrl('referral/customer_referral/create');
    }
}
