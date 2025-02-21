<?php

declare(strict_types=1);

/*
 * PaypalServerSdkLib
 *
 * This file was automatically generated by APIMATIC v3.0 ( https://www.apimatic.io ).
 */

namespace PaypalServerSdkLib\Models\Builders;

use Core\Utils\CoreHelper;
use PaypalServerSdkLib\Models\NetworkTransactionReference;
use PaypalServerSdkLib\Models\StoredPaymentSource;

/**
 * Builder for model StoredPaymentSource
 *
 * @see StoredPaymentSource
 */
class StoredPaymentSourceBuilder
{
    /**
     * @var StoredPaymentSource
     */
    private $instance;

    private function __construct(StoredPaymentSource $instance)
    {
        $this->instance = $instance;
    }

    /**
     * Initializes a new stored payment source Builder object.
     */
    public static function init(string $paymentInitiator, string $paymentType): self
    {
        return new self(new StoredPaymentSource($paymentInitiator, $paymentType));
    }

    /**
     * Sets usage field.
     */
    public function usage(?string $value): self
    {
        $this->instance->setUsage($value);
        return $this;
    }

    /**
     * Sets previous network transaction reference field.
     */
    public function previousNetworkTransactionReference(?NetworkTransactionReference $value): self
    {
        $this->instance->setPreviousNetworkTransactionReference($value);
        return $this;
    }

    /**
     * Initializes a new stored payment source object.
     */
    public function build(): StoredPaymentSource
    {
        return CoreHelper::clone($this->instance);
    }
}
