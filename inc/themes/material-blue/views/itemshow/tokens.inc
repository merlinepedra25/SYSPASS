<div id="box-popup">
    <h2 class="center"><?php echo $header; ?></H2>

    <form method="post" name="frmTokens" id="frmTokens" class="form-action"
          data-onsubmit="appMgmt/save"
          data-activetab="<?php echo isset($activeTab) ? $activeTab : ''; ?>"
          data-nextaction-id="<?php echo \SP\Core\ActionsInterface::ACTION_MGM_APITOKENS; ?>">
        <table class="popup-data">
            <tbody>
            <tr>
                <td class="descField"><?php echo _('Usuario'); ?></td>
                <td class="valField">
                    <select id="selUsers" name="users" class="select-box" required>
                        <option value=""><?php echo _('Seleccionar Usuario'); ?></option>
                        <?php foreach ($users as $user): ?>
                            <?php $selected = ($gotData && $user->id == $token->authtoken_userId) ? 'selected' : ''; ?>
                            <option value="<?php echo $user->id; ?>" <?php echo $selected; ?>><?php echo $user->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="descField"><?php echo _('Acción'); ?></td>
                <td class="valField">
                    <select id="selActions" name="actions"
                            class="select-box" required>
                        <option value=""><?php echo _('Seleccionar Acción'); ?></option>
                        <?php foreach ($actions as $id => $name): ?>
                            <?php $selected = ($gotData && $id == $token->authtoken_actionId) ? 'selected' : ''; ?>
                            <option value="<?php echo $id; ?>" <?php echo $selected; ?>><?php echo $name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <?php if (!$isView): ?>
                <tr>
                    <td class="descField"><?php echo _('Opciones'); ?></td>
                    <td class="valField">
                        <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="refreshtoken"
                               title="<?php echo _('Regenerar Autorización'); ?>">
                            <input type="checkbox" id="refreshtoken"
                                   class="mdl-switch__input mdl-color-text--indigo-400"
                                   name="refreshtoken"/>
                            <span class="mdl-switch__label"><?php echo _('Regenerar Autorización'); ?></span>
                        </label>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td class="descField"><?php echo _('Token'); ?></td>
                    <td class="valField"><?php echo $gotData ? $token->authtoken_token : ''; ?></td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <input type="hidden" name="itemId" value="<?php echo $gotData ? $token->authtoken_id : ''; ?>"/>
        <input type="hidden" name="actionId" value="<?php echo $actionId; ?>"/>
        <input type="hidden" name="sk" value="">
        <input type="hidden" name="isAjax" value="1">
    </form>
    <div class="action-in-box">
        <button
            class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored <?php echo $icons->getIconSave()->getClassButton(); ?>"
            form="frmTokens" title="<?php echo $icons->getIconSave()->getTitle(); ?>">
            <i class="material-icons"><?php echo $icons->getIconSave()->getIcon(); ?></i>
        </button>
    </div>
</div>
<script type="text/javascript">
    sysPassApp.sk.set("<?php echo $sk; ?>");
</script>