<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fff3cd; padding:12px 15px; color:#856404; margin-bottom:20px; border-radius:6px; border-left: 4px solid #ffeeba; font-size: 14px; line-height: 1.6;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<?php
// ==========================================
// 1. 数据精准提取
// ==========================================
$base       = $data['base_info'] ?? [];
$person_a   = $base['person_a'] ?? [];
$person_b   = $base['person_b'] ?? [];
$detail     = $data['detail_info'] ?? [];
$chart_data = $detail['chart_data'] ?? [];
$chart_desc = $detail['chart_description'] ?? [];

// 本命与互动数据
$a_natal    = $chart_data['person_a_natal'] ?? [];
$b_natal    = $chart_data['person_b_natal'] ?? [];
$synastry   = $chart_data['synastry'] ?? [];

// SVG 清洗
$raw_svg    = $detail['chart_svg'] ?? [];
$svg_a_in_b = preg_replace('/<\?xml.*?\?>/is', '', $raw_svg['a_in_b'] ?? '');
$svg_b_in_a = preg_replace('/<\?xml.*?\?>/is', '', $raw_svg['b_in_a'] ?? '');

// 🌐 动态语言后缀拾取 (从本命盘完美移植)
$lang_opt = get_option('yfj_language', 'zh-cn');
$sf = 'chinese';

