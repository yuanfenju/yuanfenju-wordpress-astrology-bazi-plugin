<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fff3cd; padding:12px 15px; color:#856404; margin-bottom:20px; border-radius:6px; border-left: 4px solid #ffeeba; font-size: 14px; line-height: 1.6;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<?php
// 1. 安全提取精算数据结构
$base       = $data['base_info'] ?? [];
$detail     = $data['detail_info'] ?? [];
$sizhu_info = $detail['sizhu_info'] ?? [];
$indication = $sizhu_info['sizhu_indication'] ?? [];
$xys        = $base['xiyongshen'] ?? [];
$dayun_info = $detail['dayun_info'] ?? [];

// 2. 基础变量提取 (补充了你需要的生肖、星座等变量)
$name       = $base['name'] ?? ($data['name'] ?? '未知');
$sex        = $base['sex'] ?? '未知';
$display_sex = str_replace(['乾造', '坤造'], ['男', '女'], $sex); // 把专业术语替换为男女
$sx         = $base['shengxiao'] ?? '';
$xz         = $base['xingzuo'] ?? '';
$gongli     = $base['gongli'] ?? '';
$nongli     = $base['nongli'] ?? '';

// 3. 极致体验：时间焦点引擎
$current_year = (int)date('Y');
$past_dayun   = [];
$active_dayun = []; // 包含当前和未来

