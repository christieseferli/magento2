<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento
 * @subpackage  integration_tests
 * @copyright   Copyright (c) 2012 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Implementation of the @magentoDbIsolation DocBlock annotation
 */
class Magento_Test_Annotation_DbIsolation
{
    /**
     * @var bool
     */
    protected $_isIsolationActive = false;

    /**
     * Handler for 'startTestTransactionRequest' event
     *
     * @param PHPUnit_Framework_TestCase $test
     * @param Magento_Test_Event_Param_Transaction $param
     */
    public function startTestTransactionRequest(
        PHPUnit_Framework_TestCase $test, Magento_Test_Event_Param_Transaction $param
    ) {
        $methodIsolation = $this->_getIsolation('method', $test);
        if ($this->_isIsolationActive) {
            if ($methodIsolation === false) {
                $param->requestTransactionRollback();
            }
        } else if ($methodIsolation || ($methodIsolation === null && $this->_getIsolation('class', $test))) {
            $param->requestTransactionStart();
        }
    }

    /**
     * Handler for 'endTestTransactionRequest' event
     *
     * @param PHPUnit_Framework_TestCase $test
     * @param Magento_Test_Event_Param_Transaction $param
     */
    public function endTestTransactionRequest(
        PHPUnit_Framework_TestCase $test, Magento_Test_Event_Param_Transaction $param
    ) {
        if ($this->_isIsolationActive && $this->_getIsolation('method', $test)) {
            $param->requestTransactionRollback();
        }
    }

    /**
     * Handler for 'startTransaction' event
     *
     * @param PHPUnit_Framework_TestCase $test
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function startTransaction(PHPUnit_Framework_TestCase $test)
    {
        $this->_isIsolationActive = true;
    }

    /**
     * Handler for 'rollbackTransaction' event
     */
    public function rollbackTransaction()
    {
        $this->_isIsolationActive = false;
    }

    /**
     * Retrieve database isolation annotation value for the current scope.
     * Possible results:
     *   NULL  - annotation is not defined
     *   TRUE  - annotation is defined as 'enabled'
     *   FALSE - annotation is defined as 'disabled'
     *
     * @param string $scope 'class' or 'method'
     * @param PHPUnit_Framework_TestCase $test
     * @return bool|null Returns NULL, if isolation is not defined for the current scope
     * @throws Magento_Exception
     */
    protected function _getIsolation($scope, PHPUnit_Framework_TestCase $test)
    {
        $annotations = $test->getAnnotations();
        if (isset($annotations[$scope]['magentoDbIsolation'])) {
            $isolation = $annotations[$scope]['magentoDbIsolation'];
            if ($isolation !== array('enabled') && $isolation !== array('disabled')) {
                throw new Magento_Exception(
                    'Invalid "@magentoDbIsolation" annotation, can be "enabled" or "disabled" only.'
                );
            }
            return ($isolation === array('enabled'));
        }
        return null;
    }
}
