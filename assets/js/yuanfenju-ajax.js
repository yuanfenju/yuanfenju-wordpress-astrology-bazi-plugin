jQuery(document).ready(function($) {
    // 拦截所有带有 yfj-ajax-form 类的表单提交
    $('.yfj-ajax-form').on('submit', function(e) {
        e.preventDefault();

        let $form = $(this);
        let $container = $form.closest('.yfj-form-container');
        let module_id = $container.data('module'); // 自动识别是哪个模块的请求(如 bazi, ziwei)
        let $loading = $container.find('.yfj-loading');
        let $result = $container.find('.yfj-result-area');

        let nonce = $form.find('input[name="yfj_nonce_field"]').val();

        // 序列化表单业务数据，转为对象
        let formDataArray = $form.serializeArray();
        let businessData = {};
        $.each(formDataArray, function() {
            if(this.name !== 'yfj_nonce_field' && this.name !== '_wp_http_referer') {
                businessData[this.name] = this.value;
            }
        });

        // ================== 【前端弹窗断点调试】 ==================
        // 调试完毕后，在下一行最前面加上 // 把它注释掉，即可正常请求接口
        //alert("【即将发送的接口参数】\n\n" + JSON.stringify(businessData, null, 4)); return;
        // =======================================================

        $form.hide();
        $loading.show();
        $result.hide().empty();

        $.ajax({
            url: yfj_globals.ajax_url,
            type: 'POST',
            data: {
                action: 'yfj_action_' + module_id, // 动态路由: yfj_action_bazi
                nonce: nonce,
                form_data: businessData            // 业务参数统一打包发送
            },
            success: function(res) {
                $loading.hide();
                $form.show();
                if(res.success) {
                    $result.html(res.data.html).fadeIn();
                } else {
                    // 使用注入的翻译变量
                    $result.html('<div style="color:red;">' + yfj_globals.err_prefix + res.data + '</div>').fadeIn();
                }
            },
            error: function() {
                $loading.hide();
                $form.show();
                // 使用注入的翻译变量
                $result.html('<div style="color:red;">' + yfj_globals.err_net + '</div>').fadeIn();
            }
        });
    });
});