if (!empty($dayun_info) && is_array($dayun_info)) {
    foreach ($dayun_info as $dy) {
        $start = (int)($dy['dayun_start_year'] ?? 0);
        $end   = $start + 9;
        if ($end < $current_year) {
            $past_dayun[] = $dy;
        } else {
            $active_dayun[] = $dy;
        }
    }
}
?>

    <style>
        .yfj-jingsuan-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }

        /* 统一的 Panel 面板样式 */
        .yfj-panel { background: #fff; border: 1px solid var(--yfj-border, #e2e8f0); border-radius: 8px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden; }
        .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid var(--yfj-border, #e2e8f0); font-weight: 600; font-size: 16px; color: var(--yfj-text-dark, #0f172a); display: flex; align-items: center; gap: 8px; }
        .yfj-panel-body { padding: 20px; font-size: 14.5px; line-height: 1.8; color: #475569; }

        /* 栅格与徽章样式 */
        .yfj-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; font-size: 14px; line-height: 1.6; }
        .yfj-info-grid strong { color: #1e293b; }
        .yfj-highlight { color: var(--yfj-primary, #c99a5b); font-weight: bold; }

        .yfj-badge-red { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 2px 8px; border-radius: 4px; font-size: 13px; font-weight: 600; }
        .yfj-badge-blue { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; padding: 2px 8px; border-radius: 4px; font-size: 13px; font-weight: 600; }
        .yfj-badge-orange { background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa; padding: 2px 8px; border-radius: 4px; font-size: 13px; font-weight: 600; }
        .yfj-badge-green { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; padding: 2px 8px; border-radius: 4px; font-size: 13px; font-weight: 600; }

        /* 大运流年动态焦点样式 */
        .yfj-fortune-section { border-left: 3px solid #e2e8f0; margin-left: 10px; padding-left: 25px; position: relative; }
        .yfj-dy-block { margin-bottom: 40px; position: relative; }
        .yfj-dy-block::before { content: ""; position: absolute; left: -32px; top: 0; width: 12px; height: 12px; border-radius: 50%; background: #cbd5e1; border: 2px solid #fff; }

        .yfj-dy-current { border-left-color: var(--yfj-primary, #c99a5b); }
        .yfj-dy-current::before { background: var(--yfj-primary, #c99a5b); transform: scale(1.3); box-shadow: 0 0 0 4px rgba(201, 154, 91, 0.1); }
        .yfj-tag-current { background: var(--yfj-primary, #c99a5b); color: #fff; font-size: 11px; padding: 2px 8px; border-radius: 4px; margin-left: 10px; }

        .yfj-ln-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px; margin-top: 15px; }
        .yfj-ln-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; transition: all 0.2s; }
        .yfj-ln-card:hover { border-color: var(--yfj-primary, #c99a5b); background: #fff; }
        .yfj-ln-current { border: 2px solid var(--yfj-primary, #c99a5b) !important; background: #fffcf8 !important; box-shadow: 0 4px 12px rgba(201,154,91,0.08); }

        .yfj-past-toggle { background: #f1f5f9; color: #64748b; padding: 10px; text-align: center; border-radius: 8px; cursor: pointer; margin-bottom: 20px; font-size: 13px; font-weight: bold; transition: background 0.2s; }
        .yfj-past-toggle:hover { background: #e2e8f0; }
    </style>

    <div class="yfj-jingsuan-wrapper">

        <!-- 1. 基本信息 (精算版更详细) -->
        <div class="yfj-panel">
            <div class="yfj-panel-heading">
                <span class="dashicons dashicons-id-alt"></span> <?php echo $this->t('基本信息'); ?>
            </div>
            <div class="yfj-panel-body yfj-info-grid">
                <div><strong><?php echo $this->t('命主姓名：'); ?></strong> <?php echo esc_html($name); ?> &nbsp;|&nbsp; <strong><?php echo $this->t('性别：'); ?></strong> <?php echo esc_html($display_sex); ?></div>
                <div><strong><?php echo $this->t('生肖星座：'); ?></strong> <?php echo $this->t('肖'); ?><?php echo esc_html($sx); ?> / <?php echo esc_html($xz); ?><?php echo $this->t('座'); ?></div>
                <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('出生公历：'); ?></strong> <?php echo esc_html($gongli); ?></div>
                <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('出生农历：'); ?></strong> <?php echo esc_html($nongli); ?></div>

                <div style="grid-column: 1 / -1; margin-top: 5px; padding-top: 10px; border-top: 1px dashed #e2e8f0;">
                    <strong><?php echo $this->t('四柱八字：'); ?></strong><br>
                    <span class="yfj-badge-red"><?php echo esc_html($sizhu_info['year']['tg'] ?? ''); ?><?php echo esc_html($sizhu_info['year']['dz'] ?? ''); ?><?php echo $this->t('年'); ?> (<?php echo esc_html($sizhu_info['year']['ny'] ?? ''); ?>)</span> &nbsp;
                    <span class="yfj-badge-blue"><?php echo esc_html($sizhu_info['month']['tg'] ?? ''); ?><?php echo esc_html($sizhu_info['month']['dz'] ?? ''); ?><?php echo $this->t('月'); ?> (<?php echo esc_html($sizhu_info['month']['ny'] ?? ''); ?>)</span> &nbsp;
                    <span class="yfj-badge-orange"><?php echo esc_html($sizhu_info['day']['tg'] ?? ''); ?><?php echo esc_html($sizhu_info['day']['dz'] ?? ''); ?><?php echo $this->t('日'); ?> (<?php echo esc_html($sizhu_info['day']['ny'] ?? ''); ?>)</span> &nbsp;
                    <span class="yfj-badge-green"><?php echo esc_html($sizhu_info['hour']['tg'] ?? ''); ?><?php echo esc_html($sizhu_info['hour']['dz'] ?? ''); ?><?php echo $this->t('时'); ?> (<?php echo esc_html($sizhu_info['hour']['ny'] ?? ''); ?>)</span>
                </div>

                <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('胎元命身：'); ?></strong>
                    <?php echo $this->t('胎息:'); ?><?php echo esc_html($base['taixi'] ?? ''); ?>(<?php echo esc_html($base['taixi_nayin'] ?? ''); ?>) &nbsp;
                    <?php echo $this->t('胎元:'); ?><?php echo esc_html($base['taiyuan'] ?? ''); ?>(<?php echo esc_html($base['taiyuan_nayin'] ?? ''); ?>) &nbsp;
                    <?php echo $this->t('命宫:'); ?><?php echo esc_html($base['minggong'] ?? ''); ?>(<?php echo esc_html($base['minggong_nayin'] ?? ''); ?>) &nbsp;
                    <?php echo $this->t('身宫:'); ?><?php echo esc_html($base['shengong'] ?? ''); ?>(<?php echo esc_html($base['shengong_nayin'] ?? ''); ?>)
                </div>

                <div><strong><?php echo $this->t('星宿信息：'); ?></strong> <?php echo esc_html($base['xingxiu'] ?? '-'); ?></div>
                <div><strong><?php echo $this->t('命卦信息：'); ?></strong> <?php echo esc_html($base['minggua']['minggua_name'] ?? ''); ?><?php echo $this->t('卦'); ?> (<?php echo esc_html($base['minggua']['minggua_fangwei'] ?? ''); ?>)</div>
                <div><strong><?php echo $this->t('年柱纳音：'); ?></strong> <?php echo esc_html($sizhu_info['year']['ny'] ?? ''); ?><?php echo $this->t('命'); ?> (<?php echo $this->t('司令:'); ?><?php echo esc_html($base['siling'] ?? ''); ?>)</div>
                <div><strong><?php echo $this->t('五行旺度：'); ?></strong> <?php echo esc_html($base['wuxing_wangdu'] ?? '-'); ?></div>
                <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('起运交运：'); ?></strong> <?php echo esc_html($base['qiyun'] ?? ''); ?> / <?php echo esc_html($base['jiaoyun_mang'] ?? ''); ?></div>
                <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('天干留意：'); ?></strong> <?php echo esc_html($base['tiangan_liuyi'] ?? '-'); ?></div>
                <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('地支留意：'); ?></strong> <?php echo esc_html($base['dizhi_liuyi'] ?? '-'); ?></div>
            </div>
        </div>

        <!-- 2. 喜用神深度分析 -->
        <div class="yfj-panel">
            <div class="yfj-panel-heading">
                <span class="dashicons dashicons-chart-pie"></span> <?php echo $this->t('喜用神与五行能量深度分析'); ?>
            </div>
            <div class="yfj-panel-body">
                <p>
                    <?php echo $this->t('日主天干为'); ?> <span class="yfj-highlight"><?php echo esc_html($xys['rizhu_tiangan'] ?? '-'); ?></span>，
                    <?php echo $this->t('同类为'); ?> <span class="yfj-highlight"><?php echo esc_html($xys['tonglei'] ?? '-'); ?></span>，
                    <?php echo $this->t('异类为'); ?> <span class="yfj-highlight"><?php echo esc_html($xys['yilei'] ?? '-'); ?></span>。
                </p>
                <p>
                    <span class="yfj-badge-red"><?php echo esc_html($xys['qiangruo'] ?? '-'); ?></span>，
                    <?php echo $this->t('以'); ?> <span class="yfj-badge-red"><?php echo esc_html($xys['xiyongshen'] ?? '-'); ?></span> <?php echo $this->t('为喜用神'); ?>。
                    <strong><?php echo $this->t('阴阳参考：'); ?></strong> <?php echo esc_html($xys['yinyang'] ?? '-'); ?>
                </p>

                <div style="background: #f8fafc; padding: 12px; border-radius: 6px; margin-top: 15px; border-left: 3px solid #cbd5e1;">
                    <strong><?php echo $this->t('五行个数：'); ?></strong>
                    <?php echo esc_html($xys['jin_number'] ?? '0'); ?><?php echo $this->t('金'); ?>，
                    <?php echo esc_html($xys['mu_number'] ?? '0'); ?><?php echo $this->t('木'); ?>，
                    <?php echo esc_html($xys['shui_number'] ?? '0'); ?><?php echo $this->t('水'); ?>，
                    <?php echo esc_html($xys['huo_number'] ?? '0'); ?><?php echo $this->t('火'); ?>，
                    <?php echo esc_html($xys['tu_number'] ?? '0'); ?><?php echo $this->t('土'); ?>。
                    <br>
                    <strong><?php echo $this->t('党派分布：'); ?></strong> <?php echo $this->t('自党：'); ?><?php echo esc_html($xys['zidang'] ?? '0'); ?>，<?php echo $this->t('异党：'); ?><?php echo esc_html($xys['yidang'] ?? '0'); ?>
                </div>

                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 12px; border-radius: 6px; margin-top: 10px;">
                    <strong><?php echo $this->t('五行能量打分：'); ?></strong><br>
                    <?php echo $this->t('金：'); ?><?php echo esc_html($xys['jin_score'] ?? '0'); ?><?php echo $this->t('分'); ?> (<?php echo $this->t('占比'); ?><?php echo esc_html($xys['jin_score_percent'] ?? '0%'); ?>) |
                    <?php echo $this->t('木：'); ?><?php echo esc_html($xys['mu_score'] ?? '0'); ?><?php echo $this->t('分'); ?> (<?php echo $this->t('占比'); ?><?php echo esc_html($xys['mu_score_percent'] ?? '0%'); ?>) |
                    <?php echo $this->t('水：'); ?><?php echo esc_html($xys['shui_score'] ?? '0'); ?><?php echo $this->t('分'); ?> (<?php echo $this->t('占比'); ?><?php echo esc_html($xys['shui_score_percent'] ?? '0%'); ?>) <br>
                    <?php echo $this->t('火：'); ?><?php echo esc_html($xys['huo_score'] ?? '0'); ?><?php echo $this->t('分'); ?> (<?php echo $this->t('占比'); ?><?php echo esc_html($xys['huo_score_percent'] ?? '0%'); ?>) |
                    <?php echo $this->t('土：'); ?><?php echo esc_html($xys['tu_score'] ?? '0'); ?><?php echo $this->t('分'); ?> (<?php echo $this->t('占比'); ?><?php echo esc_html($xys['tu_score_percent'] ?? '0%'); ?>)
                </div>
            </div>
        </div>

        <!-- 3. 日柱论命 & 先天纳音 & 能量五行 -->
        <div class="yfj-panel">
            <div class="yfj-panel-heading">
                <span class="dashicons dashicons-buddicons-topics"></span> <?php echo $this->t('日柱、五行与纳音解析'); ?>
            </div>
            <div class="yfj-panel-body">
                <p><strong>【<?php echo $this->t('日柱论命'); ?>】</strong><br> <?php echo esc_html($indication['xingge']['rizhu'] ?? $this->t('暂无数据')); ?></p>
                <div style="border-top: 1px dashed #e2e8f0; margin: 15px 0;"></div>
                <p><strong>【<?php echo $this->t('先天纳音'); ?>】</strong><br> <?php echo esc_html($indication['wuxing']['detail_desc'] ?? ''); ?> <?php echo esc_html($indication['wuxing']['detail_description'] ?? ''); ?></p>
                <div style="border-top: 1px dashed #e2e8f0; margin: 15px 0;"></div>
                <p><strong>【<?php echo $this->t('能量五行'); ?>】</strong><br> <?php echo esc_html($indication['wuxing']['simple_description'] ?? ''); ?></p>
            </div>
        </div>

        <!-- 4. 财运与姻缘 -->
        <div class="yfj-panel">
            <div class="yfj-panel-heading">
                <span class="dashicons dashicons-heart"></span> <?php echo $this->t('财运与姻缘分析'); ?>
            </div>
            <div class="yfj-panel-body">
                <p><strong>【<?php echo $this->t('财运分析'); ?>】</strong><br>
                    <span class="yfj-badge-red" style="font-size: 15px; margin-right: 8px;"><?php echo esc_html($indication['caiyun']['sanshishu_caiyun']['simple_desc'] ?? ''); ?></span>
                    <?php echo esc_html($indication['caiyun']['sanshishu_caiyun']['detail_desc'] ?? ''); ?>
                </p>
                <div style="border-top: 1px dashed #e2e8f0; margin: 15px 0;"></div>
                <p><strong>【<?php echo $this->t('姻缘分析'); ?>】</strong><br>
                    <?php echo esc_html($indication['yinyuan']['sanshishu_yinyuan'] ?? $this->t('暂无数据')); ?>
                </p>
            </div>
        </div>

        <!-- 5. 总体命运 -->
        <div class="yfj-panel">
            <div class="yfj-panel-heading">
                <span class="dashicons dashicons-star-filled"></span> <?php echo $this->t('一生总体命运与运程'); ?>
            </div>
            <div class="yfj-panel-body">
                <p><strong>【<?php echo $this->t('运程概括'); ?>】</strong><br> <?php echo esc_html($indication['chenggu']['description'] ?? ''); ?></p>
                <div style="border-top: 1px dashed #e2e8f0; margin: 15px 0;"></div>
                <p><strong>【<?php echo $this->t('命运总批'); ?>】</strong><br> <?php echo esc_html($indication['mingyun']['sanshishu_mingyun'] ?? ''); ?></p>
            </div>
        </div>

        <!-- 6. 十年大运与流年详批 (核心重头戏) -->
        <div class="yfj-panel">
            <div class="yfj-panel-heading">
                <span class="dashicons dashicons-chart-line"></span> <?php echo $this->t('人生运势焦点解析'); ?>
                <span style="font-size:12px; font-weight:normal; color:#64748b; margin-left:auto;"><?php echo $this->t('基于当前时间智能优先展示'); ?></span>
            </div>
            <div class="yfj-panel-body">

                <?php if (!empty($past_dayun)): ?>
                    <div class="yfj-past-toggle" onclick="jQuery('#yfj-past-fortunes').slideToggle();">
                        <span class="dashicons dashicons-backup" style="vertical-align: middle;"></span>
                        <?php echo $this->t('展开往昔运势 (共'); ?> <?php echo count($past_dayun); ?> <?php echo $this->t('个大运)'); ?>
                        <span class="dashicons dashicons-arrow-down-alt2" style="vertical-align: middle;"></span>
                    </div>
                    <div id="yfj-past-fortunes" style="display: none; opacity: 0.75;">
                        <?php render_dayun_list($past_dayun, $current_year, $this); ?>
                    </div>
                    <div style="border-top: 2px dashed #cbd5e1; margin: 30px 0;"></div>
                <?php endif; ?>

                <div class="yfj-fortune-section">
                    <?php render_dayun_list($active_dayun, $current_year, $this); ?>
                </div>

            </div>
        </div>

        <?php echo $this->get_disclaimer_html(); ?>

        <div style="text-align: center; margin-top: 30px;">
            <button onclick="jQuery('.yfj-result-area').hide(); jQuery('.yfj-ajax-form').fadeIn();"
                    style="background: #e2e8f0; color: #334155; border: none; padding: 10px 24px; border-radius: 6px; font-weight: bold; cursor: pointer;">
                <?php echo $this->t('返回重排'); ?>
            </button>
        </div>

    </div>

<?php
/**
 * 内部渲染函数：大运列表 (动态渲染)
 */
function render_dayun_list($dayun_list, $current_year, $plugin) {
    if (empty($dayun_list) || !is_array($dayun_list)) {
        echo '<p>' . $plugin->t('暂无大运流年数据') . '</p>';
        return;
    }

    foreach ($dayun_list as $dy):
        $start = (int)($dy['dayun_start_year'] ?? 0);
        $end   = $start + 9;
        $is_current_dy = ($current_year >= $start && $current_year <= $end);
        ?>
        <div class="yfj-dy-block <?php echo $is_current_dy ? 'yfj-dy-current' : ''; ?>">
            <div style="font-size: 18px; font-weight: bold; color: #1e293b; margin-bottom: 10px;">
                <?php echo $start; ?> - <?php echo $end; ?> <?php echo $plugin->t('大运：'); ?>
                <span style="color: var(--yfj-primary, #c99a5b);"><?php echo esc_html($dy['dayun_ganzhi'] ?? ''); ?></span>
                <?php if ($is_current_dy): ?>
                    <span class="yfj-tag-current"><?php echo $plugin->t('现行大运'); ?></span>
                <?php endif; ?>
            </div>

            <div style="background: #fdfaf6; padding: 15px; border-radius: 8px; border-left: 3px solid #fbd38d; font-size: 14px; margin-bottom: 20px;">
                <strong style="color: #b45309; font-size: 15px; display: inline-block; margin-bottom: 8px;"><?php echo $plugin->t('本届大运概论：'); ?></strong><br>
                <div style="line-height: 1.8; color: #475569;">
                    <?php
                    $dy_ind = $dy['dayun_indication'] ?? '';

                    if (is_array($dy_ind)) {
                        // 1. 如果接口返回的是数组，则优雅地分类输出
                        if (!empty($dy_ind['shiye']))  echo '<div style="margin-bottom:4px;"><span style="font-weight:bold; color:#059669;">['.$plugin->t('大运·事业').']</span> ' . esc_html($dy_ind['shiye']) . '</div>';
                        if (!empty($dy_ind['xueye'])) echo '<div style="margin-bottom:4px;"><span style="font-weight:bold; color:#1e293b;">['.$plugin->t('大运·学业').']</span> ' . esc_html($dy_ind['xueye']) . '</div>';
                        if (!empty($dy_ind['caiyun'])) echo '<div style="margin-bottom:4px;"><span style="font-weight:bold; color:#d97706;">['.$plugin->t('大运·财运').']</span> ' . esc_html($dy_ind['caiyun']) . '</div>';
                        if (!empty($dy_ind['yinyuan']))echo '<div style="margin-bottom:4px;"><span style="font-weight:bold; color:#db2777;">['.$plugin->t('大运·姻缘').']</span> ' . esc_html($dy_ind['yinyuan']) . '</div>';
                        if (!empty($dy_ind['jiankang']))echo '<div style="margin-bottom:4px;"><span style="font-weight:bold; color:#4f46e5;">['.$plugin->t('大运·健康').']</span> ' . esc_html($dy_ind['jiankang']) . '</div>';
                        if (!empty($dy_ind['yunshi'])) echo '<div style="margin-bottom:4px;"><span style="font-weight:bold; color:#1e293b;">['.$plugin->t('大运·总运').']</span> ' . esc_html($dy_ind['yunshi']) . '</div>';

                        // 兜底机制：如果 API 增加了其他未知的 key，直接拼接输出，防止漏掉信息
                        if (empty($dy_ind['yunshi']) && empty($dy_ind['shiye']) && empty($dy_ind['caiyun'])) {
                            foreach($dy_ind as $val) {
                                if (is_string($val)) echo esc_html($val) . '<br>';
                            }
                        }
                    } else {
                        // 2. 如果接口某天更新改成了直接返回字符串，兼容输出
                        echo esc_html($dy_ind ?: '-');
                    }
                    ?>
                </div>
            </div>

            <div class="yfj-ln-grid">
                <?php
                $liunian_info = $dy['liunian_info'] ?? [];
                if (is_array($liunian_info)):
                    foreach ($liunian_info as $ln):
                        $ln_year = (int)($ln['liunian_year'] ?? 0);
                        $is_current_ln = ($ln_year === $current_year);
                        // 极致体验：过去大运里的流年就不高亮当前年了
                        if ($ln_year < $current_year - 10) $is_current_ln = false;
                        ?>
                        <div class="yfj-ln-card <?php echo $is_current_ln ? 'yfj-ln-current' : ''; ?>">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">
                                <strong style="font-size: 15px;"><?php echo $ln_year; ?> <?php echo esc_html($ln['liunian_ganzhi'] ?? ''); ?></strong>
                                <?php if ($is_current_ln): ?>
                                    <span style="color: var(--yfj-primary, #c99a5b); font-size: 12px; font-weight: bold;">📍 <?php echo $plugin->t('流年：'); ?></span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 13px; color: #475569; display: flex; flex-direction: column; gap: 8px;">
                                <div><span style="color:#059669; font-weight:600;">[<?php echo $plugin->t('事业'); ?>]</span> <?php echo esc_html($ln['liunian_indication']['shiye'] ?? '-'); ?></div>
                                <div><span style="color:#2563eb; font-weight:600;">[<?php echo $plugin->t('学业'); ?>]</span> <?php echo esc_html($ln['liunian_indication']['xueye'] ?? '-'); ?></div>
                                <div><span style="color:#d97706; font-weight:600;">[<?php echo $plugin->t('财运'); ?>]</span> <?php echo esc_html($ln['liunian_indication']['caiyun'] ?? '-'); ?></div>
                                <div><span style="color:#db2777; font-weight:600;">[<?php echo $plugin->t('姻缘'); ?>]</span> <?php echo esc_html($ln['liunian_indication']['yinyuan'] ?? '-'); ?></div>
                                <div><span style="color:#7c3aed; font-weight:600;">[<?php echo $plugin->t('健康'); ?>]</span> <?php echo esc_html($ln['liunian_indication']['jiankang'] ?? '-'); ?></div>
                                <div style="margin-top: 5px; padding-top: 5px; border-top: 1px dashed #e2e8f0; color: #1e293b;">
                                    <strong>💡 <?php echo $plugin->t('总运'); ?></strong> <?php echo esc_html($ln['liunian_indication']['yunshi'] ?? '-'); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    <?php endforeach;
}
?>