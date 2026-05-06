<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fff3cd; padding:12px 15px; color:#856404; margin-bottom:20px; border-radius:6px; border-left: 4px solid #ffeeba; font-size: 14px; line-height: 1.6;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<?php
// 安全拦截
if (empty($data) || !is_array($data) || empty($data['gong_pan'])) {
    echo '<div style="color:red; text-align:center; padding: 20px;">' . $this->t('暂无数据') . '</div>';
    return;
}

$base = $data;
$sizhu = $data['sizhu_info'] ?? [];
$xunkong = $data['xunkong_info'] ?? [];
$zhifu = $data['zhifu_info'] ?? [];
$maxing = $data['maxing_info'] ?? [];
$kongwang = $data['kongwang_info'] ?? [];
$gong_pan = $data['gong_pan'] ?? [];

// 增加 function_exists 检查，防止在特定主题下重复加载导致 500 崩溃报错
if (!function_exists('yfj_get_yinpan_mark_style')) {
    function yfj_get_yinpan_mark_style($gong, $target_str, $type) {
        if (empty($target_str)) return '';
        $jixing = $gong['jixing'] ?? '';
        $rumu = $gong['rumu'] ?? '';
        $menpo = $gong['menpo'] ?? '';

        if ($type === 'men' && strpos($menpo, $target_str) !== false) {
            return 'background: #dc2626; color: #fff;';
        }

        $is_ji = (strpos($jixing, $target_str) !== false);
        $is_ru = (strpos($rumu, $target_str) !== false);

        if ($is_ji && $is_ru) return 'background: #3b82f6; color: #fff;';
        if ($is_ji) return 'background: #d946ef; color: #fff;';
        if ($is_ru) return 'background: #eab308; color: #fff;';

        return '';
    }
}

if (!function_exists('yfj_render_yinpan_ganzhi')) {
    function yfj_render_yinpan_ganzhi($gong, $target_key) {
        $content = $gong[$target_key] ?? '';
        if (empty($content)) return '';

        if (mb_strlen($content) > 1 && $target_key !== 'tianpan') {
            return esc_html($content);
        }

        $style = yfj_get_yinpan_mark_style($gong, $content, 'gan');
        if ($style) {
            return '<span class="yfj-status-span" style="' . $style . '">' . esc_html($content) . '</span>';
        }
        return esc_html($content);
    }
}

$gong_order = [3, 4, 5, 2, 8, 6, 1, 0, 7];
?>

