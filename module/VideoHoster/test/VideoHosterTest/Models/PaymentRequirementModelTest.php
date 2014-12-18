<?php

namespace VideoHosterTest\Models;

use VideoHoster\Models\PaymentRequirementModel;

class PaymentRequirementModelTest extends ModelTestAbstract
{
    public function setUp()
    {
        $this->model = new PaymentRequirementModel();
        $this->testData = array(
            'paymentRequirementId' => 1,
            'name'  => "free",
        );
    }
} 