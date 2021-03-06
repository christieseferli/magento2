<?php
/**
 * Router route factory.
 *
 * @copyright Copyright (c) 2012 X.commerce, Inc. (http://www.magentocommerce.com)
 */
class Magento_Controller_Router_Route_Factory
{
    /**
     * @var Magento_ObjectManager
     */
    protected $_objectManager;

    /**
     * @param Magento_ObjectManager $objectManager
     */
    public function __construct(Magento_ObjectManager $objectManager)
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * Create route instance.
     *
     * @param $routeClass
     * @param string $route Map used to match with later submitted URL path
     * @param array $defaults Defaults for map variables with keys as variable names
     * @param array $reqs Regular expression requirements for variables (keys as variable names)
     * @param mixed $locale
     * @return Zend_Controller_Router_Route_Interface
     * @throws LogicException If specified route class does not implement proper interface.
     */
    public function createRoute(
        $routeClass,
        $route,
        $defaults = array(),
        $reqs = array(),
        $locale = null
    ) {
        $route = $this->_objectManager->create(
            $routeClass,
            array(
                'route' => $route,
                'defaults' => $defaults,
                'regs' => $reqs,
                'locale' => $locale
            ),
            false
        );
        if (!$route instanceof Zend_Controller_Router_Route_Interface) {
            throw new LogicException('Route must implement "Zend_Controller_Router_Route_Interface".');
        }
        return $route;
    }
}
