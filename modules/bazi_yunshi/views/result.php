<?php
// 安全拦截
if (empty($data) || !is_array($data)) {
    echo '<div style="color:red; text-align:center; padding: 20px;">' . $this->t('暂无数据') . '</div>';
    return;
}

// 提取各个数据区块，增加容错
$base = $data['base_info'] ?? [];
$xys  = $base['xiyongshen'] ?? [];
$ys   = $data['yunshi_info'] ?? [];

// 拼装八字
$bazi_str = trim(($base['yeargz'] ?? '') . ' ' . ($base['monthgz'] ?? '') . ' ' . ($base['daygz'] ?? '') . ' ' . ($base['hourgz'] ?? ''));
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
    .yfj-panel { background: #fff; border: 1px solid var(--yfj-border, #e2e8f0); border-radius: 8px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid var(--yfj-border, #e2e8f0); font-weight: 600; font-size: 16px; color: var(--yfj-text-dark, #0f172a); display: flex; align-items: center; gap: 8px; }
    .yfj-panel-body { padding: 20px; font-size: 14.5px; line-height: 1.8; color: #475569; }

    /* 基础信息网格 */
    .yfj-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; font-size: 14px; line-height: 1.6; }
    .yfj-info-grid strong { color: #1e293b; }

    /* ================= 新的运势贴士布局 ================= */
    .yfj-ys-tips-wrapper { margin-bottom: 25px; }

    /* 顶部 5个短贴士：自适应平铺 */
    .yfj-ys-tips-top { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 10px; }
    .yfj-ys-tip-small { flex: 1; min-width: 100px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px 8px; border-radius: 6px; text-align: center; }
    .yfj-ys-tip-title { font-size: 12px; color: #64748b; margin-bottom: 4px; }
    .yfj-ys-tip-val { font-size: 14px; font-weight: 600; color: #1e293b; }

    /* 底部 3个长贴士：条状列表排布 */
    .yfj-ys-tips-bottom { display: flex; flex-direction: column; gap: 10px; }
    .yfj-ys-tip-large { display: flex; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; background: #f8fafc; }
    .yfj-ys-tip-large-label { width: 85px; min-width: 85px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 13px; color: #475569; font-weight: bold; border-right: 1px solid #e2e8f0; text-align: center; line-height: 1.3; }
    .yfj-ys-tip-large-val { flex: 1; padding: 10px 15px; font-size: 14px; font-weight: 500; color: #1e293b; }
    /* ================================================= */

    /* 特定颜色的提示 */
    .yfj-tip-color-red { color: #dc2626; }
    .yfj-tip-color-green { color: #16a34a; }
    .yfj-tip-color-orange { color: #d97706; }
    .yfj-tip-color-blue { color: #2563eb; }
    .yfj-tip-color-purple { color: #9333ea; }
    .yfj-tip-color-teal { color: #0d9488; }
    .yfj-tip-color-rose { color: #e11d48; }

    /* 分数与进度条 */
    .yfj-ys-score-row { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
    .yfj-ys-score-label { width: 80px; font-weight: bold; color: #334155; }
    .yfj-ys-score-bar-wrap { flex: 1; height: 10px; background: #e2e8f0; border-radius: 5px; overflow: hidden; position: relative; }
    .yfj-ys-score-bar { height: 100%; border-radius: 5px; transition: width 1s ease-in-out; }
    .yfj-ys-score-val { width: 45px; text-align: right; font-weight: bold; font-size: 14px; }

    /* 具体运势长文本 */
    .yfj-ys-text-box { padding: 15px; border-radius: 6px; border-left: 3px solid; margin-bottom: 20px; font-size: 14px; color: #1e293b; }
    .yfj-ys-text-title { font-size: 12px; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }

    .yfj-badge-red { color: #dc2626; font-weight: 600; }
    .yfj-highlight { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: #0f172a; font-weight: 500; }
</style>

<div class="yfj-result-wrapper">

    <!-- 1. 基本信息 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-id-alt"></span> <?php echo $this->t('基本信息'); ?>
        </div>
        <div class="yfj-panel-body yfj-info-grid">
            <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('命主姓名：'); ?></strong> <?php echo esc_html($base['name'] ?? ''); ?></div>
            <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('出生公历：'); ?></strong> <?php echo esc_html($base['gongli'] ?? ''); ?></div>
            <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('出生农历：'); ?></strong> <?php echo esc_html($base['nongli'] ?? ''); ?></div>

            <div style="grid-column: 1 / -1; margin-top: 5px; padding-top: 10px; border-top: 1px dashed #e2e8f0;">
                <strong><?php echo $this->t('四柱八字：'); ?></strong> <span class="yfj-badge-red" style="font-size: 16px;"><?php echo esc_html($bazi_str); ?></span>
            </div>
            <div style="grid-column: 1 / -1;">
                <strong><?php echo $this->t('命理格局：'); ?></strong> <span class="yfj-highlight"><?php echo esc_html($base['zhengge'] ?? ''); ?></span>
            </div>
        </div>
    </div>

    <!-- 2. 喜用神分析 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-chart-pie"></span> <?php echo $this->t('喜用神分析'); ?>
        </div>
        <div class="yfj-panel-body">
            <p>
                <?php echo $this->t('日主天干为'); ?> <span class="yfj-highlight"><?php echo esc_html($xys['rizhu_tiangan'] ?? '-'); ?></span>，
                <?php echo $this->t('同类为'); ?> <span class="yfj-highlight"><?php echo esc_html($xys['tonglei'] ?? '-'); ?></span>，
                <?php echo $this->t('异类为'); ?> <span class="yfj-highlight"><?php echo esc_html($xys['yilei'] ?? '-'); ?></span>。
            </p>
            <p>
                <span class="yfj-badge-red"><?php echo esc_html($this->t($xys['qiangruo'] ?? '-')); ?></span>，
                <?php echo $this->t('以'); ?> <span class="yfj-badge-red"><?php echo esc_html($xys['xiyongshen'] ?? '-'); ?></span> <?php echo $this->t('为喜用神'); ?>。
                <?php echo $this->t('以'); ?> <span class="yfj-highlight"><?php echo esc_html($xys['jishen'] ?? '-'); ?></span> <?php echo $this->t('为忌神'); ?>。
            </p>
            <div style="background: #f8fafc; padding: 12px; border-radius: 6px; margin-top: 15px; border-left: 3px solid #cbd5e1;">
                <strong><?php echo $this->t('五行参考：'); ?></strong> <?php echo esc_html($xys['xiyongshen_desc'] ?? ''); ?>
            </div>
            <div style="margin-top: 10px;">
                <strong><?php echo $this->t('五行统计：'); ?></strong>
                <?php echo esc_html($xys['jin_number'] ?? '0'); ?><?php echo $this->t('金'); ?>，
                <?php echo esc_html($xys['mu_number'] ?? '0'); ?><?php echo $this->t('木'); ?>，
                <?php echo esc_html($xys['shui_number'] ?? '0'); ?><?php echo $this->t('水'); ?>，
                <?php echo esc_html($xys['huo_number'] ?? '0'); ?><?php echo $this->t('火'); ?>，
                <?php echo esc_html($xys['tu_number'] ?? '0'); ?><?php echo $this->t('土'); ?>
            </div>
        </div>
    </div>

    <!-- 3. 当日运势分析 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-calendar-alt" style="color: #6366f1;"></span> <?php echo $this->t('当日运势深度分析'); ?>
        </div>
        <div class="yfj-panel-body">

            <!-- 全新的运势贴士排版 -->
            <div class="yfj-ys-tips-wrapper">
                <!-- 上半部分：短数据 -->
                <div class="yfj-ys-tips-top">
                    <div class="yfj-ys-tip-small"><div class="yfj-ys-tip-title"><?php echo $this->t('幸运数字'); ?></div><div class="yfj-ys-tip-val yfj-tip-color-red"><?php echo esc_html($ys['lucky_number'] ?? '-'); ?></div></div>
                    <div class="yfj-ys-tip-small"><div class="yfj-ys-tip-title"><?php echo $this->t('幸运颜色'); ?></div><div class="yfj-ys-tip-val yfj-tip-color-green"><?php echo esc_html($this->t($ys['lucky_color'] ?? '-')); ?></div></div>
                    <div class="yfj-ys-tip-small"><div class="yfj-ys-tip-title"><?php echo $this->t('幸运首饰'); ?></div><div class="yfj-ys-tip-val yfj-tip-color-orange"><?php echo esc_html($this->t($ys['lucky_accessory'] ?? '-')); ?></div></div>
                    <div class="yfj-ys-tip-small"><div class="yfj-ys-tip-title"><?php echo $this->t('开运宜食'); ?></div><div class="yfj-ys-tip-val yfj-tip-color-blue"><?php echo esc_html($this->t($ys['lucky_foods'] ?? '-')); ?></div></div>
                    <div class="yfj-ys-tip-small"><div class="yfj-ys-tip-title"><?php echo $this->t('幸运方位'); ?></div><div class="yfj-ys-tip-val yfj-tip-color-purple"><?php echo esc_html($this->t($ys['lucky_directions'] ?? '-')); ?></div></div>
                </div>

                <!-- 下半部分：长数据 -->
                <div class="yfj-ys-tips-bottom">
                    <div class="yfj-ys-tip-large">
                        <div class="yfj-ys-tip-large-label"><?php echo $this->t('今日适宜'); ?></div>
                        <div class="yfj-ys-tip-large-val yfj-tip-color-teal"><?php echo esc_html($ys['lucky_yi'] ?? '-'); ?></div>
                    </div>
                    <div class="yfj-ys-tip-large">
                        <div class="yfj-ys-tip-large-label"><?php echo $this->t('今日不宜'); ?></div>
                        <div class="yfj-ys-tip-large-val" style="color: #475569;"><?php echo esc_html($ys['lucky_ji'] ?? '-'); ?></div>
                    </div>
                    <div class="yfj-ys-tip-large" style="border-color: #fecdd3; background: #fff1f2;">
                        <div class="yfj-ys-tip-large-label" style="background: #ffe4e6; color: #be123c; border-right-color: #fecdd3;"><?php echo $this->t('综合吉凶'); ?></div>
                        <div class="yfj-ys-tip-large-val yfj-tip-color-rose"><?php echo esc_html($this->t($ys['jixiong_today'] ?? '-')); ?></div>
                    </div>
                </div>
            </div>

            <!-- 综合运势分数与解析 -->
            <div class="yfj-ys-score-row">
                <div class="yfj-ys-score-label"><?php echo $this->t('运势分数'); ?></div>
                <div class="yfj-ys-score-bar-wrap"><div class="yfj-ys-score-bar" style="width: <?php echo esc_attr($ys['fortune_score'] ?? 0); ?>%; background: #6366f1;"></div></div>
                <div class="yfj-ys-score-val" style="color: #6366f1;"><?php echo esc_html($ys['fortune_score'] ?? 0); ?>%</div>
            </div>
            <div class="yfj-ys-text-box" style="border-left-color: #6366f1; background: #eef2ff;">
                <div class="yfj-ys-text-title" style="color: #6366f1;"><?php echo $this->t('今日运势解析'); ?></div>
                <?php echo esc_html($ys['fortune_description'] ?? ''); ?>
            </div>

            <div style="border-top: 1px dashed #e2e8f0; margin: 25px 0;"></div>

            <!-- 爱情运势 -->
            <div class="yfj-ys-score-row">
                <div class="yfj-ys-score-label"><?php echo $this->t('爱情分数'); ?></div>
                <div class="yfj-ys-score-bar-wrap"><div class="yfj-ys-score-bar" style="width: <?php echo esc_attr($ys['love_score'] ?? 0); ?>%; background: #e11d48;"></div></div>
                <div class="yfj-ys-score-val" style="color: #e11d48;"><?php echo esc_html($ys['love_score'] ?? 0); ?>%</div>
            </div>
            <div class="yfj-ys-text-box" style="border-left-color: #e11d48; background: #fff1f2;">
                <div class="yfj-ys-text-title" style="color: #e11d48;"><?php echo $this->t('爱情运势解析'); ?></div>
                <?php echo esc_html($ys['love_description'] ?? ''); ?>
            </div>

            <!-- 事业运势 -->
            <div class="yfj-ys-score-row">
                <div class="yfj-ys-score-label"><?php echo $this->t('事业分数'); ?></div>
                <div class="yfj-ys-score-bar-wrap"><div class="yfj-ys-score-bar" style="width: <?php echo esc_attr($ys['career_score'] ?? 0); ?>%; background: #0ea5e9;"></div></div>
                <div class="yfj-ys-score-val" style="color: #0ea5e9;"><?php echo esc_html($ys['career_score'] ?? 0); ?>%</div>
            </div>
            <div class="yfj-ys-text-box" style="border-left-color: #0ea5e9; background: #f0f9ff;">
                <div class="yfj-ys-text-title" style="color: #0ea5e9;"><?php echo $this->t('事业运势解析'); ?></div>
                <?php echo esc_html($ys['career_description'] ?? ''); ?>
            </div>

            <!-- 财富运势 -->
            <div class="yfj-ys-score-row">
                <div class="yfj-ys-score-label"><?php echo $this->t('财富分数'); ?></div>
                <div class="yfj-ys-score-bar-wrap"><div class="yfj-ys-score-bar" style="width: <?php echo esc_attr($ys['wealth_score'] ?? 0); ?>%; background: #d97706;"></div></div>
                <div class="yfj-ys-score-val" style="color: #d97706;"><?php echo esc_html($ys['wealth_score'] ?? 0); ?>%</div>
            </div>
            <div class="yfj-ys-text-box" style="border-left-color: #d97706; background: #fffbeb;">
                <div class="yfj-ys-text-title" style="color: #d97706;"><?php echo $this->t('财富运势解析'); ?></div>
                <?php echo esc_html($ys['wealth_description'] ?? ''); ?>
            </div>

            <!-- 健康运势 -->
            <div class="yfj-ys-score-row">
                <div class="yfj-ys-score-label"><?php echo $this->t('健康分数'); ?></div>
                <div class="yfj-ys-score-bar-wrap"><div class="yfj-ys-score-bar" style="width: <?php echo esc_attr($ys['health_score'] ?? 0); ?>%; background: #16a34a;"></div></div>
                <div class="yfj-ys-score-val" style="color: #16a34a;"><?php echo esc_html($ys['health_score'] ?? 0); ?>%</div>
            </div>
            <div class="yfj-ys-text-box" style="border-left-color: #16a34a; background: #f0fdf4; margin-bottom: 0;">
                <div class="yfj-ys-text-title" style="color: #16a34a;"><?php echo $this->t('健康运势解析'); ?></div>
                <?php echo esc_html($ys['health_description'] ?? ''); ?>
            </div>

        </div>
    </div>

    <!-- 测算告诫，免责声明 -->
    <?php echo $this->get_disclaimer_html(); ?>

    <!-- 返回按钮 -->
    <div style="text-align: center; margin-top: 10px;">
        <button onclick="jQuery('.yfj-result-area').hide(); jQuery('.yfj-ajax-form').fadeIn();"
                style="background: #e2e8f0; color: #334155; border: none; padding: 10px 24px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            <?php echo $this->t('返回重测'); ?>
        </button>
    </div>

</div>