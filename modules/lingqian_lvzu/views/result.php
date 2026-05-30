<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fff3cd; padding:12px 15px; color:#856404; margin-bottom:20px; border-radius:6px; border-left: 4px solid #ffeeba; font-size: 14px; line-height: 1.6;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<?php
// 安全拦截
if (empty($data) || !is_array($data)) {
    echo '<div style="color:red; text-align:center; padding: 20px;">' . $this->t('暂无数据') . '</div>';
    return;
}

// 提取数据
$base = $data['list'] ?? $data['data'] ?? $data;
$lvzu = $base['lvzu'] ?? [];
$content = $lvzu['content'] ?? [];

// 吕祖专属小头像路径
$current_lang = get_option('yfj_language', 'zh-cn');
$lang_suffix = ($current_lang === 'zh-tw') ? 'zh-tw' : 'zh-cn';
$image_url = YFJ_PLUGIN_URL . 'assets/image/lingqian/' . $lang_suffix . '/lvzu.jpg';
?>

<style>
    .yfj-lq-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-lq-wrapper * { box-sizing: border-box; }
    .yfj-panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 24px; overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #0f172a; font-size: 16px; }
    .yfj-panel-body { padding: 20px; font-size: 14.5px; line-height: 1.8; position: relative; }

    /* 方块宫格布局 */
    .yfj-block-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .yfj-block-item { background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02); display: flex; flex-direction: column; transition: transform 0.2s; }
    .yfj-block-item:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
    .yfj-block-title { font-weight: bold; color: #0284c7; display: inline-block; margin-bottom: 8px; border-bottom: 1px dashed #bae6fd; padding-bottom: 6px; font-size: 15px; }
    .yfj-block-content { color: #334155; font-size: 14px; line-height: 1.6; }

    /* 吕祖灵签移动端专属适配 */
    @media (max-width: 600px) {
        /* 1. 减小面板内边距，给文字留出空间 */
        .yfj-panel-body { padding: 15px; }

        /* 2. 缩小右上角吕祖画像的尺寸，防止左侧签文被挤压过度 */
        .yfj-panel-body > div[style*="float: right"] { width: 85px !important; margin: 0 0 10px 10px !important; }

        /* 3. 核心：将解签的宫格强制改为单列向下排布，阅读体验最佳 */
        .yfj-block-grid { grid-template-columns: 1fr; gap: 10px; }

        /* 4. 统一卡片内边距和排版对齐方向 */
        .yfj-block-item { padding: 12px; }
    }
</style>

<div class="yfj-lq-wrapper">

    <!-- 1. 基本信息 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-visibility"></span> <?php echo $this->t('抽签结果'); ?></div>
        <div class="yfj-panel-body">

            <!-- 右侧吕祖小画像 -->
            <div style="float: right; margin: 0 0 15px 15px; border-radius: 6px; border: 1px solid #e2e8f0; overflow: hidden; width: 110px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <img src="<?php echo esc_url($image_url); ?>" style="width: 100%; display: block; border-bottom: 3px solid #38bdf8;">
            </div>

            <p style="margin: 0 0 10px 0; font-size: 15px;">
                <strong><?php echo $this->t('您抽出吕祖灵签第：'); ?></strong>
                <span style="color: #dc2626; font-size: 18px; font-weight: bold;"><?php echo esc_html($base['id'] ?? ''); ?></span>
                <?php echo $this->t('签'); ?>
            </p>

            <p style="margin: 0 0 10px 0; font-size: 15px;">
                <strong><?php echo $this->t('签曰：'); ?></strong>
                <span style="color: #dc2626; font-weight: bold;">
                    <?php echo esc_html(!empty($lvzu['title']) ? $lvzu['title'] : $this->t('无')); ?>
                </span>
            </p>

            <div style="clear: both;"></div>

            <!-- 签文、诗曰与解签 -->
            <div style="background: #f0f9ff; padding: 18px; border-radius: 6px; border: 1px solid #bae6fd; margin-top: 15px;">
                <p style="margin: 0 0 12px 0;">
                    <strong style="color: #0f172a;"><?php echo $this->t('签文：'); ?></strong>
                    <span style="color: #0369a1; font-weight: 500;"><?php echo esc_html($content['签文'] ?? $this->t('无')); ?></span>
                </p>
                <p style="margin: 0 0 12px 0;">
                    <strong style="color: #0f172a;"><?php echo $this->t('诗曰：'); ?></strong>
                    <span style="color: #0284c7;"><?php echo esc_html($content['诗曰'] ?? $this->t('无')); ?></span>
                </p>
                <p style="margin: 0;">
                    <strong style="color: #0f172a;"><?php echo $this->t('解曰：'); ?></strong>
                    <span style="color: #334155; line-height: 1.7;"><?php echo esc_html($content['诗解曰'] ?? $this->t('无')); ?></span>
                </p>
            </div>
        </div>
    </div>

    <!-- 2. 解签详细信息 (宫格展示) -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-book-alt"></span> <?php echo $this->t('解签信息'); ?></div>
        <div class="yfj-panel-body" style="background: #f8fafc;">
            <div class="yfj-block-grid">
                <?php
                // 吕祖灵签专属的具体解签字段
                $detail_keys = ['谋望', '钱财', '婚姻', '自身', '家宅', '开业', '迁居', '出行', '疾病', '六甲', '行人', '诉讼', '运势'];

                $valid_items = [];
                foreach ($detail_keys as $k) {
                    if (!empty($content[$k])) {
                        $valid_items[$k] = $content[$k];
                    }
                }

                // 循环输出方块 (彻底去除奇数跨列逻辑，格式与其余模块完全看齐)
                foreach ($valid_items as $label => $val):
                    ?>
                    <div class="yfj-block-item">
                        <div style="text-align: center;">
                            <span class="yfj-block-title"><?php echo esc_html(sprintf($this->t('【%s】'), $this->t($label))); ?></span>
                        </div>
                        <span class="yfj-block-content"><?php echo esc_html($val); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- 免责声明与按钮 -->
    <?php echo $this->get_disclaimer_html(); ?>

    <div style="text-align: center; margin-top: 10px;">
        <button type="button"
                onclick="this.disabled=true; this.style.opacity='0.6'; this.innerText='<?php echo $this->t('正在重置...'); ?>'; window.location.reload();"
                style="background: #e2e8f0; color: #334155; border: none; padding: 12px 30px; border-radius: 50px; font-size: 15px; font-weight: bold; cursor: pointer; transition: all 0.2s;">
            <?php echo $this->t('返回重求'); ?>
        </button>
    </div>
</div>