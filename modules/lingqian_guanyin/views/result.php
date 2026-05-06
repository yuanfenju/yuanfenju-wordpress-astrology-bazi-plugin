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

// 提取数据[cite: 12]
$base = $data['list'] ?? $data['data'] ?? $data;
$guanyin = $base['guanyin'] ?? [];
$content = $guanyin['content'] ?? [];

// 观音专属小头像路径
$current_lang = get_option('yfj_language', 'zh-cn');
$lang_suffix = ($current_lang === 'zh-tw') ? 'zh-tw' : 'zh-cn';
$image_url = YFJ_PLUGIN_URL . 'assets/image/lingqian/' . $lang_suffix . '/guanyin.jpg';
?>

<style>
    .yfj-lq-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 24px; overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #0f172a; font-size: 16px; }
    .yfj-panel-body { padding: 20px; font-size: 14.5px; line-height: 1.8; position: relative; }

    /* 单行长文本列表样式 */
    .yfj-list-item { background: #fff; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); margin-bottom: 15px; }
    .yfj-list-item:last-child { margin-bottom: 0; }
    .yfj-list-title { font-weight: bold; color: #16a34a; margin-bottom: 10px; border-bottom: 1px dashed #bbf7d0; padding-bottom: 8px; font-size: 15.5px; display: block; }
    .yfj-list-content { color: #334155; font-size: 14.5px; line-height: 1.8; display: block; text-align: justify; }
</style>

<div class="yfj-lq-wrapper">

    <!-- 1. 基本信息 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-visibility"></span> <?php echo $this->t('抽签结果'); ?></div>
        <div class="yfj-panel-body">

            <!-- 右侧观音小画像 -->
            <div style="float: right; margin: 0 0 15px 15px; border-radius: 6px; border: 1px solid #e2e8f0; overflow: hidden; width: 110px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <img src="<?php echo esc_url($image_url); ?>" style="width: 100%; display: block; border-bottom: 3px solid #4ade80;">
            </div>

            <p style="margin: 0 0 10px 0; font-size: 15px;">
                <strong><?php echo $this->t('您抽出观音灵签第：'); ?></strong>
                <span style="color: #dc2626; font-size: 18px; font-weight: bold;"><?php echo esc_html($base['id'] ?? ''); ?></span>
                <?php echo $this->t('签'); ?>
            </p>

            <p style="margin: 0 0 10px 0; font-size: 15px;">
                <strong><?php echo $this->t('签曰：'); ?></strong>
                <span style="color: #dc2626; font-weight: bold;">
                    <?php echo esc_html(!empty($guanyin['title']) ? $guanyin['title'] : $this->t('无')); ?>
                </span>
            </p>

            <?php if (!empty($content['吉凶'])): ?>
                <p style="margin: 0 0 10px 0; font-size: 15px;">
                    <strong><?php echo $this->t('吉凶：'); ?></strong>
                    <span style="background: #fee2e2; color: #dc2626; padding: 2px 10px; border-radius: 4px; font-weight: bold; border: 1px solid #fecaca;">
                    <?php echo esc_html($content['吉凶']); ?>
                </span>
                </p>
            <?php endif; ?>

            <?php if (!empty($content['宫位'])): ?>
                <p style="margin: 0 0 10px 0; font-size: 15px;">
                    <strong><?php echo $this->t('宫位：'); ?></strong>
                    <span style="color: #16a34a; font-weight: bold;">
                    <?php echo esc_html($content['宫位']); ?>
                </span>
                </p>
            <?php endif; ?>

            <div style="clear: both;"></div>

            <!-- 签辞、签语与解签 -->
            <div style="background: #f0fdf4; padding: 18px; border-radius: 6px; border: 1px solid #bbf7d0; margin-top: 15px;">
                <p style="margin: 0 0 12px 0;">
                    <strong style="color: #0f172a;"><?php echo $this->t('签诗：'); ?></strong>
                    <span style="color: #b91c1c; font-weight: 500;"><?php echo esc_html($content['签辞'] ?? $this->t('无')); ?></span>
                </p>
                <p style="margin: 0 0 12px 0;">
                    <strong style="color: #0f172a;"><?php echo $this->t('签语：'); ?></strong>
                    <span style="color: #15803d;"><?php echo esc_html($content['签语'] ?? $this->t('无')); ?></span>
                </p>
                <p style="margin: 0;">
                    <strong style="color: #0f172a;"><?php echo $this->t('解签：'); ?></strong>
                    <span style="color: #334155; line-height: 1.7;"><?php echo esc_html($content['解签'] ?? $this->t('无')); ?></span>
                </p>
            </div>
        </div>
    </div>

    <!-- 2. 解签详细信息 (单行列表排版，完美兼容长文本) -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-book-alt"></span> <?php echo $this->t('解签信息'); ?></div>
        <div class="yfj-panel-body" style="background: #f8fafc;">
            <?php
            // 观音灵签专属的具体解签字段[cite: 12]
            $detail_keys = ['仙机', '详解'];

            foreach ($detail_keys as $k) {
                if (!empty($content[$k])) {
                    ?>
                    <div class="yfj-list-item">
                        <!-- UI 标题必须翻译 -->
                        <span class="yfj-list-title"><?php echo esc_html(sprintf($this->t('【%s】'), $this->t($k))); ?></span>
                        <!-- 接口返回断语直接输出，长文本自动换行排满整行 -->
                        <span class="yfj-list-content"><?php echo esc_html($content[$k]); ?></span>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>

    <!-- 免责声明与按钮 -->
    <?php echo $this->get_disclaimer_html(); ?>

    <div style="text-align: center; margin-top: 10px;">
        <button onclick="jQuery('.yfj-result-area').hide(); jQuery('#yfj-qiuqian-ui').fadeIn(); jQuery('.yfj-ajax-form').show();"
                style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; padding: 12px 30px; border-radius: 50px; font-size: 14px; font-weight: bold; cursor: pointer;">
            <?php echo $this->t('返回重求'); ?>
        </button>
    </div>
</div>