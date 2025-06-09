<?php

namespace App\LeaveRequest\Application\Service;

use App\LeaveRequest\Domain\Model\LeaveRequestValidationSettings;
use Doctrine\ORM\EntityManagerInterface;

class LeaveValidationRuleProvider
{
    private const DEFAULT_REASON_LENGTH = 3;
    private const DEFAULT_START_DELAY_DAYS = 1;

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function getRules(): LeaveRequestValidationSettings
    {
        // You can later customize this per tenant if needed
        $settings = $this->em->getRepository(LeaveRequestValidationSettings::class)->find('global');

        if (!$settings) {
            // Fallback object if not in DB yet
            $settings = new LeaveRequestValidationSettings();
            $settings->setMinReasonLength(self::DEFAULT_REASON_LENGTH);
            $settings->setMinStartDelayDays(self::DEFAULT_START_DELAY_DAYS);
        }

        return $settings;
    }
}
