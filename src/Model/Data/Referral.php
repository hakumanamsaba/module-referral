<?php
declare(strict_types=1);

namespace WolfSellers\Referral\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use WolfSellers\Referral\Api\Data\ReferralInterface;

class Referral extends AbstractExtensibleModel implements ReferralInterface
{
    protected function _construct()
    {
        $this->_init(\WolfSellers\Referral\Model\ResourceModel\Referral::class);
    }

    public function getId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    public function getFirstName()
    {
        return $this->getData(self::FIRST_NAME);
    }

    public function setFirstName($firstName)
    {
        return $this->setData(self::FIRST_NAME, $firstName);
    }

    public function getLastName()
    {
        return $this->getData(self::LAST_NAME);
    }

    public function setLastName($lastName)
    {
        return $this->setData(self::LAST_NAME, $lastName);
    }

    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    public function getPhone()
    {
        return $this->getData(self::PHONE);
    }

    public function setPhone($phone)
    {
        return $this->setData(self::PHONE, $phone);
    }

    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}
