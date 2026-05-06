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

// 提取数据[cite: 11]
$base = $data['list'] ?? $data['data'] ?? $data;
$mazu = $base['mazu'] ?? [];
$content = $mazu['content'] ?? [];

// 妈祖专属小头像路径
$current_lang = get_option('yfj_language', 'zh-cn');
$lang_suffix = ($current_lang === 'zh-tw') ? 'zh-tw' : 'zh-cn';
$image_url = YFJ_PLUGIN_URL . 'assets/image/lingqian/' . $lang_suffix . '/mazu.jpg';
?>

<style>
    .yfj-lq-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 24px; overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #0f172a; font-size: 16px; }
    .yfj-panel-body { padding: 20px; font-size: 14.5px; line-height: 1.8; position: relative; }

    /* 方块宫格布局 */
    .yfj-block-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .yfj-block-item { background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02); display: flex; flex-direction: column; transition: transform 0.2s; }
    .yfj-block-item:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
    .yfj-block-item.is-last-odd { grid-column: 1 / -1; align-items: center; text-align: center; }
    .yfj-block-title { font-weight: bold; color: #0891b2; display: inline-block; margin-bottom: 8px; border-bottom: 1px dashed #a5f3fc; padding-bottom: 6px; font-size: 15px; }
    .yfj-block-content { color: #334155; font-size: 14px; line-height: 1.6; }
</style>

<div class="yfj-lq-wrapper">

    <!-- 1. 基本信息 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-visibility"></span> <?php echo $this->t('抽签结果'); ?></div>
        <div class="yfj-panel-body">

            <!-- 右侧妈祖小画像 -->
            <div style="float: right; margin: 0 0 15px 15px; border-radius: 6px; border: 1px solid #e2e8f0; overflow: hidden; width: 110px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <img src="<?php echo esc_url($image_url); ?>" style="width: 100%; display: block; border-bottom: 3px solid #22d3ee;">
            </div>

            <p style="margin: 0 0 10px 0; font-size: 15px;">
                <strong><?php echo $this->t('您抽出妈祖灵签第：'); ?></strong>
                <span style="color: #dc2626; font-size: 18px; font-weight: bold;"><?php echo esc_html($base['id'] ?? ''); ?></span>
                <?php echo $this->t('签'); ?>
            </p>

            <p style="margin: 0 0 10px 0; font-size: 15px;">
                <strong><?php echo $this->t('签曰：'); ?></strong>
                <span style="color: #dc2626; font-weight: bold;">
                    <?php echo esc_html(!empty($mazu['title']) ? $mazu['title'] : $this->t('无')); ?>
                </span>
            </p>

            <div style="clear: both;"></div>

            <!-- 签诗与解签 -->
            <div style="background: #ecfeff; padding: 18px; border-radius: 6px; border: 1px solid #cffafe; margin-top: 15px;">
                <p style="margin: 0 0 12px 0;">
                    <strong style="color: #0f172a;"><?php echo $this->t('签诗：'); ?></strong>
                    <!-- 妈祖的签诗字段名为“描述”[cite: 11] -->
                    <span style="color: #b91c1c; font-weight: 500;"><?php echo esc_html($content['描述'] ?? $this->t('无')); ?></span>
                </p>
                <p style="margin: 0;">
                    <strong style="color: #0f172a;"><?php echo $this->t('解签：'); ?></strong>
                    <span style="color: #334155; line-height: 1.7;"><?php echo esc_html($content['解签'] ?? $this->t('无')); ?></span>
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
                // 妈祖灵签专属的具体解签字段[cite: 11]
                $detail_keys = ['运势'];

                $valid_items = [];
                foreach ($detail_keys as $k) {
                    if (!empty($content[$k])) {
                        $valid_items[$k] = $content[$k];
                    }
                }

                $total = count($valid_items);
                $curr = 0;

                foreach ($valid_items as $label => $val):
                    $curr++;
                    $is_last_odd = ($total % 2 !== 0 && $curr === $total);
                    ?>
                    <div class="yfj-block-item <?php echo $is_last_odd ? 'is-last-odd' : ''; ?>">
                        <div style="<?php echo $is_last_odd ? '' : 'text-align: center;'; ?>">
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
        <button onclick="jQuery('.yfj-result-area').hide(); jQuery('#yfj-qiuqian-ui').fadeIn(); jQuery('.yfj-ajax-form').show();"
                style="background: #ecfeff; color: #0891b2; border: 1px solid #cffafe; padding: 12px 30px; border-radius: 50px; font-size: 14px; font-weight: bold; cursor: pointer;">
            <?php echo $this->t('返回重求'); ?>
        </button>
    </div>
</div>