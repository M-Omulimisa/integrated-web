<?php
namespace App\Services\Payments;

class PaymentServiceFactory
{
    public function getService($serviceName)
    {
        $services = config("payments.services", []);

        if (isset($services[$serviceName]) && isset($services[$serviceName]["class"]) && class_exists($services[$serviceName]["class"])) {
            return new $services[$serviceName]["class"]();
        } else {
            return null;
        }
    }

}
