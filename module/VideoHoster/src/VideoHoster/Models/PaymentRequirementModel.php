<?php

namespace VideoHoster\Models;

class PaymentRequirementModel
{
    public $paymentRequirementId;
    public $name;

    public function exchangeArray($data)
    {
        $this->paymentRequirementId = (isset($data['paymentRequirementId'])) ? $data['paymentRequirementId'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
} 