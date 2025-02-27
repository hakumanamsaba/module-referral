<?php
declare(strict_types=1);

namespace WolfSellers\Referral\Controller\Customer\Referral;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session as CustomerSession;
use WolfSellers\Referral\Api\ReferralRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Edit
 *
 * Controller for editing a customer referral.
 *
 * @category  WolfSellers
 * @package   WolfSellers_Referral
 * @author    Abraham Ruiz <abrahamruiz.hakum@gmail.com>
 */
class Edit extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var ReferralRepositoryInterface
     */
    protected $referralRepository;

    /**
     * Edit constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param CustomerSession $customerSession
     * @param ReferralRepositoryInterface $referralRepository
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CustomerSession $customerSession,
        ReferralRepositoryInterface $referralRepository
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
        $this->referralRepository = $referralRepository;
    }

    /**
     * Execute method to load and edit a referral.
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return $this->_redirect('customer/account/login');
        }

        $referralId = (int)$this->getRequest()->getParam('id');
        if (!$referralId) {
            $this->messageManager->addErrorMessage(__('Invalid referral ID.'));
            return $this->_redirect('referral/customer_referral/index');
        }

        try {
            $referral = $this->referralRepository->getById($referralId);

            if ($referral->getCustomerId() !== $this->customerSession->getCustomerId()) {
                throw new LocalizedException(__('You do not have permission to edit this referral.'));
            }

            $this->customerSession->setData('current_referral', $referral);
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('The referral does not exist.'));
            return $this->_redirect('referral/customer_referral/index');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->_redirect('referral/customer_referral/index');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred.'));
            return $this->_redirect('referral/customer_referral/index');
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Edit Referral'));
        return $resultPage;
    }
}
