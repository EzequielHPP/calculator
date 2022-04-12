<?php

namespace App\Service;

use App\Helpers\LogHelper;
use Symfony\Component\HttpFoundation\Request;

final class CalculatorService
{
    public const ERROR_NAN = 'NAN';
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Given a request, get the calculation property and execute the calculation
     *
     * @return string
     */
    public function calculate(): string
    {
        $result = self::ERROR_NAN;

        try {
            if ($this->isValidRequest()) {
                $value = $this->getSubmittedData();
                $value['calculation'] = $this->removeEndingOperators($value['calculation']);
                $result = $this->executeCalculation($value['calculation']) + 0; // + 0 removes empty 0's from decimal places
            }
        } catch (\Throwable $exception) {
            LogHelper::error($exception);
        }

        return (string)$result;
    }

    /**
     * Checks if the request has the necessary field and has valid data
     *
     * @return bool
     */
    private function isValidRequest(): bool
    {
        $rawData = $this->getSubmittedData();
        if (array_key_exists('calculation', $rawData)) {
            $cleanVersion = $this->cleanInput($rawData['calculation']);
            return $cleanVersion === $rawData['calculation'];
        }
        return false;
    }

    /**
     * Remove any unwanted characters from the math equation
     *
     * @param string $input
     * @return string
     */
    private function cleanInput(string $input): string
    {
        return preg_replace('/[^0-9\+\-\/\*.]/', '', $input);
    }

    /**
     * Remove leftover operators from end of string
     *
     * @param string $input
     * @return string
     */
    private function removeEndingOperators(string $input): string
    {
        return preg_replace('/[\+\-\/\*.]$/', '', $input);
    }

    /**
     * Try both ways of getting data from the Request
     *
     * @return array
     */
    private function getSubmittedData(): array
    {
        $rawData = $this->request->request->all();
        if (empty($rawData) && !empty($this->request->getContent())) {
            $rawData = json_decode($this->request->getContent(), true);
        }

        return $rawData;
    }

    /**
     * Given a mathematical string, run it using eval
     *
     * @param string $calculation
     * @return float
     */
    private function executeCalculation(string $calculation): float
    {
        $cleanedCalculation = $this->cleanInput($calculation);
        return empty($cleanedCalculation) ? 0 : eval('return ' . $cleanedCalculation . ';');
    }
}
