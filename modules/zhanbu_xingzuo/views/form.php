<style>
    /* 强制表单常驻顶部，不被底层的 ajax 脚本隐藏 */
    .yfj-form-container[data-module="<?php echo esc_attr($module_id); ?>"] .yfj-ajax-form {
        display: block !important;
    }
    .yfj-form-container[data-module="<?php echo esc_attr($module_id); ?>"] .yfj-result-area {
        margin-top: 25px;
    }

    /* 星座选择网格样式 */
    .yfj-xz-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 12px; }
    .yfj-xz-label { display: block; cursor: pointer; margin: 0; }
    .yfj-xz-label input[type="radio"] { display: none; }
    .yfj-xz-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 5px; text-align: center; transition: all 0.2s; color: #475569; }
    .yfj-xz-card:hover { border-color: #cbd5e1; background: #f1f5f9; }
    .yfj-xz-label input[type="radio"]:checked + .yfj-xz-card { background: #eef2ff; border-color: #6366f1; color: #4f46e5; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
    .yfj-xz-icon { font-size: 24px; margin-bottom: 5px; line-height: 1; }
    .yfj-xz-name { font-size: 13px; font-weight: 600; }
    .yfj-xz-date { font-size: 11px; color: #94a3b8; margin-top: 3px; }
</style>

<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">

    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="margin: 0 0 10px 0; color: var(--yfj-text-dark);"><?php echo $this->t('每日星座运势'); ?></h3>
        <p style="font-size: 13px; color: var(--yfj-text-body); margin: 0;">
            <?php echo $this->t('选择您的星座，全面解析今日、明日、本周、本月及本年度的财富、事业与爱情运势。'); ?>
        </p>
    </div>

    <!-- 常驻顶部的表单 -->
    <form class="yfj-ajax-form" id="yfj-xingzuo-form">
        <?php wp_nonce_field('yfj_nonce', 'yfj_nonce_field'); ?>

        <!-- 固定 type 为 0 (查询星座) -->
        <input type="hidden" name="type" value="0">

        <?php
        // 12 星座配置数据
        $constellations = [
            ['id' => 0,  'name' => '白羊座', 'date' => '3.21-4.19', 'icon' => '♈'],
            ['id' => 1,  'name' => '金牛座', 'date' => '4.20-5.20', 'icon' => '♉'],
            ['id' => 2,  'name' => '双子座', 'date' => '5.21-6.21', 'icon' => '♊'],
            ['id' => 3,  'name' => '巨蟹座', 'date' => '6.22-7.22', 'icon' => '♋'],
            ['id' => 4,  'name' => '狮子座', 'date' => '7.23-8.22', 'icon' => '♌'],
            ['id' => 5,  'name' => '处女座', 'date' => '8.23-9.22', 'icon' => '♍'],
            ['id' => 6,  'name' => '天秤座', 'date' => '9.23-10.23', 'icon' => '♎'],
            ['id' => 7,  'name' => '天蝎座', 'date' => '10.24-11.22', 'icon' => '♏'],
            ['id' => 8,  'name' => '射手座', 'date' => '11.23-12.21', 'icon' => '♐'],
            ['id' => 9,  'name' => '摩羯座', 'date' => '12.22-1.19', 'icon' => '♑'],
            ['id' => 10, 'name' => '水瓶座', 'date' => '1.20-2.18', 'icon' => '♒'],
            ['id' => 11, 'name' => '双鱼座', 'date' => '2.19-3.20', 'icon' => '♓'],
        ];
        ?>

        <div class="yfj-xz-grid">
            <?php foreach ($constellations as $index => $c): ?>
                <label class="yfj-xz-label">
                    <input type="radio" name="title_yunshi" value="<?php echo $c['id']; ?>" <?php checked($index, 0); ?>>
                    <div class="yfj-xz-card">
                        <div class="yfj-xz-icon"><?php echo $c['icon']; ?></div>
                        <div class="yfj-xz-name"><?php echo $this->t($c['name']); ?></div>
                        <div class="yfj-xz-date"><?php echo $c['date']; ?></div>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>

        <button type="submit" style="display: none;"></button>
    </form>

    <div class="yfj-loading" style="display:none; text-align: center; margin-top: 25px; color: #64748b;">
        <?php echo $this->t('正在获取星象指引，请稍候...'); ?>
    </div>
    <div class="yfj-result-area"></div>
</div>

<script>
    jQuery(document).ready(function($) {
        var $form = $('#yfj-xingzuo-form');

        // 1. 页面加载完成后，自动触发一次查询 (默认白羊座)
        setTimeout(function() {
            $form.trigger('submit');
        }, 100);

        // 2. 监听星座选择，一旦改变自动提交
        $('input[name="title_yunshi"]').on('change', function() {
            $('.yfj-result-area').empty();
            $form.trigger('submit');
        });
    });
</script>