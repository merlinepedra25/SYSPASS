<?php
/**
 * sysPass
 *
 * @author    nuxsmin
 * @link      http://syspass.org
 * @copyright 2012-2017, Rubén Domínguez nuxsmin@$syspass.org
 *
 * This file is part of sysPass.
 *
 * sysPass is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * sysPass is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 *  along with sysPass.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace SP\Modules\Web\Controllers\Traits;

use Defuse\Crypto\Exception\CryptoException;
use SP\Config\ConfigData;
use SP\Core\Exceptions\SPException;
use SP\DataModel\CustomFieldData;
use SP\DataModel\ItemSearchData;
use SP\Http\Request;
use SP\Services\CustomField\CustomFieldService;

/**
 * Trait ItemTrait
 *
 * @package SP\Modules\Web\Controllers\Traits
 */
trait ItemTrait
{
    /**
     * Obtener la lista de campos personalizados y sus valores
     *
     * @param $moduleId
     * @param $itemId
     * @return array
     */
    protected function getCustomFieldsForItem($moduleId, $itemId)
    {
        $customFieldService = new CustomFieldService();
        $customFields = [];

        $customFieldBase = new \stdClass();

        foreach ($customFieldService->getForModuleById($moduleId, $itemId) as $item) {
            try {
                $customField = clone $customFieldBase;
                $customField->required = (bool)$item->required;
                $customField->showInList = (bool)$item->showInList;
                $customField->help = $item->help;
                $customField->definitionId = (int)$item->definitionId;
                $customField->definitionName = $item->definitionName;
                $customField->typeId = (int)$item->typeId;
                $customField->typeName = $item->typeName;
                $customField->moduleId = (int)$item->moduleId;
                $customField->formId = CustomFieldService::getFormIdForName($item->definitionName);
                $customField->value = $item->data !== null ? CustomFieldService::unencryptData($item->data) : '';

                $customFields[] = $customField;
            } catch (CryptoException $e) {
                debugLog($e->getMessage());
            }
        }

        return $customFields;
    }

    /**
     * Añadir los campos personalizados del elemento
     *
     * @param int       $moduleId
     * @param int|int[] $itemId
     * @throws SPException
     */
    protected function addCustomFieldsForItem($moduleId, $itemId)
    {
        $customFields = Request::analyze('customfield');

        if (is_array($customFields)) {
            $customFieldData = new CustomFieldData();
            $customFieldData->setId($itemId);
            $customFieldData->setModuleId($moduleId);

            $customFieldService = new CustomFieldService();
            try {
                foreach ($customFields as $id => $value) {
                    $customFieldData->setDefinitionId($id);
                    $customFieldData->setData($value);

                    $customFieldService->create($customFieldData);
                }
            } catch (CryptoException $e) {
                throw new SPException(SPException::SP_ERROR, __u('Error interno'));
            }
        }
    }

    /**
     * Eliminar los campos personalizados del elemento
     *
     * @param int       $moduleId
     * @param int|int[] $itemId
     * @throws SPException
     */
    protected function deleteCustomFieldsForItem($moduleId, $itemId)
    {
        $customFieldService = new CustomFieldService();
        $customFieldService->deleteCustomFieldData($itemId, $moduleId);
    }

    /**
     * Actualizar los campos personalizados del elemento
     *
     * @param int       $moduleId
     * @param int|int[] $itemId
     * @throws SPException
     */
    protected function updateCustomFieldsForItem($moduleId, $itemId)
    {
        $customFields = Request::analyze('customfield');

        if (is_array($customFields)) {
            $customFieldService = new CustomFieldService();
            $customFieldData = new CustomFieldData();
            $customFieldData->setId($itemId);
            $customFieldData->setModuleId($moduleId);

            try {
                foreach ($customFields as $id => $value) {
                    $customFieldData->setDefinitionId($id);
                    $customFieldData->setData($value);

                    $customFieldService->update($customFieldData);
                }
            } catch (CryptoException $e) {
                throw new SPException(SPException::SP_ERROR, __u('Error interno'));
            }
        }
    }

    /**
     * Returns search data object for the current request
     *
     * @param ConfigData $configData
     * @return ItemSearchData
     */
    protected function getSearchData(ConfigData $configData)
    {
        $itemSearchData = new ItemSearchData();
        $itemSearchData->setLimitCount($configData->getAccountCount());
        $itemSearchData->setSeachString(Request::analyze('search'));
        $itemSearchData->setLimitStart(Request::analyze('start', 0));
        $itemSearchData->setLimitCount(Request::analyze('count', $configData->getAccountCount()));

        return $itemSearchData;
    }
}