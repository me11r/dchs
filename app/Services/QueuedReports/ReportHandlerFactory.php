<?php


namespace App\Services\QueuedReports;

use App\Enums\ReportType;
use App\Services\QueuedReports\Exceptions\ReportHandlerNotFound;
use App\Services\QueuedReports\ReportHandlers\AnalyticsSpiasrStrategy;
use App\Services\QueuedReports\ReportHandlers\ReportHandlerStrategyInterface;
use Illuminate\Contracts\Container\Container;

class ReportHandlerFactory
{

    /**
     * @var array
     */
    private $classMap = [
        ReportType::ANALYTICS_SPIASR => AnalyticsSpiasrStrategy::class
    ];

    /**
     * @var Container
     */
    protected $container;

    /**
     * SiteMapGeneratorFactory constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $reportType
     * @return ReportHandlerStrategyInterface
     * @throws ReportHandlerNotFound
     */
    public function create(string $reportType): ReportHandlerStrategyInterface
    {
        if (!isset($this->classMap[$reportType])) {
            throw new ReportHandlerNotFound('Report handler not found for the ' . $reportType);
        }

        return $this->container->make($this->classMap[$reportType]);
    }
}