<?php
declare(strict_types=1);

namespace WolfSellers\Referral\Api\Data;

/**
 * Interface ReferralInterface
 *
 * Interface for managing customer referrals in the Referral module.
 *
 * @category  WolfSellers
 * @package   WolfSellers_Referral
 * @author    Abraham Ruiz <abrahamruiz.hakum@gmail.com>
 */
interface ReferralInterface
{
    const ENTITY_ID   = 'entity_id';
    const CUSTOMER_ID = 'customer_id';
    const FIRST_NAME  = 'first_name';
    const LAST_NAME   = 'last_name';
    const EMAIL       = 'email';
    const PHONE       = 'phone';
    const STATUS      = 'status';
    const CREATED_AT  = 'created_at';
    const PENDING_STATUS = 'pending';
    const REGISTERED_STATUS = 'registered';

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return int|null
     */
    public function getCustomerId();

    /**
     * @param int|null $customerId
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * @return string|null
     */
    public function getFirstName();

    /**
     * @param string|null $firstName
     * @return $this
     */
    public function setFirstName($firstName);

    /**
     * @return string|null
     */
    public function getLastName();

    /**
     * @param string|null $lastName
     * @return $this
     */
    public function setLastName($lastName);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email);

    /**
     * @return string|null
     */
    public function getPhone();

    /**
     * @param string|null $phone
     * @return $this
     */
    public function setPhone($phone);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);
}
