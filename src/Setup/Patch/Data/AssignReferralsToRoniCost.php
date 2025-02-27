<?php
declare(strict_types=1);

namespace WolfSellers\Referral\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use WolfSellers\Referral\Api\Data\ReferralInterfaceFactory;
use WolfSellers\Referral\Api\ReferralRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

class AssignReferralsToRoniCost implements DataPatchInterface, PatchRevertableInterface
{
    protected $customerFactory;
    protected $customerRepository;
    protected $referralFactory;
    protected $referralRepository;
    protected $searchCriteriaBuilder;

    public function __construct(
        CustomerFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        ReferralInterfaceFactory $referralFactory,
        ReferralRepositoryInterface $referralRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->referralFactory = $referralFactory;
        $this->referralRepository = $referralRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Aplica el parche: asigna 10 referidos a roni_cost@example.com
     */
    public function apply()
    {
        // 1. Obtener el ID del usuario por defecto roni_cost@example.com
        $customer = $this->customerFactory->create();
        $customer->setWebsiteId(1); // Asume website_id=1
        $customer->loadByEmail('roni_cost@example.com');

        if ($customer->getId()) {
            for ($i = 1; $i <= 10; $i++) {
                $referral = $this->referralFactory->create();
                $referral->setCustomerId($customer->getId());
                $referral->setEmail("referido{$i}@example.com");
                $referral->setStatus('pending');

                $this->referralRepository->save($referral);
            }
        }

        return $this;
    }

    /**
     * Revert method: elimina los referidos creados en apply().
     */
    public function revert()
    {
        // 1. Obtener el ID del usuario por defecto roni_cost@example.com
        $customer = $this->customerFactory->create();
        $customer->setWebsiteId(1);
        $customer->loadByEmail('roni_cost@example.com');

        if ($customer->getId()) {
            // Buscar los referidos creados con emails referidos1@example.com a referidos10@example.com
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter('customer_id', $customer->getId(), 'eq')
                ->addFilter('email', 'referido%@example.com', 'like') // Filtrar emails con 'referido%'
                ->create();

            $referrals = $this->referralRepository->getList($searchCriteria)->getItems();

            foreach ($referrals as $referral) {
                $this->referralRepository->delete($referral);
            }
        }
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
