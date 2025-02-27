<?php
declare(strict_types=1);

namespace WolfSellers\Referral\Model;

use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use WolfSellers\Referral\Api\ReferralRepositoryInterface;
use WolfSellers\Referral\Api\Data\ReferralInterface;
use WolfSellers\Referral\Api\Data\ReferralSearchResultsInterfaceFactory;
use WolfSellers\Referral\Model\ResourceModel\Referral as ReferralResource;
use WolfSellers\Referral\Model\ResourceModel\Referral\CollectionFactory as ReferralCollectionFactory;
use WolfSellers\Referral\Api\Data\ReferralInterfaceFactory;

/**
 * Class ReferralRepository
 *
 * Repository for managing referral entities.
 *
 * @category  WolfSellers
 * @package   WolfSellers_Referral
 * @author    Abraham Ruiz <abrahamruiz.hakum@gmail.com>
 */
class ReferralRepository implements ReferralRepositoryInterface
{
    /**
     * @var ReferralResource
     */
    protected $resource;

    /**
     * @var ReferralInterfaceFactory
     */
    protected $referralFactory;

    /**
     * @var ReferralCollectionFactory
     */
    protected $referralCollectionFactory;

    /**
     * @var ReferralSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var CustomerRepository
     */
    protected CustomerRepository $customerRepository;

    /**
     * ReferralRepository constructor.
     *
     * @param ReferralResource $resource
     * @param ReferralInterfaceFactory $referralFactory
     * @param ReferralCollectionFactory $referralCollectionFactory
     * @param ReferralSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CustomerRepository $customerRepository
     */
    public function __construct(
        ReferralResource $resource,
        ReferralInterfaceFactory $referralFactory,
        ReferralCollectionFactory $referralCollectionFactory,
        ReferralSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        CustomerRepository $customerRepository
    ) {
        $this->resource = $resource;
        $this->referralFactory = $referralFactory;
        $this->referralCollectionFactory = $referralCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Get a referral by ID.
     *
     * @param int $id
     * @return ReferralInterface
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        $referral = $this->referralFactory->create();
        $this->resource->load($referral, $id);
        if (!$referral->getId()) {
            throw new NoSuchEntityException(__('The referral with ID "%1" does not exist.', $id));
        }
        return $referral;
    }

    /**
     * Save a referral.
     *
     * @param ReferralInterface $referral
     * @return ReferralInterface
     * @throws CouldNotSaveException
     */
    public function save(ReferralInterface $referral)
    {
        try {
            $status = ReferralInterface::PENDING_STATUS;

            if ($referral->getCustomerId() !== null) {
                $customer = $this->customerRepository->getById($referral->getCustomerId());
                $status = $customer && $customer->getId() ? ReferralInterface::REGISTERED_STATUS : $status;
            }

            $referral->setStatus($status);
            $this->resource->save($referral);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $referral;
    }

    /**
     * Delete a referral.
     *
     * @param ReferralInterface $referral
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ReferralInterface $referral)
    {
        try {
            $this->resource->delete($referral);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__($e->getMessage()));
        }
        return true;
    }

    /**
     * Delete a referral by ID.
     *
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     */
    public function deleteById($id)
    {
        $referral = $this->getById($id);
        return $this->delete($referral);
    }

    /**
     * Get a list of referrals based on search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \WolfSellers\Referral\Api\Data\ReferralSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->referralCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Update a referral by ID.
     *
     * @param int $id
     * @param ReferralInterface $referral
     * @return ReferralInterface
     * @throws CouldNotSaveException
     */
    public function updateById(int $id, ReferralInterface $referral): ReferralInterface
    {
        try {
            $customer = null;
            $existingReferral = $this->referralFactory->create();
            $this->resource->load($existingReferral, $id);

            if (!$existingReferral->getId()) {
                throw new NoSuchEntityException(__('The referral with ID %1 was not found.', $id));
            }

            if ($referral->getEmail()) {
                $existingReferral->setEmail($referral->getEmail());
            }
            if ($referral->getCustomerId() !== null) {
                $existingReferral->setCustomerId($referral->getCustomerId());
                $customer = $this->customerRepository->getById($referral->getCustomerId());
            }
            if ($referral->getStatus()) {
                $existingReferral->setStatus($referral->getStatus());
            } else {
                $status = ReferralInterface::PENDING_STATUS;
                $status = $customer && $customer->getId() ? ReferralInterface::REGISTERED_STATUS : $status;
                $existingReferral->setStatus($status);
            }

            $this->resource->save($existingReferral);
            return $existingReferral;
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Error updating referral: %1', $e->getMessage()));
        }
    }
}
