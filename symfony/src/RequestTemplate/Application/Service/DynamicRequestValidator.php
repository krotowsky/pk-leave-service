<?php

namespace App\RequestTemplate\Application\Service;

use App\RequestTemplate\Application\DTO\DynamicRequestDTO;
use App\RequestTemplate\Domain\Repository\RequestTemplateRepository;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class DynamicRequestValidator
{
    public function __construct(
        private readonly RequestTemplateRepository $templateRepo
    ) {}

    public function validate(DynamicRequestDTO $dto): ConstraintViolationListInterface
    {
        $violations = new ConstraintViolationList();

        $template = $this->templateRepo->find($dto->templateId);

        if (!$template) {
            $violations->add(new ConstraintViolation(
                'Unknown template ID.',
                null,
                [],
                $dto,
                'templateId',
                $dto->templateId
            ));
            return $violations;
        }

        $rules = $template->getValidationRules();

        foreach ($rules as $fieldName => $fieldRules) {
            $value = $dto->fields[$fieldName] ?? null;
            $originalValue = $value;
            $constraints = [];

            $type = $fieldRules['type'] ?? null;

            switch ($type) {
                case 'string':
                    $constraints[] = new Assert\NotBlank();
                    $constraints[] = new Assert\Type('string');
                    if (isset($fieldRules['minLength'])) {
                        $constraints[] = new Assert\Length(['min' => $fieldRules['minLength']]);
                    }
                    break;

                case 'date':
                    // First validate the date format on string
                    if (is_string($value)) {
                        $validator = Validation::createValidator();
                        $dateViolations = $validator->validate($value, [new Assert\Date()]);
                        foreach ($dateViolations as $v) {
                            $violations->add(new ConstraintViolation(
                                $v->getMessage(),
                                $v->getMessageTemplate(),
                                $v->getParameters(),
                                $dto,
                                "fields[$fieldName]",
                                $value,
                                $v->getPlural(),
                                $v->getCode(),
                                $v->getConstraint(),
                                $v->getCause()
                            ));
                        }

                        // If date format is valid, try to parse
                        try {
                            $value = new \DateTimeImmutable($value);
                        } catch (\Exception $e) {
                            break; // parsing failed, already reported above
                        }
                    }

                    if ($value instanceof \DateTimeInterface && isset($fieldRules['minDaysFromNow'])) {
                        $minDate = (new \DateTimeImmutable())->modify("+{$fieldRules['minDaysFromNow']} days");
                        $constraints[] = new Assert\GreaterThan([
                            'value' => $minDate,
                            'message' => "Must be at least {$fieldRules['minDaysFromNow']} days in the future."
                        ]);
                    }
                    break;

                case 'int':
                    $constraints[] = new Assert\NotBlank();
                    $constraints[] = new Assert\Type('integer');
                    if (isset($fieldRules['min'])) {
                        $constraints[] = new Assert\GreaterThanOrEqual($fieldRules['min']);
                    }
                    if (isset($fieldRules['max'])) {
                        $constraints[] = new Assert\LessThanOrEqual($fieldRules['max']);
                    }
                    break;

                default:
                    $constraints[] = new Assert\NotBlank([
                        'message' => "Unsupported type: {$type}"
                    ]);
                    break;
            }

            // Validate field against constraints
            $validator = Validation::createValidator();
            $fieldViolations = $validator->validate($value, $constraints);

            foreach ($fieldViolations as $v) {
                $violations->add(new ConstraintViolation(
                    $v->getMessage(),
                    $v->getMessageTemplate(),
                    $v->getParameters(),
                    $dto,
                    "fields[$fieldName]",
                    $originalValue,
                    $v->getPlural(),
                    $v->getCode(),
                    $v->getConstraint(),
                    $v->getCause()
                ));
            }
        }

        return $violations;
    }
}
