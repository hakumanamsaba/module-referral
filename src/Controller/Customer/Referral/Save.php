<?php
declare(strict_types=1);

namespace WolfSellers\Referral\Controller\Customer\Referral;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session as CustomerSession;
use WolfSellers\Referral\Api\ReferralRepositoryInterface;
use WolfSellers\Referral\Api\Data\ReferralInterfaceFactory;
use WolfSellers\Referral\Api\Data\ReferralInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

/**
 * Class Save
 *
 * Controller for saving a new customer referral.
 *
 * @category  WolfSellers
 * @package   WolfSellers_Referral
 * @author    Abraham Ruiz <abrahamruiz.hakum@gmail.com>
 */
class Save extends Action
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
     * @var ReferralInterfaceFactory
     */
    protected $referralFactory;

    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param ReferralRepositoryInterface $referralRepository
     * @param ReferralInterfaceFactory $referralFactory
     * @param CustomerFactory $customerFactory
     * @param StoreManagerInterface $storeManager
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        ReferralRepositoryInterface $referralRepository,
        ReferralInterfaceFactory $referralFactory,
        CustomerFactory $customerFactory,
        StoreManagerInterface $storeManager,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->referralRepository = $referralRepository;
        $this->referralFactory = $referralFactory;
        $this->customerFactory = $customerFactory;
        $this->storeManager = $storeManager;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->logger = $logger;
    }

    /**
     * Execute method to save a new referral.
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if (!$data || !isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->messageManager->addErrorMessage(__('Invalid email.'));
            return $resultRedirect->setPath('referral/customer_referral/index');
        }

        $customerId = $this->customerSession->getCustomerId() ?? null;
        $email = trim($data['email']);
        $entityId = isset($data['entity_id']) ? (int) $data['entity_id'] : null;

        try {
            $websiteId = $this->storeManager->getWebsite()->getId();
            $customer = $this->customerFactory->create();
            $customer->setWebsiteId($websiteId);
            $customer->loadByEmail($email);

            // Si es un nuevo referido y el email ya pertenece a un cliente, lanzamos una excepción
            if (!$entityId && $customer->getId()) {
                throw new LocalizedException(__('This email is already registered and cannot be referred.'));
            }

            // Si estamos en modo edición, no debe validar que el email sea único
            if ($entityId) {
                // Verificar que el referido no exista para este cliente
                $searchCriteria = $this->searchCriteriaBuilder
                    ->addFilter('customer_id', $customerId, 'eq')
                    ->addFilter('email', $email, 'eq')
                    ->create();

                $existingReferrals = $this->referralRepository->getList($searchCriteria)->getItems();

                // Si ya existe, solo actualiza el referido
                if (!empty($existingReferrals)) {
                    $referral = array_shift($existingReferrals); // Cargar el referido existente para editarlo
                } else {
                    $referral = $this->referralFactory->create(); // Crear un nuevo referido si no existe
                }
            } else {
                // En caso de crear un nuevo referido
                $referral = $this->referralFactory->create();
            }

            // Asignar datos al referido
            $referral->setFirstName($data['first_name'] ?? null);
            $referral->setLastName($data['last_name'] ?? null);
            $referral->setEmail($data['email'] ?? '');
            $referral->setPhone($data['phone'] ?? null);
            $referral->setCustomerId($customerId);
            $referral->setStatus($data['status'] ?? 'pending');

            // Guardar el referido
            $this->referralRepository->save($referral);

            $this->messageManager->addSuccessMessage(__('The referral has been successfully saved.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->error('Error saving referral: ' . $e->getMessage());
            $this->messageManager->addErrorMessage(__('An unexpected error occurred while saving the referral.'));
        }

        return $resultRedirect->setPath('referral/customer_referral/index');
    }
}
