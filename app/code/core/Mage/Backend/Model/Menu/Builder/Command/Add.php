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
 * @category    Mage
 * @package     Mage_Backend
 * @copyright   Copyright (c) 2012 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Builder command to add menu items
 */
class Mage_Backend_Model_Menu_Builder_Command_Add extends Mage_Backend_Model_Menu_Builder_CommandAbstract
{
    /**
     * List of params that command requires for execution
     *
     * @var array
     */
    protected $_requiredParams = array(
        "id",
        "title",
        "module",
        "resource"
    );

    /**
     * Add command as last in the list of callbacks
     *
     * @param Mage_Backend_Model_Menu_Builder_CommandAbstract $command
     * @return Mage_Backend_Model_Menu_Builder_CommandAbstract
     * @throws InvalidArgumentException
     */
    public function chain(Mage_Backend_Model_Menu_Builder_CommandAbstract $command)
    {
        if ($command instanceof Mage_Backend_Model_Menu_Builder_Command_Add) {
            throw new InvalidArgumentException("Two 'add' commands cannot have equal id (" . $command->getId() . ")");
        }
        return parent::chain($command);
    }

    /**
     * Add missing data to item
     *
     * @param array $itemParams
     * @return array
     */
    protected function _execute(array $itemParams)
    {
        foreach ($this->_data as $key => $value) {
            $itemParams[$key] = isset($itemParams[$key]) ? $itemParams[$key] : $value;
        }
        return $itemParams;
    }
}
