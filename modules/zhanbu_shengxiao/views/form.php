<style>
    /* 强制表单常驻顶部 */
    .yfj-form-container[data-module="<?php echo esc_attr($module_id); ?>"] .yfj-ajax-form {
        display: block !important;
    }
    .yfj-form-container[data-module="<?php echo esc_attr($module_id); ?>"] .yfj-result-area {
        margin-top: 25px;
    }

    /* 生肖选择网格样式 (中国红配色) */
    .yfj-sx-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 12px; }
    .yfj-sx-label { display: block; cursor: pointer; margin: 0; }
    .yfj-sx-label input[type="radio"] { display: none; }
    .yfj-sx-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px 5px; text-align: center; transition: all 0.2s; color: #475569; }
    .yfj-sx-card:hover { border-color: #fecaca; background: #fff1f2; }

    /* 选中状态：红色高亮 */
    .yfj-sx-label input[type="radio"]:checked + .yfj-sx-card { background: #fff1f2; border-color: #e11d48; color: #e11d48; box-shadow: 0 0 0 2px rgba(225, 29, 72, 0.2); }

    .yfj-sx-icon { font-size: 26px; margin-bottom: 8px; line-height: 1; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1)); }
    .yfj-sx-name { font-size: 14px; font-weight: 600; letter-spacing: 1px; }
</style>

<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">

    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="margin: 0 0 10px 0; color: #9f1239;"><?php echo $this->t('生肖流年每日运势'); ?></h3>
        <p style="font-size: 13px; color: var(--yfj-text-body); margin: 0;">
            <?php echo $this->t('点击您的属相，精准洞悉今日、本周及本年度的财富、事业与感情走向。'); ?>
        </p>
    </div>

    <!-- 常驻顶部的表单 -->
    <form class="yfj-ajax-form" id="yfj-shengxiao-form">
        <?php wp_nonce_field('yfj_nonce', 'yfj_nonce_field'); ?>

        <!-- 固定 type 为 1 (查询生肖) -->
        <input type="hidden" name="type" value="1">

        <?php
        // 12 生肖配置数据 (严格对应 0-11 的索引)
        $shengxiao_list = [
            ['id' => 0,  'name' => '属鼠', 'icon' => '🐀'],
            ['id' => 1,  'name' => '属牛', 'icon' => '🐂'],
            ['id' => 2,  'name' => '属虎', 'icon' => '🐅'],
            ['id' => 3,  'name' => '属兔', 'icon' => '🐇'],
            ['id' => 4,  'name' => '属龙', 'icon' => '🐉'],
            ['id' => 5,  'name' => '属蛇', 'icon' => '🐍'],
            ['id' => 6,  'name' => '属马', 'icon' => '🐎'],
            ['id' => 7,  'name' => '属羊', 'icon' => '🐏'],
            ['id' => 8,  'name' => '属猴', 'icon' => '🐒'],
            ['id' => 9,  'name' => '属鸡', 'icon' => '🐓'],
            ['id' => 10, 'name' => '属狗', 'icon' => '🐕'],
            ['id' => 11, 'name' => '属猪', 'icon' => '🐖'],
        ];
        ?>

        <div class="yfj-sx-grid">
            <?php foreach ($shengxiao_list as $index => $sx): ?>
                <label class="yfj-sx-label">
                    <input type="radio" name="title_yunshi" value="<?php echo $sx['id']; ?>" <?php checked($index, 0); ?>>
                    <div class="yfj-sx-card">
                        <div class="yfj-sx-icon"><?php echo $sx['icon']; ?></div>
                        <div class="yfj-sx-name"><?php echo $this->t($sx['name']); ?></div>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>

        <button type="submit" style="display: none;"></button>
    </form>

    <div class="yfj-loading" style="display:none; text-align: center; margin-top: 25px; color: #e11d48;">
        <?php echo $this->t('正在推演生肖运势，请稍候...'); ?>
    </div>
    <div class="yfj-result-area"></div>
</div>

<script>
    jQuery(document).ready(function($) {
        var $form = $('#yfj-shengxiao-form');

        // 1. 页面加载完成后，自动触发一次查询 (默认属鼠)
        setTimeout(function() {
            $form.trigger('submit');
        }, 100);

        // 2. 监听生肖选择，一旦改变自动提交
        $('input[name="title_yunshi"]').on('change', function() {
            $('.yfj-result-area').empty();
            $form.trigger('submit');
        });
    });
</script>