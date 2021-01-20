'use strict';

var UpFit = window.UpFit || {};

function PermissionsModule()
{
    const selectors = {
        LIST_CONTAINER: '.js-permission-container',
        PERMISSION_TYPE: '.js-permission-type',
        PERMISSION_CONTROL: '.js-permission-control',
        PERMISSION_CONTROL_CONTAINER: '.js-permission-control-container',
        PERMISSION_CONTROL_TYPE: '.js-permission-control_type',
        INPUTS: 'input',
        SELECT_ALL: '.js-permission-select-all'
    };

    /**
     * @param {jQuery} $container
     * @constructor
     */
    function PermissionList($container)
    {
        this.container = $($container);

        this.initElement();
        this.initEvents();
    }

    PermissionList.prototype.initElement = function ()
    {
        var self = this;

        this.elements = {
            inputs: this.container.find(selectors.INPUTS),
            selectAll: this.container.find(selectors.SELECT_ALL),
            accessControls: this.container.find(selectors.PERMISSION_CONTROL),
            permissionTypes: this.container.find(selectors.PERMISSION_TYPE),
            permissionInputs: this.container.find(selectors.PERMISSION_CONTROL_TYPE)
        };

        this.container.find('.js-permission-control[data-control-id]').each(function() {
            var input = $(this),
                row = input.closest(selectors.PERMISSION_CONTROL_CONTAINER),
                inputs = row.find(selectors.PERMISSION_CONTROL_TYPE);

            self.refreshGroupPermissions(input, inputs);
        });

        this.container.find('.js-permission-type[data-type]').each(function () {
            var input = $(this),
                inputs = self.container.find('.js-permission-control_type[value="'+ input.data('type') +'"]');

            self.refreshGroupPermissions(input, inputs);
        });
    };

    PermissionList.prototype.refreshCheckedAll = function() {
        var checkedAll = true,
           inputController = this.container.find(selectors.PERMISSION_CONTROL_TYPE),
           selectAll = this.container.find(selectors.SELECT_ALL);

        inputController.each(function() {
            if (!$(this).prop('checked')) {
                checkedAll = false;
            }
        });
        selectAll.prop('checked', checkedAll);
    };

    PermissionList.prototype.refreshGroupPermissions = function(selectAllInput, relatedInputs)
    {
        var checkedAll = true;
        relatedInputs.each(function () {
            if (!$(this).prop('checked')) {
                checkedAll = false;
            }
        });

        selectAllInput.prop('checked', checkedAll)
    };

    PermissionList.prototype.initEvents = function ()
    {
        var self = this,
            elements = this.elements;

        self.refreshCheckedAll();

        elements.selectAll.on('change', function () {
            elements.inputs.prop('checked', $(this).prop('checked'));
        });

        elements.permissionInputs.on('change', function () {
            var inputVal = $(this).val(),
                tableRow =  $(this).closest('tr'),
                control = tableRow.find(selectors.PERMISSION_CONTROL),
                row = tableRow.find(selectors.PERMISSION_CONTROL_TYPE),
                permission = self.container.find(selectors.PERMISSION_TYPE + '[data-type=' + inputVal + ']'),
                permissionType = permission.data('type'),
                column = self.container.find(selectors.PERMISSION_CONTROL_TYPE + '[value=' + permissionType + ']');
            self.refreshCheckedAll();
            self.refreshGroupPermissions(permission, column);
            self.refreshGroupPermissions(control, row);
        });

        elements.accessControls.on('change', function() {
            var container = $(this).closest(selectors.PERMISSION_CONTROL_CONTAINER);
            container.find(selectors.PERMISSION_CONTROL_TYPE).prop('checked', $(this).prop('checked'));
            self.refreshCheckedAll();
        });

        elements.permissionTypes.on('change', function () {
            var input = $(this),
                type = input.data('type');
            self.container.find(selectors.PERMISSION_CONTROL_TYPE + '[value=' + type + ']')
                .prop('checked', input.prop('checked'));
            self.refreshCheckedAll();
        });
    };

    function initPermissionLists()
    {
        $(selectors.LIST_CONTAINER).each(function (i, container) {
            new PermissionList(container);
        });
    }

    return {
        PermissionList: PermissionList,
        initPermissionLists: initPermissionLists
    }
}

$.extend(UpFit, PermissionsModule());

$(function () {
    UpFit.initPermissionLists();
});
