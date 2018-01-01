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

namespace SP\Forms;

use SP\Core\Acl\ActionsInterface;
use SP\Core\Exceptions\ValidationException;
use SP\DataModel\GroupData;
use SP\Http\Request;

/**
 * Class UserGroupForm
 *
 * @package SP\Forms
 */
class UserGroupForm extends FormBase implements FormInterface
{
    /**
     * @var GroupData
     */
    protected $groupData;

    /**
     * Validar el formulario
     *
     * @param $action
     * @return bool
     * @throws \SP\Core\Exceptions\ValidationException
     */
    public function validate($action)
    {
        switch ($action) {
            case ActionsInterface::GROUP_CREATE:
            case ActionsInterface::GROUP_EDIT:
                $this->analyzeRequestData();
                $this->checkCommon();
                break;
        }

        return true;
    }

    /**
     * Analizar los datos de la petición HTTP
     *
     * @return void
     */
    protected function analyzeRequestData()
    {
        $this->groupData = new GroupData();
        $this->groupData->setUsergroupId($this->itemId);
        $this->groupData->setUsergroupName(Request::analyze('name'));
        $this->groupData->setUsergroupDescription(Request::analyze('description'));
        $this->groupData->setUsers(Request::analyze('users', 0));
    }

    /**
     * @throws ValidationException
     */
    protected function checkCommon()
    {
        if (!$this->groupData->getUsergroupName()) {
            throw new ValidationException(__u('Es necesario un nombre de grupo'));
        }
    }

    /**
     * @return GroupData
     */
    public function getItemData()
    {
        return $this->groupData;
    }
}