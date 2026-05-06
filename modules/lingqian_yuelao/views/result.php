<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fff3cd; padding:12px 15px; color:#856404; margin-bottom:20px; border-radius:6px; border-left: 4px solid #ffeeba; font-size: 14px; line-height: 1.6;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<?php
// 安全拦截[cite: 6]
if (empty($data) || !is_array($data)) {
    echo '<div style="color:red; text-align:center; padding: 20px;">' . $this->t('暂无数据') . '</div>';
    return;
}

// 提取数据[cite: 6]
$base = $data['list'] ?? $data['data'] ?? $data;
$yuelao = $base['yuelao'] ?? [];
$content = $yuelao['content'] ?? [];

// 月老专属小头像路径[cite: 6]
$current_lang = get_option('yfj_language', 'zh-cn');
$lang_suffix = ($current_lang === 'zh-tw') ? 'zh-tw' : 'zh-cn';
$image_url = YFJ_PLUGIN_URL . 'assets/image/lingqian/' . $lang_suffix . '/yuelao.jpg';
?>

<style>
    .yfj-lq-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 24px; overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #0f172a; font-size: 16px; }
    .yfj-panel-body { padding: 20px; font-size: 14.5px; line-height: 1.8; position: relative; }

    /* 改为单行长文本列表样式，去掉原先的方块宫格[cite: 6] */
    .yfj-list-item { background: #fff; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); margin-bottom: 15px; }
    .yfj-list-item:last-child { margin-bottom: 0; }
    .yfj-list-title { font-weight: bold; color: #be185d; margin-bottom: 10px; border-bottom: 1px dashed #fbcfe8; padding-bottom: 8px; font-size: 15.5px; display: block; }
    .yfj-list-content { color: #334155; font-size: 14.5px; line-height: 1.8; display: block; text-align: justify; }
</style>

<div class="yfj-lq-wrapper">

    <!-- 1. 基本信息[cite: 6] -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-visibility"></span> <?php echo $this->t('抽签结果'); ?></div>
        <div class="yfj-panel-body">

            <!-- 右侧月老小画像[cite: 6] -->
            <div style="float: right; margin: 0 0 15px 15px; border-radius: 6px; border: 1px solid #e2e8f0; overflow: hidden; width: 110px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <img src="<?php echo esc_url($image_url); ?>" style="width: 100%; display: block; border-bottom: 3px solid #f472b6;">
            </div>

            <p style="margin: 0 0 10px 0; font-size: 15px;">
                <strong><?php echo $this->t('您抽出月老灵签第：'); ?></strong>
                <span style="color: #dc2626; font-size: 18px; font-weight: bold;"><?php echo esc_html($base['id'] ?? ''); ?></span>
                <?php echo $this->t('签'); ?>
            </p>

            <p style="margin: 0 0 10px 0; font-size: 15px;">
                <strong><?php echo $this->t('签曰：'); ?></strong>
                <span style="color: #dc2626; font-weight: bold;">
                    <?php echo esc_html(!empty($yuelao['title']) ? $yuelao['title'] : $this->t('无')); ?>
                </span>
            </p>

            <p style="margin: 0 0 10px 0; font-size: 15px;">
                <strong><?php echo $this->t('吉凶：'); ?></strong>
                <span style="background: #fdf2f8; color: #be185d; padding: 2px 10px; border-radius: 4px; font-weight: bold; border: 1px solid #fbcfe8;">
                    <?php echo esc_html(!empty($content['吉凶']) ? $content['吉凶'] : $this->t('未知')); ?>
                </span>
            </p>

            <div style="clear: both;"></div>

            <!-- 解签[cite: 6] -->
            <div style="background: #fdf2f8; padding: 18px; border-radius: 6px; border: 1px solid #fbcfe8; margin-top: 15px;">
                <p style="margin: 0;">
                    <strong style="color: #0f172a;"><?php echo $this->t('解签：'); ?></strong>
                    <span style="color: #334155; line-height: 1.7;"><?php echo esc_html($content['解签'] ?? $this->t('无')); ?></span>
                </p>
            </div>
        </div>
    </div>

    <!-- 2. 解签详细信息 (改为单行全宽排版，完美兼容“白话”等长文本) -->
    <?php
    // 排除已知核心字段[cite: 6]
    $core_keys = ['吉凶', '解签'];
    $valid_items = [];
    foreach ($content as $k => $v) {
        if (!in_array($k, $core_keys) && !empty($v)) {
            $valid_items[$k] = $v;
        }
    }

    if (!empty($valid_items)):
        ?>
        <div class="yfj-panel">
            <div class="yfj-panel-heading"><span class="dashicons dashicons-book-alt"></span> <?php echo $this->t('解签信息'); ?></div>
            <div class="yfj-panel-body" style="background: #f8fafc;">
                <?php foreach ($valid_items as $label => $val): ?>
                    <div class="yfj-list-item">
                        <span class="yfj-list-title"><?php echo esc_html(sprintf($this->t('【%s】'), $this->t($label))); ?></span>
                        <span class="yfj-list-content"><?php echo esc_html($val); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- 免责声明与按钮[cite: 6] -->
    <?php echo $this->get_disclaimer_html(); ?>

    <div style="text-align: center; margin-top: 10px;">
        <button onclick="jQuery('.yfj-result-area').hide(); jQuery('#yfj-qiuqian-ui').fadeIn(); jQuery('.yfj-ajax-form').show();"
                style="background: #fdf2f8; color: #be185d; border: 1px solid #fbcfe8; padding: 12px 30px; border-radius: 50px; font-size: 14px; font-weight: bold; cursor: pointer;">
            <?php echo $this->t('返回重求'); ?>
        </button>
    </div>
</div>