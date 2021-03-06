<?php
/*
 * This file is part of the CSBill package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CSBill\CoreBundle\Twig\Extension;

use Twig_Extension;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InactiveScopeException;

class GlobalExtension extends Twig_Extension
{
    /**
     * @var ContainerInterface $container
     */
    protected $container;

    /**
     * Sets the container
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Get global twig variables
     *
     * @return array
     */
    public function getGlobals()
    {
        return array(
                    'query'            => $this->getQuery(),
                    'currency'         => $this->container->get('currency'),
                    'settings'         => $this->container->get('settings')->getSettings(),
                    'invoice_manager'  => $this->container->get('invoice.manager')
            );
    }

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFilters()
     */
    public function getFilters()
    {
        return array(new \Twig_SimpleFilter('percentage', array($this, 'formatPercentage')),
                    new \Twig_SimpleFilter('currency', array($this, 'formatCurrency')));
    }

    /**
     * @param  int|float $amount
     * @return string
     */
    public function formatCurrency($amount)
    {
        return $this->container->get('currency')->format($amount);
    }

    /**
     * @param  int|float $amount
     * @param  int       $percentage
     * @return float|int
     */
    public function formatPercentage($amount, $percentage = 0)
    {
        if ($percentage > 0) {
            return ($amount * $percentage) / 100;
        }

        return 0;
    }

    /**
     * Get the url query
     *
     * @throws InactiveScopeException
     * @return array
     */
    protected function getQuery()
    {
        try {
            $request = $this->container->get('request');

            $params = array_merge($request->query->all(), $request->attributes->all());

            foreach ($params as $key => $param) {
                if (substr($key, 0, 1) == '_') {
                    unset($params[$key]);
                }
            }

            return $params;
        } catch (InactiveScopeException $e) {
            return array();
        }
    }

    /**
     * {inhertitDoc}
     */
    public function getName()
    {
        return 'csbill_core.twig.globals';
    }
 }
