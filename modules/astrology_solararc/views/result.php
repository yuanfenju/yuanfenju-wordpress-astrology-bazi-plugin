<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fff3cd; padding:12px 15px; color:#856404; margin-bottom:20px; border-radius:6px; border-left: 4px solid #ffeeba; font-size: 14px; line-height: 1.6;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<?php
// ==========================================
// 1. 数据精准提取 (匹配太阳弧字典)
// ==========================================
$base         = $data['base_info'] ?? [];
$natal_info   = $base['natal_info'] ?? [];
$target_info  = $base['target_info'] ?? [];

$detail       = $data['detail_info'] ?? [];
$chart_data   = $detail['chart_data'] ?? [];
$chart_desc   = $detail['chart_description'] ?? [];

// 数据分层 (person_a_natal 为推运太阳弧，person_b_natal 为本命底盘)
$natal_data      = $chart_data['person_b_natal'] ?? [];
$natal_planets   = $natal_data['planetData'] ?? [];
$natal_houses    = $natal_data['housesData'] ?? [];

// 深度触发数据 (Synastry 结构)
$synastry        = $chart_data['synastry'] ?? [];
$house_overlays  = $synastry['house_overlay_a_in_b'] ?? []; // 推运太阳弧星入本命宫
$aspect_list     = $synastry['planet_aspects'] ?? [];       // 推运太阳弧与本命星相位

// SVG
$raw_svg   = $detail['chart_svg'] ?? '';
$clean_svg = preg_replace('/<\?xml.*?\?>/is', '', $raw_svg);

// 🌐 动态语言后缀拾取
$lang_opt = get_option('yfj_language', 'zh-cn');
$sf = 'chinese';
if ($lang_opt === 'zh-tw') { $sf = 'chinese_traditional'; }
elseif ($lang_opt === 'en-us') { $sf = 'english'; }
?>

