<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\JobQueue\Extension\Shell;

use Aureja\JobQueue\JobInterface;
use Aureja\JobQueue\JobState;
use Aureja\JobQueue\JobTrait;
use Aureja\JobQueue\Model\JobReportInterface;
use Aureja\JobQueue\Model\Manager\JobReportManagerInterface;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 4/16/15 10:37 PM
 */
class ShellJob implements JobInterface
{

    use JobTrait;

    /**
     * @var Process
     */
    private $process;

    /**
     * @var JobReportManagerInterface
     */
    private $reportManager;

    /**
     * Constructor.
     *
     * @param string $command
     * @param JobReportManagerInterface $reportManager
     */
    public function __construct($command, JobReportManagerInterface $reportManager)
    {
        $this->process = new Process($command);
        $this->process->setTimeout(null);
        $this->reportManager = $reportManager;
    }

    /**
     * {@inheritdoc}
     */
    public function run(JobReportInterface $report)
    {
        try {
            $this->process->start();
            $this->savePid($this->process->getPid(), $report);
            while ($this->process->isRunning()) {
                // waiting for process to finish
            }

            if ($this->process->isSuccessful()) {
                $report->setOutput(trim($this->process->getOutput()));

                return JobState::STATE_FINISHED;
            }

            $report->setErrorOutput(trim($this->process->getErrorOutput()));
        } catch (LogicException $e) {
            $report->setErrorOutput($e->getMessage());
        } catch (RuntimeException $e) {
            $report->setErrorOutput($e->getMessage());
        }


        return JobState::STATE_FAILED;
    }
}
