<?php

namespace SprykerTest\Zed\Install\Business\Style;

use Codeception\Test\Unit;
use Spryker\Zed\Install\Business\Stage\StageInterface;
use Spryker\Zed\Install\Business\Style\SprykerStyle;
use Spryker\Zed\Install\Business\Timer\TimerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SprykerStyleTest extends Unit
{
    public function testStartInstall()
    {
        $inputInterfaceMock = $this->getMockBuilder(InputInterface::class)->getMock();
        $outputInterfaceMock = $this->getMockBuilder(OutputInterface::class)->getMock();
        $timerInterfaceMpck = $this->getMockBuilder(TimerInterface::class)->getMock();
        $startInterfaceMock = $this->getMockBuilder(StageInterface::class)->getMock();
        $outputInterfaceMock->expects($this->once())->method('writeln');
        $sprykerStyle = new SprykerStyle($inputInterfaceMock, $outputInterfaceMock, $timerInterfaceMpck);

        $sprykerStyle->startInstall($startInterfaceMock);
    }
}