<style>
    .yfj-solar-arc-result { font-family: -apple-system, sans-serif; color: #334155; line-height: 1.6; }
    .yfj-info-header { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
    .yfj-info-box { padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; }
    .yfj-svg-wrap { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; text-align: center; margin-bottom: 25px; }
    .yfj-svg-item svg { width: 100%; max-width: 600px; height: auto; display: inline-block; }
    .yfj-data-section { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 20px; overflow: hidden; }
    .yfj-table-box { overflow-x: auto; padding: 15px; }
    .yfj-table { width: 100%; border-collapse: collapse; font-size: 13px; text-align: center; min-width: 550px; border: 1px solid #ddd; }
    .yfj-table th { background: #f9f9f9; padding: 8px; border: 1px solid #ddd; font-weight: bold; color: #333; text-align: center; }
    .yfj-table td { padding: 8px; border: 1px solid #ddd; vertical-align: middle; }
    .yfj-table tr:hover { background: #fcfcfc; }
    .yfj-desc-block { background: #fdfaf6; padding: 15px; margin-bottom: 15px; border-radius: 8px; border-left: 4px solid var(--yfj-primary, #c99a5b); }
</style>

<div class="yfj-solar-arc-result">

    <div class="yfj-info-header">
        <div class="yfj-info-box" style="border-top: 3px solid #00d0ce;">
            <h4 style="margin:0 0 10px 0; color: #00d0ce; font-size: 16px;"><span class="dashicons dashicons-admin-users"></span> <?php echo $this->t('A本命信息 (内圈基底)'); ?></h4>
            <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('公历：'); ?></strong><?php echo esc_html($natal_info['birthday'] ?? '-'); ?></p>
            <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('灵数：'); ?></strong><span style="color:red; font-weight:bold;"><?php echo esc_html($natal_info['numerology'] ?? '-'); ?></span></p>
            <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('经纬度：'); ?></strong><?php echo esc_html($natal_info['longitude'] ?? '-'); ?>, <?php echo esc_html($natal_info['latitude'] ?? '-'); ?></p>
            <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('时区：'); ?></strong><?php echo esc_html($natal_info['timezone'] ?? '-'); ?></p>
        </div>
        <div class="yfj-info-box" style="border-top: 3px solid #ff4c00;">
            <h4 style="margin:0 0 10px 0; color: #ff4c00; font-size: 16px;"><span class="dashicons dashicons-clock"></span> <?php echo $this->t('推运信息 (外圈)'); ?></h4>
            <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('推运方式：'); ?></strong><?php echo $this->t('太阳弧推运 (Solar Arc)'); ?></p>
            <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('推运时间：'); ?></strong><span style="color:#ff4c00; font-weight:bold;"><?php echo esc_html($target_info['birthday'] ?? '-'); ?></span></p>
            <p style="margin:4px 0; font-size:13px;"><strong><?php echo $this->t('推运逻辑：'); ?></strong><?php echo $this->t('全盘星体以太阳真实赤经弧为基准等距推进'); ?></p>
        </div>
    </div>

    <div class="yfj-svg-wrap">
        <h4 style="margin-top:0; color:#1e293b; font-size: 15px;"><span class="dashicons dashicons-art"></span> <?php echo $this->t('太阳弧推运双轮盘 (内圈A本命 / 外圈推运)'); ?></h4>
        <div class="yfj-svg-item"><?php echo $clean_svg; ?></div>
    </div>

    <div class="yfj-data-section">
        <div class="yfj-table-box">
            <h5 align="center" style="margin: 0 0 15px 0; font-size:15px;"><b><?php echo $this->t('星体落入星座 （A本命盘）'); ?></b></h5>
            <table class="yfj-table">
                <thead>
                <tr>
                    <th><?php echo $this->t('星体'); ?></th>
                    <th><?php echo $this->t('落入星座'); ?></th>
                    <th><?php echo $this->t('落入宫位'); ?></th>
                    <th><?php echo $this->t('逆行'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($natal_planets as $vo): ?>
                    <tr>
                        <td><font color="#00d0ce"><b><?php echo esc_html($vo["planet_{$sf}"] ?? $vo['planet_chinese'] ?? '-'); ?></b></font></td>
                        <td>
                            <?php echo esc_html($vo['sign']["sign_{$sf}"] ?? $vo['sign']['sign_chinese'] ?? ''); ?>(<?php echo esc_html($vo['sign']['deg'] ?? 0); ?>°<?php echo esc_html($vo['sign']['min'] ?? 0); ?>′<?php echo esc_html($vo['sign']['sec'] ?? 0); ?>″)
                        </td>
                        <td>
                            <?php if (!empty($vo['house_id'])): ?>
                                <?php echo esc_html($vo['house_id']); ?><?php echo $this->t('宫'); ?>(<?php echo esc_html($vo['house_deg'] ?? 0); ?>°<?php echo esc_html($vo['house_min'] ?? 0); ?>′<?php echo esc_html($vo['house_sec'] ?? 0); ?>″)
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?php echo !empty($vo['is_retrograde']) ? '<font color="red">√</font>' : '-'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="yfj-data-section">
        <div class="yfj-table-box">
            <h5 align="center" style="margin: 0 0 15px 0; font-size:15px;"><b><?php echo $this->t('各宫位置 （A本命盘）'); ?></b></h5>
            <table class="yfj-table">
                <thead>
                <tr>
                    <th><?php echo $this->t('宫位'); ?></th>
                    <th><?php echo $this->t('星座'); ?></th>
                    <th><?php echo $this->t('宫主星'); ?></th>
                    <th><?php echo $this->t('飞入'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($natal_houses as $vo): ?>
                    <tr>
                        <td><font color="#00d0ce"><b><?php echo esc_html($vo['house_id'] ?? ''); ?><?php echo $this->t('宫'); ?></b></font></td>
                        <td>
                            <?php echo esc_html($vo['sign']["sign_{$sf}"] ?? $vo['sign']['sign_chinese'] ?? ''); ?>(<?php echo esc_html($vo['sign']['deg'] ?? 0); ?>°<?php echo esc_html($vo['sign']['min'] ?? 0); ?>′<?php echo esc_html($vo['sign']['sec'] ?? 0); ?>″)
                        </td>
                        <td><?php echo esc_html($vo['main_planet'][0]["planet_{$sf}"] ?? $vo['main_planet'][0]['planet_chinese'] ?? ''); ?></td>
                        <td>
                            <?php if(!empty($vo['ruler_fly_into'][0])): ?>
                                <?php echo esc_html($vo['ruler_fly_into'][0]["fall_sign_{$sf}"] ?? $vo['ruler_fly_into'][0]['fall_sign_chinese'] ?? ''); ?>
                                <?php echo esc_html($vo['ruler_fly_into'][0]['fall_house_id'] ?? ''); ?><?php echo $this->t('宫'); ?>
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
            <h5 align="center" style="margin: 0 0 15px 0; font-size:15px;"><b><?php echo $this->t('推运星体 落入 A'); ?></b></h5>
            <table class="yfj-table">
                <thead>
                <tr style="background-color: #fff4f0;">
                    <th style="color:#ff4c00;"><?php echo $this->t('推运'); ?></th>
                    <th style="color:#ff4c00;"><?php echo $this->t('星座'); ?></th>
                    <th style="color:#ff4c00;"><?php echo $this->t('A宫位'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($house_overlays as $vo): ?>
                    <tr>
                        <td><font color="#ff4c00"><b><?php echo esc_html($vo['planet']["planet_{$sf}"] ?? $vo['planet']['planet_chinese'] ?? ''); ?></b></font></td>
                        <td>
                            <?php echo esc_html($vo['planet']['sign']["sign_{$sf}"] ?? $vo['planet']['sign']['sign_chinese'] ?? ''); ?>(<?php echo esc_html($vo['planet']['sign']['deg'] ?? 0); ?>°<?php echo esc_html($vo['planet']['sign']['min'] ?? 0); ?>′<?php echo esc_html($vo['planet']['sign']['sec'] ?? 0); ?>″)
                        </td>
                        <td>
                            <?php if (!empty($vo['house_id'])): ?>
                                <?php echo esc_html($vo['house_id']); ?><?php echo $this->t('宫'); ?>(<?php echo esc_html($vo['relative_dms']['deg'] ?? 0); ?>°<?php echo esc_html($vo['relative_dms']['min'] ?? 0); ?>′<?php echo esc_html($vo['relative_dms']['sec'] ?? 0); ?>″)
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
            <h5 align="center" style="margin: 0 0 15px 0; font-size:15px;"><b><?php echo $this->t('相位列表（推运 vs A）'); ?></b></h5>
            <table class="yfj-table">
                <thead>
                <tr style="background-color: #fff4f0;">
                    <th><?php echo $this->t('推运 (触发方)'); ?></th>
                    <th><?php echo $this->t('相位'); ?></th>
                    <th><?php echo $this->t('A (承受方)'); ?></th>
                    <th><?php echo $this->t('方向'); ?></th>
                    <th><?php echo $this->t('容许度'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($aspect_list as $vo):
                    $t_name = $vo['planet_a']["planet_{$sf}"] ?? $vo['planet_a']['planet_chinese'] ?? '';
                    $n_name = $vo['planet_b']["planet_{$sf}"] ?? $vo['planet_b']['planet_chinese'] ?? '';
                    $aspect_name = $vo['aspect']["aspect_{$sf}"] ?? $vo['aspect']['aspect_chinese'] ?? '-';
                    $in_out = (string)($vo['aspect']['in_out'] ?? '');
                    ?>
                    <tr>
                        <td><font color="#ff4c00"><b><?php echo esc_html($t_name); ?></b></font></td>
                        <td><?php echo esc_html($aspect_name); ?></td>
                        <td><font color="#00d0ce"><b><?php echo esc_html($n_name); ?></b></font></td>
                        <td>
                            <?php if($in_out === '1'): ?><?php echo $this->t('入相'); ?>
                            <?php elseif($in_out === '-1'): ?><?php echo $this->t('出相'); ?>
                            <?php else: ?>-<?php endif; ?>
                        </td>
                        <td><?php echo esc_html($vo['aspect']['deg'] ?? 0); ?>°<?php echo esc_html($vo['aspect']['min'] ?? 0); ?>′<?php echo esc_html($vo['aspect']['sec'] ?? 0); ?>″</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="yfj-data-section">
        <div class="yfj-table-box" style="padding: 10px 15px; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
            <h3 style="margin: 0; font-size: 15px; font-weight: bold;"><span class="dashicons dashicons-list-view"></span> <?php echo $this->t('总体概览'); ?></h3>
        </div>
        <div style="padding: 15px;">
            <?php foreach (($chart_desc['summary'] ?? []) as $vo): ?>
                <p style="margin-bottom: 10px; font-size: 14px;">
                    【<font color="#ff4c00"><?php echo esc_html($vo['target']); ?></font>】：<br><?php echo nl2br(esc_html($vo['description'])); ?>
                </p><br>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="yfj-data-section">
        <div class="yfj-table-box" style="padding: 10px 15px; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
            <h3 style="margin: 0; font-size: 15px; font-weight: bold;"><span class="dashicons dashicons-list-view"></span> <?php echo $this->t('相位与事件触发解析'); ?></h3>
        </div>
        <div style="padding: 15px;">
            <?php foreach (($chart_desc['aspects'] ?? []) as $vo): ?>
                <p style="margin-bottom: 10px; font-size: 14px;">
                    【<font color="#ff4c00"><?php echo esc_html($vo['target']); ?></font>】：<br><?php echo nl2br(esc_html($vo['description'])); ?>
                </p><br>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="yfj-data-section">
        <div class="yfj-table-box" style="padding: 10px 15px; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
            <h3 style="margin: 0; font-size: 15px; font-weight: bold;"><span class="dashicons dashicons-list-view"></span> <?php echo $this->t('四轴落点与交角解析'); ?></h3>
        </div>
        <div style="padding: 15px;">
            <?php foreach (($chart_desc['angles'] ?? []) as $vo): ?>
                <p style="margin-bottom: 10px; font-size: 14px;">
                    【<font color="#ff4c00"><?php echo esc_html($vo['target']); ?></font>】：<br><?php echo nl2br(esc_html($vo['description'])); ?>
                </p><br>
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