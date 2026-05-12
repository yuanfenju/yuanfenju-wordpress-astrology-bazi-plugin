<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fff3cd; padding:12px 15px; color:#856404; margin-bottom:20px; border-radius:6px; border-left: 4px solid #ffeeba; font-size: 14px; line-height: 1.6;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<?php
// ==========================================
// 1. 数据精准提取 (严格匹配行运字典)
// ==========================================
$base         = $data['base_info'] ?? [];
$natal_info   = $base['natal_info'] ?? [];
$target_info  = $base['target_info'] ?? [];

$detail       = $data['detail_info'] ?? [];
$chart_data   = $detail['chart_data'] ?? [];
$chart_desc   = $detail['chart_description'] ?? [];

// 行运盘核心数据分类提取 (A方为推运，B方为本命)
$transit_data    = $chart_data['person_a_natal'] ?? [];
$natal_data      = $chart_data['person_b_natal'] ?? [];

$transit_planets = $transit_data['planetData'] ?? [];
$natal_planets   = $natal_data['planetData'] ?? [];
$natal_houses    = $natal_data['housesData'] ?? []; // 【补全】本命各宫位置

// 深度触发数据
$synastry          = $chart_data['synastry'] ?? [];
$transit_aspects   = $synastry['planet_aspects'] ?? [];
$transit_in_houses = $synastry['house_overlay_a_in_b'] ?? []; // 行运星过境本命宫位
$angle_contacts    = $synastry['angle_contacts'] ?? [];

// SVG 清洗
$raw_svg   = $detail['chart_svg'] ?? '';
$clean_svg = preg_replace('/<\?xml.*?\?>/is', '', $raw_svg);

// 🌐 动态语言后缀拾取
$lang_opt = get_option('yfj_language', 'zh-cn');
$sf = 'chinese';

if ($lang_opt === 'zh-tw') {
    $sf = 'chinese_traditional';
} elseif ($lang_opt === 'en-us') {
    $sf = 'english';
}
?>

