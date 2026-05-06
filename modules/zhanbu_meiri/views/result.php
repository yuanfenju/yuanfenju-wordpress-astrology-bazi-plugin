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

$base = $data['list'] ?? $data['data'] ?? $data;
$desc = $base['description'] ?? [];
?>

<style>
    .yfj-mr-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 24px; overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #0f172a; font-size: 16px; display: flex; align-items: center; gap: 8px; }
    .yfj-panel-body { padding: 20px; font-size: 14.5px; line-height: 1.8; }

    /* === 方块宫格布局核心 CSS === */
    .yfj-block-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr); /* 默认两列 */
        gap: 12px;
    }
    .yfj-block-item {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        transition: transform 0.2s;
    }
    .yfj-block-item:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.05); }

    /* 奇数个时的最后一个元素自动跨列并居中 */
    .yfj-block-item.is-last-odd {
        grid-column: 1 / -1;
        align-items: center; /* 整个方块内容居中 */
        text-align: center;
    }

    .yfj-block-title {
        font-weight: bold;
        color: #b45309;
        display: inline-block;
        margin-bottom: 8px;
        border-bottom: 1px dashed #fcd34d;
        padding-bottom: 6px;
        font-size: 15px;
    }
    .yfj-block-content {
        color: #334155;
        font-size: 14px;
        line-height: 1.6;
    }
</style>

<div class="yfj-mr-wrapper">

    <!-- 1. 基本信息 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-visibility"></span> <?php echo $this->t('基本信息'); ?></div>
        <div class="yfj-panel-body">

            <p style="margin: 0 0 10px 0; font-size: 15px;">
                <strong><?php echo $this->t('您掷出随机数：'); ?></strong>
                <span style="color: #dc2626; font-size: 18px; font-weight: bold;">
                    <?php echo esc_html($base['number'] ?? ''); ?>
                </span>
            </p>

            <p style="margin: 0 0 10px 0; font-size: 15px;">
                <strong><?php echo $this->t('卦名：'); ?></strong>
                <span style="color: #dc2626; font-weight: bold;">
                    <?php echo esc_html(!empty($base['guaming']) ? $base['guaming'] : $this->t('无')); ?>
                </span>
            </p>

            <p style="margin: 0 0 10px 0; font-size: 15px;">
                <strong><?php echo $this->t('吉凶：'); ?></strong>
                <span style="background: #fee2e2; color: #dc2626; padding: 2px 10px; border-radius: 4px; font-weight: bold; border: 1px solid #fecaca;">
                    <?php echo esc_html(!empty($desc['凶吉']) ? $desc['凶吉'] : $this->t('未知')); ?>
                </span>
            </p>

            <div style="background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0; margin-top: 15px;">
                <p style="margin: 0 0 10px 0;">
                    <strong style="color: #0f172a;"><?php echo $this->t('卦曰：'); ?></strong>
                    <span style="color: #b91c1c;">
                        <?php echo esc_html(!empty($desc['卦曰']) ? $desc['卦曰'] : $this->t('无')); ?>
                    </span>
                </p>
                <p style="margin: 0;">
                    <strong style="color: #0f172a;"><?php echo $this->t('解卦：'); ?></strong>
                    <span style="color: #475569;">
                        <?php echo esc_html(!empty($desc['解曰']) ? $desc['解曰'] : $this->t('无')); ?>
                    </span>
                </p>
            </div>

        </div>
    </div>

    <!-- 2. 解卦信息 (智能方块宫格布局) -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-book-alt"></span> <?php echo $this->t('解卦信息'); ?></div>
        <div class="yfj-panel-body" style="background: #f8fafc;">
            <div class="yfj-block-grid">
                <?php
                // 定义所有需要输出的方块字段，保证顺序
                $items_keys = ['运势', '财富', '感情', '事业', '身体', '神鬼', '行人'];

                // 过滤出有数据的字段
                $valid_items = [];
                foreach ($items_keys as $k) {
                    if (!empty($desc[$k])) {
                        $valid_items[$k] = $desc[$k];
                    }
                }

                $total = count($valid_items);
                $count = 0;

                // 循环输出方块
                foreach ($valid_items as $k => $v) {
                    $count++;
                    // 判断是否是奇数个的最后一个（比如第7个“行人”）
                    $is_last_odd = ($total % 2 !== 0 && $count === $total) ? ' is-last-odd' : '';
                    ?>

                    <div class="yfj-block-item<?php echo $is_last_odd; ?>">
                        <div style="<?php echo $is_last_odd ? '' : 'text-align: center;'; ?>">
                            <span class="yfj-block-title"><?php echo esc_html(sprintf($this->t('【%s】'), $this->t($k))); ?></span>
                        </div>
                        <span class="yfj-block-content"><?php echo esc_html($v); ?></span>
                    </div>

                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <!-- 公共免责声明 -->
    <?php echo $this->get_disclaimer_html(); ?>

    <!-- 返回重测按钮 -->
    <div style="text-align: center; margin-top: 10px;">
        <button onclick="jQuery('.yfj-result-area').hide(); jQuery('#yfj-qiuqian-ui').fadeIn(); jQuery('.yfj-ajax-form').show();"
                style="background: #e2e8f0; color: #334155; border: none; padding: 12px 30px; border-radius: 50px; font-size: 15px; font-weight: bold; cursor: pointer; transition: all 0.2s;">
            <?php echo $this->t('返回重测'); ?>
        </button>
    </div>

</div>