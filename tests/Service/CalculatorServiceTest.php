<?php

namespace App\Tests\Service;

use App\Service\v1\CalculatorService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CalculatorServiceTest extends TestCase
{

    // Unit Tests

    public function testCalculatePlus(): void
    {
        $service = (new CalculatorService($this->mockRequest(['calculation'=>'2+2'])));

        $output = $service->calculate();
        $this->assertSame('4', $output);
    }

    public function testCalculateMinus(): void
    {
        $service = (new CalculatorService($this->mockRequest(['calculation'=>'2-2'])));

        $output = $service->calculate();
        $this->assertSame('0', $output);
    }

    public function testCalculateDivision(): void
    {
        $service = (new CalculatorService($this->mockRequest(['calculation'=>'2/2'])));

        $output = $service->calculate();
        $this->assertSame('1', $output);
    }

    public function testCalculateMultiplication(): void
    {
        $service = (new CalculatorService($this->mockRequest(['calculation'=>'2*2'])));

        $output = $service->calculate();
        $this->assertSame('4', $output);
    }

    public function testCalculateInvalid(): void
    {
        $service = (new CalculatorService($this->mockRequest(['calculation'=>'abc'])));

        $output = $service->calculate();
        $this->assertSame($service::ERROR_NAN, $output);
    }

    public function testCalculateInvalidWithNumbers(): void
    {
        $service = (new CalculatorService($this->mockRequest(['calculation'=>'abc1+2efg'])));

        $output = $service->calculate();
        $this->assertSame($service::ERROR_NAN, $output);
    }

    public function testCalculateWeirdSymbols(): void
    {
        $service = (new CalculatorService($this->mockRequest(['calculation'=>'^&*()$£"%2*2'])));

        $output = $service->calculate();
        $this->assertSame($service::ERROR_NAN, $output);
    }

    public function testCalculateEmpty(): void
    {
        $service = (new CalculatorService($this->mockRequest(['calculation'=>''])));

        $output = $service->calculate();
        $this->assertSame('0', $output);
    }


    public function testCalculateLeftoverOperators(): void
    {
        $service = (new CalculatorService($this->mockRequest(['calculation'=>'2+2+'])));

        $output = $service->calculate();
        $this->assertSame('4', $output);
    }

    private function mockRequest(array $data = []): Request
    {
        return Request::create('/v1/calculator', 'POST', $data);

    }
}
