<?php

declare(strict_types=1);

/*
 * PaypalServerSdkLib
 *
 * This file was automatically generated by APIMATIC v3.0 ( https://www.apimatic.io ).
 */

namespace PaypalServerSdkLib\Models\Builders;

use Core\Utils\CoreHelper;
use PaypalServerSdkLib\Models\Address;
use PaypalServerSdkLib\Models\Name;
use PaypalServerSdkLib\Models\PhoneNumber;
use PaypalServerSdkLib\Models\VenmoWalletAttributesResponse;
use PaypalServerSdkLib\Models\VenmoWalletResponse;

/**
 * Builder for model VenmoWalletResponse
 *
 * @see VenmoWalletResponse
 */
class VenmoWalletResponseBuilder
{
    /**
     * @var VenmoWalletResponse
     */
    private $instance;

    private function __construct(VenmoWalletResponse $instance)
    {
        $this->instance = $instance;
    }

    /**
     * Initializes a new venmo wallet response Builder object.
     */
    public static function init(): self
    {
        return new self(new VenmoWalletResponse());
    }

    /**
     * Sets email address field.
     */
    public function emailAddress(?string $value): self
    {
        $this->instance->setEmailAddress($value);
        return $this;
    }

    /**
     * Sets account id field.
     */
    public function accountId(?string $value): self
    {
        $this->instance->setAccountId($value);
        return $this;
    }

    /**
     * Sets user name field.
     */
    public function userName(?string $value): self
    {
        $this->instance->setUserName($value);
        return $this;
    }

    /**
     * Sets name field.
     */
    public function name(?Name $value): self
    {
        $this->instance->setName($value);
        return $this;
    }

    /**
     * Sets phone number field.
     */
    public function phoneNumber(?PhoneNumber $value): self
    {
        $this->instance->setPhoneNumber($value);
        return $this;
    }

    /**
     * Sets address field.
     */
    public function address(?Address $value): self
    {
        $this->instance->setAddress($value);
        return $this;
    }

    /**
     * Sets attributes field.
     */
    public function attributes(?VenmoWalletAttributesResponse $value): self
    {
        $this->instance->setAttributes($value);
        return $this;
    }

    /**
     * Initializes a new venmo wallet response object.
     */
    public function build(): VenmoWalletResponse
    {
        return CoreHelper::clone($this->instance);
    }
}
