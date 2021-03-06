<?php

namespace Dtc\QueueBundle\Controller;

use Dtc\QueueBundle\Exception\UnsupportedException;
use Symfony\Component\HttpFoundation\Response;

trait ControllerTrait
{
    protected function render($template, $params)
    {
        if ($this->container->has('templating')) {
            return new Response($this->container->get('templating')->render($template, $params));
        } elseif ($this->container->has('twig')) {
            return new Response($this->container->get('twig')->render($template, $params));
        }
        throw new \Exception('Need Twig Bundle or Templating component installed');
    }

    protected function validateJobTimingManager()
    {
        if ($this->container->hasParameter('dtc_queue.manager.job_timing')) {
            $this->validateManagerType('dtc_queue.manager.job_timing');
        } elseif ($this->container->hasParameter('dtc_queue.manager.job_timing')) {
            $this->validateManagerType('dtc_queue.manager.run');
        } else {
            $this->validateManagerType('dtc_queue.manager.job');
        }
    }

    protected function validateRunManager()
    {
        if ($this->container->hasParameter('dtc_queue.manager.job_timing')) {
            $this->validateManagerType('dtc_queue.manager.run');
        } else {
            $this->validateManagerType('dtc_queue.manager.job');
        }
    }

    /**
     * @param string $type
     */
    protected function validateManagerType($type)
    {
        $managerType = $this->container->getParameter($type);
        if ('mongodb' !== $managerType && 'orm' != $managerType && 'odm' != $managerType) {
            throw new UnsupportedException("Unsupported manager type: $managerType");
        }
    }

    protected function addCssJs(array &$params)
    {
        $params['css'] = $this->container->getParameter('dtc_grid.theme.css');
        $params['js'] = $this->container->getParameter('dtc_grid.theme.js');
        $jQuery = $this->container->getParameter('dtc_grid.jquery');
        array_unshift($params['js'], $jQuery['url']);
        $params['chartjs'] = $this->container->getParameter('dtc_queue.admin.chartjs');
    }
}