<style>
    .yfj-yp-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 24px; overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 12px 15px; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #0f172a; font-size: 16px; }
    .yfj-panel-body { padding: 20px; font-size: 14px; line-height: 1.8; }

    .yfj-yp-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px; }
    .yfj-yp-info-col { background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0; }
    .yfj-yp-info-col p { margin: 0 0 5px 0; }
    .yfj-yp-highlight { color: #dc2626; font-weight: bold; }
    .yfj-yp-green { color: #16a34a; font-weight: bold; }

    .yfj-yinpan-container { position: relative; max-width: 620px; margin: 40px auto 20px auto; padding: 30px; }

    .yfj-yp-yingan { position: absolute; font-size: 14px; font-weight: bold; color: #64748b; background: #fff; padding: 2px 6px; border-radius: 4px; border: 1px solid #cbd5e1; z-index: 10; display: flex; gap: 4px; align-items: center; }
    .yfj-yp-n1 { top: 0; left: 15%; }
    .yfj-yp-n2 { top: 0; right: 15%; }
    .yfj-yp-n3 { bottom: 0; left: 15%; }
    .yfj-yp-n4 { bottom: 0; right: 15%; }
    .yfj-yp-n5 { top: 50%; left: 0; transform: translateY(-50%); }
    .yfj-yp-n6 { top: 50%; right: 0; transform: translateY(-50%); }
    .yfj-yp-n7 { top: 0; left: 50%; transform: translateX(-50%); }
    .yfj-yp-n8 { bottom: 0; left: 50%; transform: translateX(-50%); }

    .yfj-yp-ma { color: #dc2626; font-size: 12px; background: #fef2f2; border: 1px solid #fecaca; padding: 1px 3px; border-radius: 3px; line-height: 1; }

    .yfj-jiugong-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px; background: #cbd5e1; padding: 4px; border-radius: 8px; }
    .yfj-gong-box { background: #fff; padding: 10px; min-height: 110px; display: flex; flex-direction: column; justify-content: space-between; text-align: center; border-radius: 4px; border: 1px solid #fff; transition: 0.3s; position: relative; }
    .yfj-gong-box:hover { border-color: #94a3b8; box-shadow: 0 4px 12px rgba(0,0,0,0.05); z-index: 5; }

    .yfj-gong-row1 { color: #dc2626; font-weight: bold; margin-bottom: 5px; font-size: 15px; }
    .yfj-gong-row2 { color: #800080; font-weight: bold; margin-bottom: 5px; font-size: 15px; }
    .yfj-gong-row3 { color: #2563eb; font-weight: bold; font-size: 16px; }

    .yfj-status-span { display: inline-block; padding: 2px 4px; border-radius: 4px; line-height: 1; margin: 0 2px; }

    .yfj-kong-circle { position: absolute; top: 6px; right: 6px; color: #2563eb; font-size: 16px; font-weight: bold; line-height: 1; }

    .yfj-yp-legend { text-align: center; font-size: 13px; margin-top: 20px; padding: 10px; background: #f8fafc; border-radius: 6px; }
    .yfj-legend-item { display: inline-block; padding: 2px 6px; border-radius: 4px; color: #fff; margin: 0 5px; font-weight: bold; }
</style>

<div class="yfj-yp-wrapper">
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-id-alt"></span> <?php echo $this->t('基本信息'); ?></div>
        <div class="yfj-panel-body">
            <div class="yfj-yp-info-grid">
                <div class="yfj-yp-info-col">
                    <p><strong><?php echo $this->t('姓名：'); ?></strong> <?php echo esc_html($base['name']); ?> (<?php echo esc_html($base['sex']); ?>)</p>
                    <p><strong><?php echo $this->t('公历：'); ?></strong> <?php echo esc_html($base['gongli']); ?></p>
                    <p><strong><?php echo $this->t('农历：'); ?></strong> <?php echo esc_html($base['nongli']); ?></p>
                    <p><strong><?php echo $this->t('上一节气：'); ?></strong> <?php echo esc_html($base['jieqi_pre']); ?> </p>
                    <p><strong><?php echo $this->t('下一节气：'); ?></strong> <?php echo esc_html($base['jieqi_next']); ?> </p>
                    <p><strong><?php echo $this->t('马星：'); ?></strong> <span class="yfj-yp-green"><?php echo esc_html($maxing['maxing_name'] ?? ''); ?></span><?php echo $this->t('落'); ?><?php echo esc_html($maxing['maxing_luogong'] ?? ''); ?><?php echo $this->t('宫'); ?></p>
                    <p><strong><?php echo $this->t('月将：'); ?></strong> <span class="yfj-yp-highlight"><?php echo esc_html($base['yuejiang'] ?? ''); ?></span></p>
                </div>
                <div class="yfj-yp-info-col">
                    <p><strong><?php echo $this->t('四柱：'); ?></strong> <span class="yfj-yp-highlight"><?php echo esc_html($sizhu['year_gan'].$sizhu['year_zhi']); ?> &nbsp; <?php echo esc_html($sizhu['month_gan'].$sizhu['month_zhi']); ?> &nbsp; <?php echo esc_html($sizhu['day_gan'].$sizhu['day_zhi']); ?> &nbsp; <?php echo esc_html($sizhu['hour_gan'].$sizhu['hour_zhi']); ?></span></p>
                    <p><strong><?php echo $this->t('旬空：'); ?></strong> <span style="color:#2563eb; font-weight:bold;"><?php echo esc_html($xunkong['year_xunkong'].' '.$xunkong['month_xunkong'].' '.$xunkong['day_xunkong'].' '.$xunkong['hour_xunkong']); ?><?php if(($base['iske'] ?? 0) == 1) echo ' '.esc_html($xunkong['ke_xunkong'] ?? ''); ?></span></p>
                    <p><strong><?php echo $this->t('旬首：'); ?></strong> <span class="yfj-yp-green"><?php echo esc_html($base['xunshou'] ?? ''); ?></span> <span class="yfj-yp-highlight"><?php echo esc_html($base['kongwang'] ?? ''); ?></span></p>
                    <p><strong><?php echo $this->t('定局：'); ?></strong> <span class="yfj-yp-highlight"><?php echo esc_html($base['dunju']); ?></span> (<span class="yfj-yp-green"><?php echo esc_html($base['panlei']); ?></span>)</p>
                    <p><strong><?php echo $this->t('值符：'); ?></strong> <span class="yfj-yp-highlight"><?php echo esc_html($zhifu['zhifu_name'] ?? ''); ?></span><?php echo $this->t('落'); ?><?php echo esc_html($zhifu['zhifu_luogong'] ?? ''); ?><?php echo $this->t('宫'); ?></p>
                    <p><strong><?php echo $this->t('值使：'); ?></strong> <span class="yfj-yp-highlight"><?php echo esc_html($zhifu['zhishi_name'] ?? ''); ?></span><?php echo $this->t('落'); ?><?php echo esc_html($zhifu['zhishi_luogong'] ?? ''); ?><?php echo $this->t('宫'); ?></p>
                    <p><strong><?php echo $this->t('空亡：'); ?></strong> <span class="yfj-yp-green"><?php echo esc_html($kongwang['kongwang_name'] ?? ''); ?></span><?php echo $this->t('落'); ?><?php echo esc_html($kongwang['kongwang_luogong'] ?? ''); ?><?php echo $this->t('宫'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-grid-view"></span> <?php echo $this->t('阴盘奇门局'); ?></div>
        <div class="yfj-panel-body">

            <div class="yfj-yinpan-container">
                <?php
                $yingan_map = [
                    'yfj-yp-n1' => 3,
                    'yfj-yp-n7' => 4,
                    'yfj-yp-n2' => 5,
                    'yfj-yp-n5' => 2,
                    'yfj-yp-n6' => 6,
                    'yfj-yp-n3' => 8,
                    'yfj-yp-n8' => 0,
                    'yfj-yp-n4' => 7
                ];
                foreach ($yingan_map as $cls => $idx) {
                    $g = $gong_pan[$idx] ?? [];
                    if (!empty($g)) {
                        echo '<div class="yfj-yp-yingan ' . $cls . '">' . esc_html($g['yingan'] ?? '');
                        if (($g['is_maxing'] ?? 0) == 1) {
                            echo '<span class="yfj-yp-ma">' . $this->t('马') . '</span>';
                        }
                        echo '</div>';
                    }
                }
                ?>

                <div class="yfj-jiugong-grid">
                    <?php foreach($gong_order as $idx):
                        $g = $gong_pan[$idx] ?? [];
                        if(empty($g)) { echo '<div class="yfj-gong-box"></div>'; continue; }
                        ?>
                        <div class="yfj-gong-box">
                            <?php if(($g['is_kongwang'] ?? 0) == 1): ?><div class="yfj-kong-circle">〇</div><?php endif; ?>

                            <div class="yfj-gong-row1">
                                <?php echo esc_html($g['dipan_bashen'] ?? ''); ?>
                            </div>

                            <div class="yfj-gong-row2">
                                <?php echo esc_html($g['tianpan_jiuxing'] ?? ''); ?>
                                <?php
                                if(isset($g['tianpan_is_ji']) && $g['tianpan_is_ji'] == 1) {
                                    echo yfj_render_yinpan_ganzhi($g, 'tianpan_yuangong');
                                    echo yfj_render_yinpan_ganzhi($g, 'tianpan_jigong');
                                } else {
                                    echo yfj_render_yinpan_ganzhi($g, 'tianpan');
                                }
                                ?>
                            </div>

                            <div class="yfj-gong-row3">
                                <?php
                                $men_style = yfj_get_yinpan_mark_style($g, $g['dipan_bamen'] ?? '', 'men');
                                echo '<span class="yfj-status-span" style="'.$men_style.'">' . esc_html($g['dipan_bamen'] ?? '') . '</span>';
                                ?>
                                <?php
                                if(isset($g['dipan_is_ji']) && $g['dipan_is_ji'] == 1) {
                                    echo yfj_render_yinpan_ganzhi($g, 'dipan_yuangong');
                                    echo yfj_render_yinpan_ganzhi($g, 'dipan_jigong');
                                } else {
                                    echo yfj_render_yinpan_ganzhi($g, 'dipan');
                                }
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="yfj-yp-legend">
                <?php echo $this->t('符号颜色说明：'); ?>
                <span class="yfj-legend-item" style="background: #eab308;"><?php echo $this->t('入墓'); ?></span>
                <span class="yfj-legend-item" style="background: #d946ef;"><?php echo $this->t('击刑'); ?></span>
                <span class="yfj-legend-item" style="background: #dc2626;"><?php echo $this->t('门迫'); ?></span>
                <span class="yfj-legend-item" style="background: #3b82f6;"><?php echo $this->t('刑+墓'); ?></span>
            </div>

        </div>
    </div>

    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-welcome-learn-more"></span> <?php echo $this->t('排盘简批'); ?></div>
        <div class="yfj-panel-body">
            <?php
            for($i = 0; $i <= 8; $i++):
                $g = $gong_pan[$i] ?? [];
                if(empty($g) || empty($g['dipan_bamen'])) continue;
                ?>
                <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px dashed #cbd5e1;">
                    <div style="font-size: 16px; font-weight: bold; color: #0f172a; margin-bottom: 8px;">
                        【<?php echo esc_html($g['dipan_bamen'] ?? ''); ?>】
                    </div>
                    <div style="color: #475569; margin-bottom: 5px;">
                        <span style="font-weight: bold; color: #b91c1c;">[<?php echo $this->t('宫局'); ?>]：</span> <?php echo esc_html($g['description']['gong_ju'] ?? ''); ?>
                    </div>
                    <div style="color: #475569; margin-bottom: 5px;">
                        <span style="font-weight: bold;">[<?php echo $this->t('宫局解析'); ?>]：</span> <?php echo esc_html($g['description']['gong_ju_desc'] ?? ''); ?>
                    </div>
                    <div style="color: #475569;">
                        <span style="font-weight: bold;">[<?php echo $this->t('落宫解析'); ?>]：</span> <?php echo esc_html($g['description']['luo_gong_desc'] ?? ''); ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- 公共免责声明 -->
    <?php echo $this->get_disclaimer_html(); ?>

    <div style="text-align: center; margin-top: 10px;">
        <button onclick="jQuery('.yfj-result-area').hide(); jQuery('.yfj-ajax-form').fadeIn();"
                style="background: #e2e8f0; color: #334155; border: none; padding: 10px 24px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            <?php echo $this->t('返回重排'); ?>
        </button>
    </div>

</div>