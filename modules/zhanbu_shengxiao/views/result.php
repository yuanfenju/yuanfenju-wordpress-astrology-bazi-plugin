<?php
// 安全拦截
if (empty($data) || !is_array($data)) {
    echo '<div style="color:red; text-align:center; padding: 20px;">' . $this->t('暂无数据') . '</div>';
    return;
}

// 提取当前测算的类型名称（如"属鼠"），并包裹翻译引擎处理繁体等情况
$api_type = $data['运势类型'] ?? '生肖';
$type_name = esc_html($this->t($api_type));
?>

<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fff3cd; padding:12px 15px; color:#856404; margin-bottom:20px; border-radius:6px; border-left: 4px solid #ffeeba; font-size: 14px; line-height: 1.6;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<style>
    .yfj-result-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid #fecdd3; border-radius: 8px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(225,29,72,0.05); overflow: hidden; }
    .yfj-panel-heading { background: #fff1f2; padding: 14px 20px; border-bottom: 1px solid #fecdd3; font-weight: 600; font-size: 16px; color: #be123c; display: flex; align-items: center; gap: 8px; }
    .yfj-panel-body { padding: 20px; font-size: 14.5px; line-height: 1.8; color: #475569; }

    /* 顶部配对小贴士区块 */
    .yfj-ys-tips { display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 10px; margin-bottom: 20px; }
    .yfj-ys-tip-item { background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px; border-radius: 6px; text-align: center; }
    .yfj-ys-tip-title { font-size: 12px; color: #64748b; margin-bottom: 4px; }
    .yfj-ys-tip-val { font-size: 14px; font-weight: 600; color: #1e293b; }

    /* 特定颜色的提示 */
    .yfj-tip-color-red { color: #dc2626; }
    .yfj-tip-color-green { color: #16a34a; }
    .yfj-tip-color-orange { color: #d97706; }
    .yfj-tip-color-blue { color: #2563eb; }
    .yfj-tip-color-purple { color: #9333ea; }

    /* 分数与进度条 */
    .yfj-ys-score-row { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
    .yfj-ys-score-label { width: 80px; font-weight: bold; color: #334155; }
    .yfj-ys-score-bar-wrap { flex: 1; height: 10px; background: #e2e8f0; border-radius: 5px; overflow: hidden; position: relative; }
    .yfj-ys-score-bar { height: 100%; border-radius: 5px; background: #e11d48; transition: width 1s ease-in-out; }
    .yfj-ys-score-val { width: 45px; text-align: right; font-weight: bold; color: #e11d48; font-size: 14px; }

    /* 具体运势长文本 */
    .yfj-ys-text-box { background: #fff1f2; padding: 15px; border-radius: 6px; border-left: 3px solid #e11d48; margin-bottom: 20px; font-size: 14px; color: #1e293b; }
    .yfj-ys-text-title { font-size: 12px; font-weight: bold; color: #e11d48; margin-bottom: 5px; text-transform: uppercase; }
</style>

<div class="yfj-result-wrapper">

    <?php
    // 规范多语言：直接在定义时就完整包裹翻译方法，方便词条扫描工具抓取
    $time_blocks = [
        '今日运势' => $this->t('今日运势'),
        '明日运势' => $this->t('明日运势'),
        '本周运势' => $this->t('本周运势'),
        '本月运势' => $this->t('本月运势'),
        '本年运势' => $this->t('本年运势')
    ];

    foreach ($time_blocks as $block_key => $block_title_translated):
        if (!isset($data[$block_key])) continue;
        $ys_data = $data[$block_key];

        // 内部 API 字段名作为键值，不需要翻译包裹
        $main_text_key = isset($ys_data['本年运势']) ? '本年运势' :
            (isset($ys_data['本月运势']) ? '本月运势' :
                (isset($ys_data['本周运势']) ? '本周运势' : '今明运势'));
        ?>

        <div class="yfj-panel">
            <div class="yfj-panel-heading">
                <span class="dashicons dashicons-calendar-alt"></span> <?php echo $type_name . ' ' . $block_title_translated; ?>
            </div>
            <div class="yfj-panel-body">

                <!-- 顶部 5 个吉凶提示区块 (如果短属性需要转繁体，加上翻译包裹) -->
                <div class="yfj-ys-tips">
                    <div class="yfj-ys-tip-item"><div class="yfj-ys-tip-title"><?php echo $this->t('速配生肖'); ?></div><div class="yfj-ys-tip-val yfj-tip-color-red"><?php echo esc_html($this->t($ys_data['速配生肖'] ?? '-')); ?></div></div>
                    <div class="yfj-ys-tip-item"><div class="yfj-ys-tip-title"><?php echo $this->t('提防生肖'); ?></div><div class="yfj-ys-tip-val yfj-tip-color-green"><?php echo esc_html($this->t($ys_data['提防生肖'] ?? '-')); ?></div></div>
                    <div class="yfj-ys-tip-item"><div class="yfj-ys-tip-title"><?php echo $this->t('幸运颜色'); ?></div><div class="yfj-ys-tip-val yfj-tip-color-orange"><?php echo esc_html($this->t($ys_data['幸运颜色'] ?? '-')); ?></div></div>
                    <div class="yfj-ys-tip-item"><div class="yfj-ys-tip-title"><?php echo $this->t('幸运数字'); ?></div><div class="yfj-ys-tip-val yfj-tip-color-blue"><?php echo esc_html($ys_data['幸运数字'] ?? '-'); ?></div></div>
                    <div class="yfj-ys-tip-item"><div class="yfj-ys-tip-title"><?php echo $this->t('幸运宝石'); ?></div><div class="yfj-ys-tip-val yfj-tip-color-purple"><?php echo esc_html($this->t($ys_data['幸运宝石'] ?? '-')); ?></div></div>
                </div>

                <!-- 综合评分与运势正文 -->
                <div class="yfj-ys-score-row">
                    <div class="yfj-ys-score-label"><?php echo $this->t('综合指数'); ?></div>
                    <div class="yfj-ys-score-bar-wrap"><div class="yfj-ys-score-bar" style="width: <?php echo esc_attr($ys_data['综合分数'] ?? 0); ?>%; background: #be123c;"></div></div>
                    <div class="yfj-ys-score-val" style="color: #be123c;"><?php echo esc_html($ys_data['综合分数'] ?? 0); ?>%</div>
                </div>
                <div class="yfj-ys-text-box" style="border-left-color: #be123c; background: #fff1f2;">
                    <div class="yfj-ys-text-title" style="color: #be123c;"><?php echo $this->t('综合运势解析'); ?></div>
                    <?php echo esc_html($ys_data[$main_text_key] ?? ''); ?>
                </div>

                <div style="border-top: 1px dashed #e2e8f0; margin: 25px 0;"></div>

                <!-- 爱情运势 -->
                <div class="yfj-ys-score-row">
                    <div class="yfj-ys-score-label"><?php echo $this->t('爱情指数'); ?></div>
                    <div class="yfj-ys-score-bar-wrap"><div class="yfj-ys-score-bar" style="width: <?php echo esc_attr($ys_data['爱情分数'] ?? 0); ?>%; background: #ec4899;"></div></div>
                    <div class="yfj-ys-score-val" style="color: #ec4899;"><?php echo esc_html($ys_data['爱情分数'] ?? 0); ?>%</div>
                </div>
                <div class="yfj-ys-text-box" style="border-left-color: #ec4899; background: #fdf2f8;">
                    <div class="yfj-ys-text-title" style="color: #ec4899;"><?php echo $this->t('爱情运势解析'); ?></div>
                    <?php echo esc_html($ys_data['爱情运势'] ?? '-'); ?>
                </div>

                <!-- 事业运势 -->
                <div class="yfj-ys-score-row">
                    <div class="yfj-ys-score-label"><?php echo $this->t('事业指数'); ?></div>
                    <div class="yfj-ys-score-bar-wrap"><div class="yfj-ys-score-bar" style="width: <?php echo esc_attr($ys_data['事业分数'] ?? 0); ?>%; background: #0284c7;"></div></div>
                    <div class="yfj-ys-score-val" style="color: #0284c7;"><?php echo esc_html($ys_data['事业分数'] ?? 0); ?>%</div>
                </div>
                <div class="yfj-ys-text-box" style="border-left-color: #0284c7; background: #f0f9ff;">
                    <div class="yfj-ys-text-title" style="color: #0284c7;"><?php echo $this->t('事业运势解析'); ?></div>
                    <?php echo esc_html($ys_data['事业运势'] ?? '-'); ?>
                </div>

                <!-- 财富运势 -->
                <div class="yfj-ys-score-row">
                    <div class="yfj-ys-score-label"><?php echo $this->t('财富指数'); ?></div>
                    <div class="yfj-ys-score-bar-wrap"><div class="yfj-ys-score-bar" style="width: <?php echo esc_attr($ys_data['财富分数'] ?? 0); ?>%; background: #d97706;"></div></div>
                    <div class="yfj-ys-score-val" style="color: #d97706;"><?php echo esc_html($ys_data['财富分数'] ?? 0); ?>%</div>
                </div>
                <div class="yfj-ys-text-box" style="border-left-color: #d97706; background: #fffbeb;">
                    <div class="yfj-ys-text-title" style="color: #d97706;"><?php echo $this->t('财富运势解析'); ?></div>
                    <?php echo esc_html($ys_data['财富运势'] ?? '-'); ?>
                </div>

                <!-- 健康运势 -->
                <div class="yfj-ys-score-row">
                    <div class="yfj-ys-score-label"><?php echo $this->t('健康指数'); ?></div>
                    <div class="yfj-ys-score-bar-wrap"><div class="yfj-ys-score-bar" style="width: <?php echo esc_attr($ys_data['健康分数'] ?? 0); ?>%; background: #16a34a;"></div></div>
                    <div class="yfj-ys-score-val" style="color: #16a34a;"><?php echo esc_html($ys_data['健康分数'] ?? 0); ?>%</div>
                </div>
                <div class="yfj-ys-text-box" style="border-left-color: #16a34a; background: #f0fdf4; margin-bottom: 0;">
                    <div class="yfj-ys-text-title" style="color: #16a34a;"><?php echo $this->t('健康运势解析'); ?></div>
                    <?php echo esc_html($ys_data['健康运势'] ?? '-'); ?>
                </div>

            </div>
        </div>
    <?php endforeach; ?>

</div>