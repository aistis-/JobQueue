<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\JobQueue\Model\Manager;

use Aureja\JobQueue\Model\JobConfigurationInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 4/24/15 10:01 PM
 */
abstract class JobReportManager implements JobReportManagerInterface
{

    /**
     * {@inheritdoc}
     */
    public function create(JobConfigurationInterface $configuration)
    {
        $className = $this->getClass();
        $report = new $className;
        $report->setConfiguration($configuration);

        return $report;
    }
}