<style>
    .yfj-transit-result { font-family: -apple-system, sans-serif; color: #334155; line-height: 1.6; }

    /* 信息头部 */
    .yfj-info-header { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
    .yfj-info-box { padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; }

    /* SVG 容器 */
    .yfj-svg-wrap { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; text-align: center; margin-bottom: 25px; }
    .yfj-svg-item { display: inline-block; width: 100%; max-width: 600px; height: auto; }
    .yfj-svg-item svg { width: 100%; height: auto; display: block; }

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

<div class="yfj-transit-result">

    <div class="yfj-info-header">
        <div class="yfj-info-box" style="border-top: 3px solid #00d0ce;">
            <h4 style="margin:0 0 10px 0; color: #00d0ce; font-size: 16px;"><span class="dashicons dashicons-admin-users"></span> <?php echo $this->t('本命信息（内圈）'); ?></h4>
            <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('公历：'); ?></strong><?php echo esc_html($natal_info['birthday'] ?? '-'); ?></p>
            <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('生命灵数：'); ?></strong><span style="color:red; font-weight:bold;"><?php echo esc_html($natal_info['numerology'] ?? '-'); ?></span></p>
            <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('经度：'); ?></strong><?php echo esc_html($natal_info['longitude'] ?? '-'); ?></p>
            <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('纬度：'); ?></strong><?php echo esc_html($natal_info['latitude'] ?? '-'); ?></p>
            <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('时区：'); ?></strong><?php echo esc_html($natal_info['timezone'] ?? '-'); ?></p>
        </div>
        <div class="yfj-info-box" style="border-top: 3px solid #ff4c00;">
            <h4 style="margin:0 0 10px 0; color: #ff4c00; font-size: 16px;"><span class="dashicons dashicons-clock"></span> <?php echo $this->t('推运信息（外圈）'); ?></h4>
            <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('公历：'); ?></strong><?php echo esc_html($target_info['birthday'] ?? '-'); ?></p>
            <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('经度：'); ?></strong><?php echo esc_html($target_info['longitude'] ?? '-'); ?></p>
            <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('纬度：'); ?></strong><?php echo esc_html($target_info['latitude'] ?? '-'); ?></p>
            <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('时区：'); ?></strong><?php echo esc_html($target_info['timezone'] ?? '-'); ?></p>
        </div>
    </div>

    <div class="yfj-svg-wrap">
        <h4 style="margin-top:0; color:#1e293b; font-size: 15px;"><span class="dashicons dashicons-art"></span> <?php echo $this->t('行运星盘'); ?></h4>
        <div class="yfj-svg-item"><?php echo $clean_svg; ?></div>
    </div>

    <div class="yfj-data-section">
        <div class="yfj-table-box">
            <h5 align="center" style="margin: 0 0 15px 0; font-size:15px;"><b><?php echo $this->t('星体落入星座（本命盘）'); ?></b></h5>
            <table class="yfj-table">
                <thead>
                <tr style="background-color: #f5f5f5;">
                    <th><?php echo $this->t('星体'); ?></th>
                    <th><?php echo $this->t('落入星座'); ?></th>
                    <th><?php echo $this->t('落入宫位'); ?></th>
                    <th><?php echo $this->t('逆行'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($natal_planets as $vo): ?>
                    <tr>
                        <td><font color="#ff4c00"><b><?php echo esc_html($vo["planet_{$sf}"] ?? $vo['planet_chinese'] ?? '-'); ?></b></font></td>
                        <td><font color="#ff4c00">
                                <?php echo esc_html($vo['sign']["sign_{$sf}"] ?? $vo['sign']['sign_chinese'] ?? ''); ?>
                                (<?php echo esc_html($vo['sign']['deg'] ?? 0); ?>°<?php echo esc_html($vo['sign']['min'] ?? 0); ?>′<?php echo esc_html($vo['sign']['sec'] ?? 0); ?>″)
                            </font></td>
                        <td><font color="#ff4c00">
                                <?php if (!empty($vo['house_id'])): ?>
                                    <?php echo esc_html($vo['house_id']); ?><?php echo $this->t('宫'); ?>
                                    (<?php echo esc_html($vo['house_deg'] ?? 0); ?>°<?php echo esc_html($vo['house_min'] ?? 0); ?>′<?php echo esc_html($vo['house_sec'] ?? 0); ?>″)
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </font></td>
                        <td>
                            <?php if(!empty($vo['is_retrograde'])): ?>
                                <font color="red">√</font>
                            <?php else: ?>
                                <font color="blue">-</font>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="yfj-data-section">
        <div class="yfj-table-box">
            <h5 align="center" style="margin: 0 0 15px 0; font-size:15px;"><b><?php echo $this->t('各宫位置（本命盘）'); ?></b></h5>
            <table class="yfj-table">
                <thead>
                <tr style="background-color: #f5f5f5;">
                    <th><?php echo $this->t('宫位'); ?></th>
                    <th><?php echo $this->t('星座'); ?></th>
                    <th><?php echo $this->t('宫主星'); ?></th>
                    <th><?php echo $this->t('飞入'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($natal_houses as $vo): ?>
                    <tr>
                        <td><font color="#ff4c00"><?php echo esc_html($vo['house_id'] ?? ''); ?><?php echo $this->t('宫'); ?></font></td>
                        <td><font color="#ff4c00">
                                <?php echo esc_html($vo['sign']["sign_{$sf}"] ?? $vo['sign']['sign_chinese'] ?? ''); ?>
                                <?php echo esc_html($vo['sign']['deg'] ?? 0); ?>°<?php echo esc_html($vo['sign']['min'] ?? 0); ?>′<?php echo esc_html($vo['sign']['sec'] ?? 0); ?>″
                            </font></td>
                        <td><?php echo esc_html($vo['main_planet'][0]["planet_{$sf}"] ?? $vo['main_planet'][0]['planet_chinese'] ?? ''); ?></td>
                        <td>
                            <?php if(!empty($vo['ruler_fly_into'][0])): ?>
                                <?php echo esc_html($vo['ruler_fly_into'][0]["fall_sign_{$sf}"] ?? $vo['ruler_fly_into'][0]['fall_sign_chinese'] ?? ''); ?>
                                <font color="#ff4c00"><?php echo esc_html($vo['ruler_fly_into'][0]['fall_house_id'] ?? ''); ?><?php echo $this->t('宫'); ?></font>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="yfj-data-section">
        <div class="yfj-table-box">
            <h5 align="center" style="margin: 0 0 15px 0; font-size:15px;"><b><?php echo $this->t('行运星体落入本命'); ?></b></h5>
            <table class="yfj-table">
                <thead>
                <tr style="background-color: #f5f5f5;">
                    <th><?php echo $this->t('推运'); ?></th>
                    <th><?php echo $this->t('星座'); ?></th>
                    <th><?php echo $this->t('本命宫'); ?></th>
                    <th><?php echo $this->t('逆行'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($transit_in_houses as $vo): ?>
                    <tr>
                        <td><font color="#ff4c00"><b><?php echo esc_html($vo['planet']["planet_{$sf}"] ?? $vo['planet']['planet_chinese'] ?? ''); ?></b></font></td>
                        <td><font color="#ff4c00">
                                <?php echo esc_html($vo['planet']['sign']["sign_{$sf}"] ?? $vo['planet']['sign']['sign_chinese'] ?? ''); ?>
                                (<?php echo esc_html($vo['planet']['sign']['deg'] ?? 0); ?>°<?php echo esc_html($vo['planet']['sign']['min'] ?? 0); ?>′<?php echo esc_html($vo['planet']['sign']['sec'] ?? 0); ?>″)
                            </font></td>
                        <td><font color="#ff4c00">
                                <?php echo esc_html($vo['house_id'] ?? ''); ?><?php echo $this->t('宫'); ?>
                                (<?php echo esc_html($vo['relative_dms']['deg'] ?? 0); ?>°<?php echo esc_html($vo['relative_dms']['min'] ?? 0); ?>′<?php echo esc_html($vo['relative_dms']['sec'] ?? 0); ?>″)
                            </font></td>
                        <td>
                            <?php if(!empty($vo['planet']['is_retrograde'])): ?>
                                <font color="red">√</font>
                            <?php else: ?>
                                <font color="blue">-</font>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="yfj-data-section">
        <div class="yfj-table-box">
            <h5 align="center" style="margin: 0 0 15px 0; font-size:15px;"><b><?php echo $this->t('相位列表（推运 vs 本命）'); ?></b></h5>
            <table class="yfj-table">
                <thead>
                <tr style="background-color: #f5f5f5;">
                    <th><?php echo $this->t('推运'); ?></th>
                    <th><?php echo $this->t('相位'); ?></th>
                    <th><?php echo $this->t('本命'); ?></th>
                    <th><?php echo $this->t('方向'); ?></th>
                    <th><?php echo $this->t('容许度'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($transit_aspects as $vo):
                    $t_name = $vo['planet_a']["planet_{$sf}"] ?? $vo['planet_a']['planet_chinese'] ?? '';
                    $n_name = $vo['planet_b']["planet_{$sf}"] ?? $vo['planet_b']['planet_chinese'] ?? '';
                    $aspect_name = $vo['aspect']["aspect_{$sf}"] ?? $vo['aspect']['aspect_chinese'] ?? '-';

                    $in_out_val = (string)($vo['aspect']['in_out'] ?? '');
                    if ($in_out_val === '1') {
                        $direction_str = '<font color="#0088cc">' . $this->t('入相') . '</font>';
                    } elseif ($in_out_val === '-1') {
                        $direction_str = '<font color="#8a6d3b">' . $this->t('出相') . '</font>';
                    } else {
                        $direction_str = '-';
                    }
                    ?>
                    <tr>
                        <td><font color="#0088cc"><?php echo esc_html($t_name); ?></font></td>
                        <td><font color="#0088cc"><?php echo esc_html($aspect_name); ?></font></td>
                        <td><font color="#0088cc"><?php echo esc_html($n_name); ?></font></td>
                        <td><?php echo $direction_str; // 这里保留了 HTML 不要转义 ?></td>
                        <td><font color="#0088cc">
                                <?php echo esc_html($vo['aspect']['deg'] ?? 0); ?>°<?php echo esc_html($vo['aspect']['min'] ?? 0); ?>′<?php echo esc_html($vo['aspect']['sec'] ?? 0); ?>″
                            </font></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="yfj-data-section" style="border: 1px solid #d9534f; border-radius: 4px;">
        <div style="background-color: #f2dede; color: #a94442; padding: 10px 15px; border-bottom: 1px solid #d9534f;">
            <h3 style="margin: 0; font-size: 16px; font-weight: normal;"><span class="dashicons dashicons-list-view"></span> <?php echo $this->t('关系核心总结'); ?></h3>
        </div>
        <div style="padding: 15px;">
            <?php foreach (($chart_desc['summary'] ?? []) as $vo): ?>
                <p style="font-size: 15px; margin-bottom: 15px;">
                    【<font color="red"><?php echo esc_html($vo['target']); ?></font>】：<br>
                    <?php echo nl2br(esc_html($vo['description'])); ?>
                </p>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="yfj-data-section" style="border: none;">
        <h4 style="font-size:16px; border-bottom:1px solid #e2e8f0; padding-bottom:10px; margin: 15px 0;">
            <span class="dashicons dashicons-book-alt"></span> <?php echo $this->t('行运落入本命宫位解析'); ?>
        </h4>
        <div style="padding: 0;">
            <?php foreach (($chart_desc['houses'] ?? []) as $vo): ?>
                <div class="yfj-desc-block" style="border-left-color: #00d0ce; background: #f0fdfc;">
                    <strong style="font-size:15px; color:#00d0ce;">【<?php echo esc_html($vo['target'] ?? ''); ?>】</strong>
                    <p style="margin-top:8px; font-size:14px;"><?php echo nl2br(esc_html($vo['description'] ?? '')); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="yfj-data-section" style="border: none;">
        <h4 style="font-size:16px; border-bottom:1px solid #e2e8f0; padding-bottom:10px; margin: 15px 0;">
            <span class="dashicons dashicons-book-alt"></span> <?php echo $this->t('四轴互动解析'); ?>
        </h4>
        <div style="padding: 0;">
            <?php foreach (($chart_desc['angles'] ?? []) as $vo): ?>
                <div class="yfj-desc-block" style="border-left-color: #ff9900; background: #fff8e1;">
                    <strong style="font-size:15px; color:#ff9900;">【<?php echo esc_html($vo['target'] ?? ''); ?>】</strong>
                    <p style="margin-top:8px; font-size:14px;"><?php echo nl2br(esc_html($vo['description'] ?? '')); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="yfj-data-section" style="border: none;">
        <h4 style="font-size:16px; border-bottom:1px solid #e2e8f0; padding-bottom:10px; margin: 15px 0;">
            <span class="dashicons dashicons-book-alt"></span> <?php echo $this->t('行星互动相位解析'); ?>
        </h4>
        <div style="padding: 0;">
            <?php foreach (($chart_desc['aspects'] ?? []) as $vo): ?>
                <div class="yfj-desc-block" style="border-left-color: #ff4c00; background: #fffafa;">
                    <strong style="font-size:15px; color:#ff4c00;">【<?php echo esc_html($vo['target'] ?? ''); ?>】</strong>
                    <p style="margin-top:8px; font-size:14px;"><?php echo nl2br(esc_html($vo['description'] ?? '')); ?></p>
                </div>
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