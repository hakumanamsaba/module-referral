<?php
declare(strict_types=1);

namespace WolfSellers\Referral\Controller\Customer\Referral;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session as CustomerSession;
use WolfSellers\Referral\Api\ReferralRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Delete
 *
 * Controller for deleting a customer referral.
 *
 * @category  WolfSellers
 * @package   WolfSellers_Referral
 * @author    Abraham Ruiz <abrahamruiz.hakum@gmail.com>
 */
class Delete extends Action
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
     * Delete constructor.
     *
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param ReferralRepositoryInterface $referralRepository
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        ReferralRepositoryInterface $referralRepository
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->referralRepository = $referralRepository;
    }

    /**
     * Execute method to delete a referral.
     *
     * @return \Magento\Framework\Controller\Result\Redirect
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
                throw new LocalizedException(__('You do not have permission to delete this referral.'));
            }

            $this->referralRepository->delete($referral);
            $this->messageManager->addSuccessMessage(__('Referral successfully deleted.'));
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('The referral does not exist.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while deleting the referral.'));
        }

        return $this->_redirect('referral/customer_referral/index');
    }
}