if ($lang_opt === 'zh-tw') {
    $sf = 'chinese_traditional';
} elseif ($lang_opt === 'en-us') {
    $sf = 'english';
}
?>

    <style>
        .yfj-synastry-result { font-family: -apple-system, sans-serif; color: #334155; line-height: 1.6; }

        /* 双方信息头部 */
        .yfj-info-header { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
        .yfj-info-box { padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; }

        /* SVG 容器：强制约束 */
        .yfj-svg-wrap { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; text-align: center; margin-bottom: 25px; }
        .yfj-svg-item { display: inline-block; width: 100%; max-width: 580px; height: auto; }
        .yfj-svg-item svg { width: 100%; height: auto; display: block; }

        /* 视角切换按钮 */
        .yfj-view-tabs { display: flex; justify-content: center; margin-bottom: 25px; gap: 4px; background: #f1f5f9; border-radius: 8px; padding: 6px; }
        .yfj-tab-btn { flex: 1; padding: 12px; border: none; cursor: pointer; font-weight: bold; background: transparent; color: #64748b; transition: all 0.3s; border-radius: 6px; }
        .yfj-tab-btn.active { background: #fff; color: #ff4c00; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }

        /* 硬核数据表统一样式 */
        .yfj-data-section { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 20px; overflow: hidden; }
        .yfj-table-box { overflow-x: auto; padding: 15px; }
        .yfj-table { width: 100%; border-collapse: collapse; font-size: 13px; text-align: center; min-width: 500px; border: 1px solid #ddd; }
        .yfj-table th { background: #f5f5f5; padding: 8px; border: 1px solid #ddd; font-weight: bold; color: #333; text-align: center; }
        .yfj-table td { padding: 8px; border: 1px solid #ddd; vertical-align: middle; }
        .yfj-table tr:hover { background: #f9f9f9; }

        /* 解析块 */
        .yfj-desc-block { background: #fdfaf6; padding: 15px; margin-bottom: 15px; border-radius: 8px; border-left: 4px solid var(--yfj-primary, #c99a5b); }
    </style>

    <div class="yfj-synastry-result">

        <div class="yfj-info-header">
            <div class="yfj-info-box" style="border-top: 3px solid #ff4c00;">
                <h4 style="margin:0 0 10px 0; color: #ff4c00; font-size: 16px;"><?php echo $this->t('A方信息'); ?></h4>
                <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('出生公历：'); ?></strong><?php echo esc_html($person_a['birthday'] ?? '-'); ?></p>
                <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('经度：'); ?></strong><?php echo esc_html($person_a['longitude'] ?? '-'); ?> &nbsp; <strong><?php echo $this->t('纬度：'); ?></strong><?php echo esc_html($person_a['latitude'] ?? '-'); ?></p>
                <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('时区：'); ?></strong><?php echo esc_html($person_a['timezone'] ?? '-'); ?></p>
                <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('生命灵数：'); ?></strong><span style="color:red; font-weight:bold;"><?php echo esc_html($person_a['numerology'] ?? '-'); ?></span></p>
            </div>
            <div class="yfj-info-box" style="border-top: 3px solid #00d0ce;">
                <h4 style="margin:0 0 10px 0; color: #00d0ce; font-size: 16px;"><?php echo $this->t('B方信息'); ?></h4>
                <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('出生公历：'); ?></strong><?php echo esc_html($person_b['birthday'] ?? '-'); ?></p>
                <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('经度：'); ?></strong><?php echo esc_html($person_b['longitude'] ?? '-'); ?> &nbsp; <strong><?php echo $this->t('纬度：'); ?></strong><?php echo esc_html($person_b['latitude'] ?? '-'); ?></p>
                <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('时区：'); ?></strong><?php echo esc_html($person_b['timezone'] ?? '-'); ?></p>
                <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('生命灵数：'); ?></strong><span style="color:red; font-weight:bold;"><?php echo esc_html($person_b['numerology'] ?? '-'); ?></span></p>
            </div>
        </div>

        <div class="yfj-view-tabs">
            <button class="yfj-tab-btn active" id="tab-ba" onclick="toggleSynView('b_in_a')"><?php echo $this->t('A 为主场 (观察 B 介入 A)'); ?></button>
            <button class="yfj-tab-btn" id="tab-ab" onclick="toggleSynView('a_in_b')"><?php echo $this->t('B 为主场 (观察 A 介入 B)'); ?></button>
        </div>

        <div id="view-content-ba">
            <div class="yfj-svg-wrap">
                <h4 style="margin-top:0; color:#1e293b; font-size: 15px;"><span class="dashicons dashicons-art"></span> <?php echo $this->t('A 为主场能量交互星图'); ?></h4>
                <div class="yfj-svg-item"><?php echo $svg_b_in_a; ?></div>
                <div style="font-size: 12px; color: #94a3b8; margin-top: 10px;"><?php echo $this->t('( 内圈：A方本命盘 &nbsp;|&nbsp; 外圈：B方行星落入 )'); ?></div>
            </div>

            <div class="yfj-data-section">
                <?php render_hardcore_natal_tables($a_natal, 'A', $this, $sf); ?>
            </div>

            <div class="yfj-data-section">
                <?php render_hardcore_overlay_table($synastry['house_overlay_b_in_a'] ?? [], 'B', 'A', $this, $sf); ?>
            </div>

            <div class="yfj-data-section">
                <?php render_aspect_list_table($synastry['planet_aspects'] ?? [], 'B', 'A', $this, $sf); ?>
            </div>

            <div class="yfj-data-section" style="border: 1px solid #d9534f; border-radius: 4px;">
                <div style="background-color: #f2dede; color: #a94442; padding: 10px 15px; border-bottom: 1px solid #d9534f;">
                    <h3 style="margin: 0; font-size: 16px; font-weight: normal;"><span class="dashicons dashicons-heart" style="vertical-align: text-bottom;"></span> <?php echo $this->t('关系核心评价总结'); ?></h3>
                </div>
                <div style="padding: 15px;">
                    <?php foreach (($chart_desc['summary'] ?? []) as $vo): ?>
                        <p style="font-size: 15px; margin-bottom: 10px;">
                            <b>【<font color="#d9534f"><?php echo esc_html($vo['target']); ?></font>】</b>：<br>
                            <?php echo nl2br(esc_html($vo['description'])); ?>
                        </p>
                        <br>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="yfj-data-section" style="border: none;">
                <h4 style="font-size:16px; border-bottom:1px solid #e2e8f0; padding-bottom:10px; margin: 15px 0;"><span class="dashicons dashicons-book-alt"></span> <?php echo $this->t('落位解析：B 对 A 的能量影响'); ?></h4>
                <div style="padding: 0;">
                    <?php foreach (($chart_desc['houses']['b_in_a'] ?? []) as $vo): ?>
                        <div class="yfj-desc-block" style="border-left-color: #ff4c00; background: #fffafa;">
                            <strong style="font-size:15px; color:#ff4c00;">【<?php echo esc_html($vo['target'] ?? ''); ?>】</strong>
                            <p style="margin-top:8px; font-size:14px;"><?php echo nl2br(esc_html($vo['description'] ?? '')); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div id="view-content-ab" style="display: none;">
            <div class="yfj-svg-wrap">
                <h4 style="margin-top:0; color:#1e293b; font-size: 15px;"><span class="dashicons dashicons-art"></span> <?php echo $this->t('B 为主场能量交互星图'); ?></h4>
                <div class="yfj-svg-item"><?php echo $svg_a_in_b; ?></div>
                <div style="font-size: 12px; color: #94a3b8; margin-top: 10px;"><?php echo $this->t('( 内圈：B方本命盘 &nbsp;|&nbsp; 外圈：A方行星落入 )'); ?></div>
            </div>

            <div class="yfj-data-section">
                <?php render_hardcore_natal_tables($b_natal, 'B', $this, $sf); ?>
            </div>

            <div class="yfj-data-section">
                <?php render_hardcore_overlay_table($synastry['house_overlay_a_in_b'] ?? [], 'A', 'B', $this, $sf); ?>
            </div>

            <div class="yfj-data-section">
                <?php render_aspect_list_table($synastry['planet_aspects'] ?? [], 'A', 'B', $this, $sf); ?>
            </div>

            <div class="yfj-data-section" style="border: 1px solid #d9534f; border-radius: 4px;">
                <div style="background-color: #f2dede; color: #a94442; padding: 10px 15px; border-bottom: 1px solid #d9534f;">
                    <h3 style="margin: 0; font-size: 16px; font-weight: normal;"><span class="dashicons dashicons-heart" style="vertical-align: text-bottom;"></span> <?php echo $this->t('关系核心评价总结'); ?></h3>
                </div>
                <div style="padding: 15px;">
                    <?php foreach (($chart_desc['summary'] ?? []) as $vo): ?>
                        <p style="font-size: 15px; margin-bottom: 10px;">
                            <b>【<font color="#d9534f"><?php echo esc_html($vo['target']); ?></font>】</b>：<br>
                            <?php echo nl2br(esc_html($vo['description'])); ?>
                        </p>
                        <br>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="yfj-data-section" style="border: none;">
                <h4 style="font-size:16px; border-bottom:1px solid #e2e8f0; padding-bottom:10px; margin: 15px 0;"><span class="dashicons dashicons-book-alt"></span> <?php echo $this->t('落位解析：A 对 B 的能量影响'); ?></h4>
                <div style="padding: 0;">
                    <?php foreach (($chart_desc['houses']['a_in_b'] ?? []) as $vo): ?>
                        <div class="yfj-desc-block" style="border-left-color: #00d0ce; background: #f0fdfc;">
                            <strong style="font-size:15px; color:#008b89;">【<?php echo esc_html($vo['target'] ?? ''); ?>】</strong>
                            <p style="margin-top:8px; font-size:14px;"><?php echo nl2br(esc_html($vo['description'] ?? '')); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="yfj-data-section" style="border: 1px solid #ddd; border-radius: 4px;">
            <div style="background-color: #f5f5f5; padding: 10px 15px; border-bottom: 1px solid #ddd;">
                <h3 style="margin: 0; font-size: 16px; font-weight: normal; color: #333;"><span class="dashicons dashicons-admin-links" style="vertical-align: text-bottom;"></span> <?php echo $this->t('双人互动相位解析'); ?></h3>
            </div>
            <div style="padding: 15px; line-height: 1.8;">
                <?php foreach (($chart_desc['aspects'] ?? []) as $vo): ?>
                    <p style="font-size:14px; margin-bottom:10px;">
                        <b>【<font color="#333"><?php echo esc_html($vo['target']); ?></font>】</b>：<?php echo nl2br(esc_html($vo['description'])); ?>
                    </p>
                <?php endforeach; ?>

                <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

                <h4 style="font-size: 16px; font-weight: bold; color: #ff9900; margin-bottom: 15px;"><?php echo $this->t('四轴接触解析：'); ?></h4>
                <?php foreach (($chart_desc['angles'] ?? []) as $vo): ?>
                    <p style="font-size:14px; margin-bottom:10px;">
                        <b>【<font color="#ff9900"><?php echo esc_html($vo['target']); ?></font>】</b>：<?php echo nl2br(esc_html($vo['description'])); ?>
                    </p>
                <?php endforeach; ?>
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

    <script>
        function toggleSynView(mode) {
            const vBA = document.getElementById('view-content-ba');
            const vAB = document.getElementById('view-content-ab');
            const tBA = document.getElementById('tab-ba');
            const tAB = document.getElementById('tab-ab');

            if (mode === 'b_in_a') {
                vBA.style.display = 'block'; vAB.style.display = 'none';
                tBA.classList.add('active'); tAB.classList.remove('active');
            } else {
                vBA.style.display = 'none'; vAB.style.display = 'block';
                tBA.classList.remove('active'); tAB.classList.add('active');
            }
        }
    </script>

<?php
/**
 * 内部渲染函数：硬核本命结构表 (完美支持多语言 $sf 与 双重守护星遍历)
 */
function render_hardcore_natal_tables($natal, $label, $plugin, $sf) {
    $planets = $natal['planetData'] ?? [];
    $houses = $natal['housesData'] ?? [];
    $color = ($label === 'A') ? '#ff4c00' : '#00d0ce';
    ?>
    <div class="yfj-table-box">
        <h5 align="center" style="margin: 0 0 15px 0; font-size:15px;"><b><?php echo $label; ?><?php echo $plugin->t('方 星体分布'); ?></b></h5>
        <table class="yfj-table">
            <thead>
            <tr style="background-color: #f5f5f5;">
                <th><?php echo $plugin->t('星体'); ?></th>
                <th><?php echo $plugin->t('落入星座'); ?></th>
                <th><?php echo $plugin->t('落入宫位'); ?></th>
                <th><?php echo $plugin->t('逆行'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($planets as $vo): ?>
                <tr>
                    <td><font color="<?php echo $color; ?>"><b><?php echo esc_html($vo["planet_{$sf}"] ?? $vo['planet_chinese'] ?? '-'); ?></b></font></td>
                    <td><?php echo esc_html($vo['sign']["sign_{$sf}"] ?? $vo['sign']['sign_chinese'] ?? ''); ?>(<?php echo esc_html($vo['sign']['deg'] ?? 0); ?>°<?php echo esc_html($vo['sign']['min'] ?? 0); ?>′<?php echo esc_html($vo['sign']['sec'] ?? 0); ?>″)</td>
                    <td>
                        <?php if (!empty($vo['house_id'])): ?>
                            <?php echo esc_html($vo['house_id']); ?><?php echo $plugin->t('宫'); ?>(<?php echo esc_html($vo['house_deg'] ?? 0); ?>°<?php echo esc_html($vo['house_min'] ?? 0); ?>′<?php echo esc_html($vo['house_sec'] ?? 0); ?>″)
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($vo['is_retrograde'])): ?>
                            <font color="red">√</font>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <h5 align="center" style="margin: 30px 0 15px 0; font-size:15px;"><b><?php echo $label; ?><?php echo $plugin->t('方 各宫位置'); ?></b></h5>
        <table class="yfj-table">
            <thead>
            <tr style="background-color: #f5f5f5;">
                <th><?php echo $plugin->t('宫位'); ?></th>
                <th><?php echo $plugin->t('星座'); ?></th>
                <th><?php echo $plugin->t('宫主星'); ?></th>
                <th><?php echo $plugin->t('飞入'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($houses as $h):
                $fly_into_raw = $h['ruler_fly_into'] ?? [];
                $fly_into_str = '-';

                // 完美保留飞星双守护逻辑与多语言
                if (is_array($fly_into_raw) && !empty($fly_into_raw)) {
                    $fly_list = [];
                    foreach ($fly_into_raw as $fly_item) {
                        if (is_array($fly_item)) {
                            $sign = $fly_item["fall_sign_{$sf}"] ?? $fly_item['fall_sign_chinese'] ?? '';
                            $house = isset($fly_item['fall_house_id']) ? $fly_item['fall_house_id'] . $plugin->t('宫') : '';
                            $fly_list[] = trim($sign . ' ' . $house);
                        } else {
                            $fly_list[] = (string)$fly_item;
                        }
                    }
                    $fly_into_str = implode(', ', array_map('esc_html', $fly_list));
                } elseif (is_string($fly_into_raw) && $fly_into_raw !== '') {
                    $fly_into_str = esc_html($fly_into_raw);
                }
                ?>
                <tr>
                    <td style="color:#333; font-weight:bold;"><?php echo esc_html($h['house_id'] ?? ''); ?><?php echo $plugin->t('宫'); ?></td>
                    <td><?php echo esc_html($h['sign']["sign_{$sf}"] ?? $h['sign']['sign_chinese'] ?? ''); ?>(<?php echo esc_html($h['sign']['deg'] ?? 0); ?>°<?php echo esc_html($h['sign']['min'] ?? 0); ?>′<?php echo esc_html($h['sign']['sec'] ?? 0); ?>″)</td>
                    <td style="color:#333; font-weight:bold;">
                        <?php
                        // 完美保留宫主星双守护逻辑与多语言
                        $main_planets = [];
                        foreach(($h['main_planet'] ?? []) as $mp) {
                            $main_planets[] = $mp["planet_{$sf}"] ?? $mp['planet_chinese'] ?? '';
                        }
                        echo esc_html(implode(' / ', $main_planets));
                        ?>
                    </td>
                    <td style="color:#666;"><?php echo $fly_into_str; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * 内部渲染函数：硬核落宫交互表 (支持多语言 $sf)
 */
function render_hardcore_overlay_table($overlay_data, $guest, $host, $plugin, $sf) {
    $guest_color = ($guest === 'A') ? '#ff4c00' : '#00d0ce';
    ?>
    <div class="yfj-table-box">
        <h5 align="center" style="margin: 0 0 15px 0; font-size:15px;"><b><?php echo $host; ?><?php echo $plugin->t('为主场 - 互动数据表'); ?></b></h5>
        <table class="yfj-table">
            <thead>
            <tr style="background-color: #f5f5f5;">
                <th><?php echo $guest; ?><?php echo $plugin->t('方星体'); ?></th>
                <th><?php echo $guest; ?><?php echo $plugin->t('方星座'); ?></th>
                <th><?php echo $plugin->t('落入'); ?><?php echo $host; ?><?php echo $plugin->t('方宫位'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($overlay_data as $ov): ?>
                <tr>
                    <td><font color="<?php echo $guest_color; ?>"><b><?php echo esc_html($ov['planet']["planet_{$sf}"] ?? $ov['planet']['planet_chinese'] ?? ''); ?></b></font></td>
                    <td><?php echo esc_html($ov['planet']['sign']["sign_{$sf}"] ?? $ov['planet']['sign']['sign_chinese'] ?? ''); ?>(<?php echo esc_html($ov['planet']['sign']['deg'] ?? 0); ?>°<?php echo esc_html($ov['planet']['sign']['min'] ?? 0); ?>′<?php echo esc_html($ov['planet']['sign']['sec'] ?? 0); ?>″)</td>
                    <td style="color:#333;">
                        <?php echo esc_html($ov['house_id'] ?? ''); ?><?php echo $plugin->t('宫'); ?> (<?php echo esc_html($ov['relative_dms']['deg'] ?? 0); ?>°<?php echo esc_html($ov['relative_dms']['min'] ?? 0); ?>′<?php echo esc_html($ov['relative_dms']['sec'] ?? 0); ?>″)
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * 内部渲染函数：精准对应原版的相位列表 (支持多语言 $sf)
 */
function render_aspect_list_table($aspects, $primary, $secondary, $plugin, $sf) {
    ?>
    <div class="yfj-table-box">
        <h5 align="center" style="margin: 0 0 15px 0; font-size:15px;"><b><?php echo $plugin->t('相位列表'); ?> (<?php echo $primary; ?> vs <?php echo $secondary; ?>)</b></h5>
        <table class="yfj-table">
            <thead>
            <tr style="background-color: #d9edf7;">
                <th><?php echo $primary; ?><?php echo $plugin->t('方'); ?></th>
                <th><?php echo $plugin->t('相位'); ?></th>
                <th><?php echo $secondary; ?><?php echo $plugin->t('方'); ?></th>
                <th><?php echo $plugin->t('方向'); ?></th>
                <th><?php echo $plugin->t('容许度'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($aspects as $vo):
                $col1_name = ($primary === 'A') ? ($vo['planet_a']["planet_{$sf}"] ?? $vo['planet_a']['planet_chinese'] ?? '') : ($vo['planet_b']["planet_{$sf}"] ?? $vo['planet_b']['planet_chinese'] ?? '');
                $col1_color = ($primary === 'A') ? '#ff4c00' : '#00d0ce';

                $col2_name = ($secondary === 'A') ? ($vo['planet_a']["planet_{$sf}"] ?? $vo['planet_a']['planet_chinese'] ?? '') : ($vo['planet_b']["planet_{$sf}"] ?? $vo['planet_b']['planet_chinese'] ?? '');
                $col2_color = ($secondary === 'A') ? '#ff4c00' : '#00d0ce';

                // API 已自带多语言相位名称，直接读取，彻底抛弃 Switch！
                $aspect_str = $vo['aspect']["aspect_{$sf}"] ?? $vo['aspect']['aspect_chinese'] ?? '-';

                $in_out_val = (string)($vo['aspect']['in_out'] ?? '');
                if ($in_out_val === '1') {
                    $direction_str = $plugin->t('入相');
                } elseif ($in_out_val === '-1') {
                    $direction_str = $plugin->t('出相');
                } else {
                    $direction_str = '-';
                }
                ?>
                <tr>
                    <td><font color="<?php echo $col1_color; ?>"><b><?php echo esc_html($col1_name); ?></b></font></td>
                    <td style="color:#333;"><b><?php echo esc_html($aspect_str); ?></b></td>
                    <td><font color="<?php echo $col2_color; ?>"><b><?php echo esc_html($col2_name); ?></b></font></td>
                    <td style="color:#666;"><?php echo esc_html($direction_str); ?></td>
                    <td style="color:#666;"><?php echo esc_html($vo['aspect']['deg'] ?? 0); ?>°<?php echo esc_html($vo['aspect']['min'] ?? 0); ?>′<?php echo esc_html($vo['aspect']['sec'] ?? 0); ?>″</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}
?>