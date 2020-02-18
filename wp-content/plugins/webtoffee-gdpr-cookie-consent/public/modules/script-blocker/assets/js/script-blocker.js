jQuery(document).ready(function ($) {


    // Toggle gateway on/off.
    $('.cli_script_items').on('click', '.cli-script-items-toggle-enabled', function () {

        var $link = $(this), $row = $link.closest('tr'), $toggle = $link.find('.cli-input-toggle');
        var $script_id = $row.attr('data-script_id');

        var data = {
            action: 'cli_toggle_script_enabled',
            security: cli_script_admin.nonces.cli_toggle_script,
            cliscript_id: $script_id
        };

        $toggle.addClass('cli-input-toggle--loading');
        $.ajax({
            url: cli_script_admin.ajax_url,
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function (response) {
                console.log(response);
                //return;
                if (true === response.success && true === response.data) {
                    $toggle.removeClass('cli-input-toggle--enabled, cli-input-toggle--disabled');
                    $toggle.addClass('cli-input-toggle--enabled');
                    $toggle.removeClass('cli-input-toggle--loading');
                } else if (false === response.success || false === response.data) {
                    $toggle.removeClass('cli-input-toggle--enabled, cli-input-toggle--disabled');
                    $toggle.addClass('cli-input-toggle--disabled');
                    $toggle.removeClass('cli-input-toggle--loading');
                }
            }
        });

        return false;
    });


    $("[name='cliscript_category']").on('change', function (e) {

   
        var $valueSelected = this.value;

        var $link = $(this), $row = $link.closest('tr');

        $script_id = $row.attr('data-script_id');

        var data = {
            action: 'cli_change_script_category',
            security: cli_script_admin.nonces.cli_change_script_category,
            cliscript_id: $script_id,
            category: $valueSelected
        };

        $.ajax({
            url: cli_script_admin.ajax_url,
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function (response) {

                if (true === response.success) {
                    location.reload();
                } else if (false === response.success) {
                    console.log(response);
                }
            }
        });


    });

});