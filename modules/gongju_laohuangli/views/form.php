<style>
    /* 强制老黄历的表单不被底层的 ajax 脚本隐藏，使其常驻顶部 */
    .yfj-form-container[data-module="<?php echo esc_attr($module_id); ?>"] .yfj-ajax-form {
        display: block !important;
    }
    .yfj-form-container[data-module="<?php echo esc_attr($module_id); ?>"] .yfj-result-area {
        margin-top: 20px;
    }
</style>

<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">

    <!-- 隐藏式提交表单，外层容器做成控制条样式 -->
    <form class="yfj-ajax-form" id="yfj-laohuangli-form">
        <?php wp_nonce_field('yfj_nonce', 'yfj_nonce_field'); ?>

        <?php
        // 计算前后 90 天的日期范围限制
        $today = date('Y-m-d');
        $min_date = date('Y-m-d', strtotime('-90 days'));
        $max_date = date('Y-m-d', strtotime('+90 days'));
        ?>

        <div style="background: #f8fafc; border: 1px solid var(--yfj-border); border-radius: 8px; padding: 15px; display: flex; align-items: center; justify-content: center; gap: 15px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
            <label style="font-weight: 600; color: #334155; margin: 0; font-size: 15px;">
                <span class="dashicons dashicons-calendar-alt"></span> <?php echo $this->t('黄历日期：'); ?>
            </label>
            <!-- 日期选择器 -->
            <input type="date" name="title_laohuangli" id="laohuangli_date_picker"
                   value="<?php echo $today; ?>"
                   min="<?php echo $min_date; ?>"
                   max="<?php echo $max_date; ?>"
                   required
                   style="padding: 8px 15px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 16px; min-width: 180px; cursor: pointer; outline: none;">

            <!-- 隐藏的 submit 按钮，配合底层拦截逻辑 -->
            <button type="submit" style="display: none;"></button>
        </div>
    </form>

    <div class="yfj-loading" style="display:none; text-align: center; margin-top: 25px; color: #64748b;">
        <?php echo $this->t('正在加载黄历数据，请稍候...'); ?>
    </div>
    <div class="yfj-result-area"></div>
</div>

<script>
    jQuery(document).ready(function($) {
        var $form = $('#yfj-laohuangli-form');
        var $datePicker = $('#laohuangli_date_picker');

        // 1. 页面加载完成后，自动触发一次表单提交，静默渲染当天的老黄历
        setTimeout(function() {
            $form.trigger('submit');
        }, 100);

        // 2. 当用户改变日期时，无需点击按钮，自动触发提交刷新下方数据
        $datePicker.on('change', function() {
            // 清空旧结果并显示 loading（让交互更有呼吸感）
            $('.yfj-result-area').empty();
            $form.trigger('submit');
        });
    });
</script>