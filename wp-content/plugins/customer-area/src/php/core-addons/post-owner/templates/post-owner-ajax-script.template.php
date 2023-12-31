<?php /**
 * Template version: 3.1.0
 * Template zone: admin|frontend
 *
 * -= 3.1.0 =-
 * - Prevent Select2 assignment boxes to fail while initializing if the theme or a plugin is already loading Select2
 *
 * -= 3.0.0 =-
 * - Initial version
 */ ?>

<?php /** @var string $field_id */ ?>
<?php /** @var string $nonce */ ?>
<?php /** @var string $action */ ?>
<?php /** @var array $extra_data */ ?>

<script type="text/javascript">
    <!--
    (function ($)
    {
        "use strict";
        $(document).ready(function ()
        {
            $("#<?php echo esc_attr($field_id); ?>").cuarSelect2({
                width     : '40%',
                allowClear: true,
                placeholder: '',
                ajax      : {
                    url           : cuar.ajaxUrl,
                    dataType      : 'json',
                    data          : function (params)
                    {
                        return {
                            search: params.term,
                            nonce : '<?php echo $nonce ?>',
                            action: '<?php echo $action ?>',
                            <?php foreach ($extra_data as $key => $value): ?>
                            <?php   echo $key . ': "' . $value . '",'; ?>
                            <?php endforeach; ?>
                            page  : params.page || 1
                        };
                    },
                    processResults: function (data)
                    {
                        if (!data.success) {
                            alert(data.data);
                            return {results: []};
                        }

                        return {
                            results   : data.data.results,
                            pagination: {
                                more: data.data.more
                            }
                        };
                    }
                }
            });
        });
    })(jQuery);
    //-->
</script>