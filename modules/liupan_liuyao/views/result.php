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

// 1. 提取基础数据节点
$base = $data['data'] ?? $data['list'] ?? $data;
$sizhu = $base['sizhu_info'] ?? [];
$shensha = $base['shensha_info'] ?? [];
$gua_info = $base['gua_info'] ?? [];

// 2. 提取卦象内的爻象信息
$bengua = $gua_info['bengua'] ?? [];
$biangua = $gua_info['biangua'] ?? [];

$bengua_yao_info = $bengua['gua_yao_info'] ?? [];
$bengua_liushen  = $bengua_yao_info['liushen'] ?? [];
$bengua_liuqin   = $bengua_yao_info['liuqin'] ?? [];
$bengua_fushen   = $bengua_yao_info['fushen'] ?? [];
$bengua_shiying  = $bengua_yao_info['shiying'] ?? [];

$biangua_yao_info = $biangua['gua_yao_info'] ?? [];
$biangua_liuqin   = $biangua_yao_info['liuqin'] ?? [];
$biangua_shiying  = $biangua_yao_info['shiying'] ?? [];

// 3. 提取动爻 (兼容单个字符串或数组)
$dongyao_raw = $base['dongyao'] ?? $base['dongyao_array'] ?? '';
$dongyao_arr = [];
if (is_array($dongyao_raw)) {
    $dongyao_arr = $dongyao_raw;
} elseif (is_string($dongyao_raw) && trim($dongyao_raw) !== '') {
    $dongyao_arr = array_map('intval', explode(',', trim($dongyao_raw)));
}

// 4. 解析二进制卦图数据 (根据 JSON 推断，gua_mark 从左到右对应 初爻 到 上爻)
$ben_lines = [];
if (!empty($bengua['gua_mark'])) {
    $ben_lines = str_split(substr(trim($bengua['gua_mark']), 0, 6));
}
$bian_lines = [];
if (!empty($biangua['gua_mark'])) {
    $bian_lines = str_split(substr(trim($biangua['gua_mark']), 0, 6));
}

/**
 * 六爻：纯内联 CSS 矢量大尺寸红蓝单爻绘图引擎
 */
if (!function_exists('yfj_render_single_yao')) {
    function yfj_render_single_yao($line_val, $is_dong) {
        if ($line_val === '') return '';
        // 【优化】将宽度拉长到 130px，高度拉长到 12px，使卦图更大气
        $html = '<div style="position: relative; width: 130px; height: 12px; display: flex; justify-content: space-between; align-items: center;">';
        if ($line_val == '1' || $line_val === 1) {
            $html .= '<div style="width: 100%; height: 100%; background-color: #dc2626; border-radius: 2px;"></div>'; // 阳红
            $marker = $is_dong ? '<span style="color:#dc2626; font-weight:bold;">O</span>' : '';
        } else {
            $html .= '<div style="width: 44%; height: 100%; background-color: #2563eb; border-radius: 2px;"></div>'; // 阴蓝
            $html .= '<div style="width: 44%; height: 100%; background-color: #2563eb; border-radius: 2px;"></div>';
            $marker = $is_dong ? '<span style="color:#2563eb; font-weight:bold;">X</span>' : '';
        }
        // 【优化】标记右移，避免和加长后的卦图贴太近
        $html .= '<div style="position: absolute; right: -25px; font-size: 16px; line-height: 1;">' . $marker . '</div>';
        $html .= '</div>';
        return $html;
    }
}
?>

<style>
    .yfj-ly-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 24px; overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #0f172a; font-size: 16px; display: flex; align-items: center; gap: 8px; }
    .yfj-panel-body { padding: 20px; font-size: 14.5px; line-height: 1.8; }

    .yfj-info-box { background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0; display: grid; grid-template-columns: 1fr; gap: 10px; margin-bottom: 15px; }
    @media (min-width: 768px) { .yfj-info-box { grid-template-columns: 1fr 1fr; } }
    .yfj-info-item { margin: 0 0 8px 0; }

    /* 六爻强迫症专属网格排版 (清理重复CSS，彻底解决换行与拉宽问题) */
    .yfj-paipan-container { background: #fffbeb; padding: 25px 20px; border-radius: 8px; border: 1px solid #fde68a; margin-bottom: 30px; overflow-x: auto; }

    /* 【核心优化】将卦图列 (第4和第8列) 拓宽至 190px，总宽度增至 880px 保证平铺不换行 */
    .yfj-grid-row { display: grid; grid-template-columns: 45px 85px 100px 190px 35px 20px 100px 190px 35px; align-items: center; gap: 8px; margin-bottom: 22px; font-size: 15px; min-width: 880px; }
    .yfj-grid-header { font-weight: bold; color: #b45309; border-bottom: 1px solid #fcd34d; padding-bottom: 15px; margin-bottom: 25px; }

    /* 所有文字容器加上 white-space: nowrap; 绝对禁止换行 */
    .g-shen { text-align: center; color: #475569; font-weight: bold; white-space: nowrap; }
    .g-fu   { text-align: center; color: #059669; font-size: 14.5px; white-space: nowrap; }
    .g-qin  { text-align: right; color: #0f172a; font-weight: bold; padding-right: 12px; white-space: nowrap; }
    /* 【核心优化】给 g-gua 也加上不换行属性，确保文字平铺 */
    .g-gua  { display: flex; justify-content: center; align-items: center; white-space: nowrap; }
    .g-sy   { text-align: left; color: #dc2626; font-weight: bold; font-size: 14px; padding-left: 10px; white-space: nowrap; }
    .g-gap  { width: 100%; }

    .yfj-desc-block { border-bottom: 1px dashed #cbd5e1; padding-bottom: 20px; margin-bottom: 20px; }
    .yfj-desc-block:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .yfj-desc-grid { display: grid; grid-template-columns: 1fr; gap: 10px; }
    @media (min-width: 768px) { .yfj-desc-grid { grid-template-columns: 1fr 1fr; } }
</style>

<div class="yfj-ly-wrapper">

    <!-- 1. 基本信息 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-id-alt"></span> <?php echo $this->t('基本信息'); ?></div>
        <div class="yfj-panel-body" style="padding: 15px 20px;">
            <div class="yfj-info-box">
                <div>
                    <p class="yfj-info-item"><strong><?php echo $this->t('年命：'); ?></strong> <span style="color:#16a34a; font-weight:bold;"><?php echo esc_html($this->t($base['nianming'] ?? '')); ?></span></p>
                    <p class="yfj-info-item"><strong><?php echo $this->t('起卦：'); ?></strong> <span style="color:#2563eb; font-weight:bold;"><?php echo esc_html($this->t($base['model'] ?? '')); ?></span></p>
                    <p class="yfj-info-item"><strong><?php echo $this->t('公历：'); ?></strong> <?php echo esc_html($base['gongli'] ?? ''); ?></p>
                    <p class="yfj-info-item"><strong><?php echo $this->t('农历：'); ?></strong> <?php echo esc_html($base['nongli'] ?? ''); ?></p>
                    <p class="yfj-info-item" style="margin-bottom:0;"><strong><?php echo $this->t('四柱：'); ?></strong> <span style="color:#dc2626; font-weight:bold;">
                        <?php echo esc_html(($sizhu['year_gan'] ?? '').($sizhu['year_zhi'] ?? '')) .  ' '; ?>
                        <?php echo esc_html(($sizhu['month_gan'] ?? '').($sizhu['month_zhi'] ?? '')) . ' '; ?>
                        <?php echo esc_html(($sizhu['day_gan'] ?? '').($sizhu['day_zhi'] ?? '')) . ' '; ?>
                        <?php echo esc_html(($sizhu['hour_gan'] ?? '').($sizhu['hour_zhi'] ?? '')); ?>
                    </span></p>
                </div>
                <div>
                    <p class="yfj-info-item"><strong><?php echo $this->t('主卦：'); ?></strong> <?php echo esc_html($this->t($gua_info['bengua']['gua_name'] ?? '')); ?></p>
                    <p class="yfj-info-item"><strong><?php echo $this->t('变卦：'); ?></strong> <span style="color:#dc2626; font-weight:bold;"><?php echo esc_html($this->t($gua_info['biangua']['gua_name'] ?? '-')); ?></span></p>
                    <p class="yfj-info-item"><strong><?php echo $this->t('卦身：'); ?></strong> <?php echo esc_html($this->t($base['guashen'] ?? '')); ?></p>
                    <p class="yfj-info-item"><strong><?php echo $this->t('空亡：'); ?></strong> <span style="color:#2563eb; font-weight:bold;"><?php echo esc_html($this->t($base['kongwang'] ?? '')); ?></span></p>
                    <p class="yfj-info-item" style="margin-bottom:0;"><strong><?php echo $this->t('神煞：'); ?></strong>
                        <span style="color:#475569;">
                        <?php
                        $ss_arr = [];
                        if(!empty($shensha['yima']))   $ss_arr[] = $this->t('驿马').'-'.$this->t($shensha['yima']);
                        if(!empty($shensha['taohua'])) $ss_arr[] = $this->t('桃花').'-'.$this->t($shensha['taohua']);
                        if(!empty($shensha['rilu']))   $ss_arr[] = $this->t('日禄').'-'.$this->t($shensha['rilu']);
                        if(!empty($shensha['guiren'])) $ss_arr[] = $this->t('贵人').'-'.$this->t($shensha['guiren']);
                        echo esc_html(implode('  ', $ss_arr));
                        ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. 六爻纳甲排盘 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-grid-view"></span> <?php echo $this->t('六爻排盘'); ?></div>
        <div class="yfj-panel-body">
            <div class="yfj-paipan-container">

                <!-- 表头 -->
                <div class="yfj-grid-row yfj-grid-header">
                    <div class="g-shen">【<?php echo $this->t('六神'); ?>】</div>
                    <div class="g-fu">【<?php echo $this->t('伏神'); ?>】</div>
                    <div class="g-qin" style="text-align: center; padding:0;">【<?php echo $this->t('六亲'); ?>】</div>
                    <div class="g-gua">【<?php echo $this->t('主卦'); ?>】：<?php echo esc_html($this->t($bengua['gua_name'] ?? '')); ?> <span style="color:#dc2626;font-size:13px;margin-left:3px;">(<?php echo esc_html($this->t($bengua['gua_gong'] ?? '')); ?>)</span></div>
                    <div class="g-sy"></div>
                    <div class="g-gap"></div>
                    <?php if(!empty($biangua)): ?>
                        <div class="g-qin" style="text-align: center; padding:0;">【<?php echo $this->t('六亲'); ?>】</div>
                        <div class="g-gua">【<?php echo $this->t('变卦'); ?>】：<?php echo esc_html($this->t($biangua['gua_name'] ?? '')); ?> <span style="color:#dc2626;font-size:13px;margin-left:3px;">(<?php echo esc_html($this->t($biangua['gua_gong'] ?? '')); ?>)</span></div>
                        <div class="g-sy"></div>
                    <?php endif; ?>
                </div>

                <!-- 逐爻渲染 -->
                <?php
                for ($yao_idx = 6; $yao_idx >= 1; $yao_idx--):
                    $arr_idx = $yao_idx - 1;

                    $liushen = $bengua_liushen['gua_yao'.$yao_idx] ?? '';

                    $fushen = '';
                    if (!empty($bengua_fushen['has_fushen'])) {
                        foreach ($bengua_fushen['fushen_arr'] ?? [] as $f) {
                            if (($f['fushen_yao_position'] ?? '') == $yao_idx) {
                                $fushen = $f['fushen'] ?? ''; break;
                            }
                        }
                    }

                    $ben_liuqin = $bengua_liuqin['gua_yao'.$yao_idx] ?? '';
                    $bian_liuqin = $biangua_liuqin['gua_yao'.$yao_idx] ?? '';

                    $ben_sy = '';
                    if (($bengua_shiying['shi_yao_position'] ?? '') == $yao_idx) $ben_sy = $this->t('世');
                    if (($bengua_shiying['ying_yao_position'] ?? '') == $yao_idx) $ben_sy = $this->t('应');

                    $bian_sy = '';
                    if (($biangua_shiying['shi_yao_position'] ?? '') == $yao_idx) $bian_sy = $this->t('世');
                    if (($biangua_shiying['ying_yao_position'] ?? '') == $yao_idx) $bian_sy = $this->t('应');

                    $is_dong = in_array($yao_idx, $dongyao_arr);
                    ?>

                    <div class="yfj-grid-row">
                        <div class="g-shen"><?php echo esc_html($liushen); ?></div>
                        <div class="g-fu"><?php echo esc_html($fushen); ?></div>
                        <div class="g-qin"><?php echo esc_html($ben_liuqin); ?></div>
                        <div class="g-gua"><?php echo yfj_render_single_yao($ben_lines[$arr_idx] ?? '', $is_dong); ?></div>
                        <div class="g-sy"><?php echo esc_html($this->t($ben_sy)); ?></div>
                        <div class="g-gap"></div>
                        <?php if(!empty($biangua)): ?>
                            <div class="g-qin"><?php echo esc_html($bian_liuqin); ?></div>
                            <div class="g-gua"><?php echo yfj_render_single_yao($bian_lines[$arr_idx] ?? '', false); ?></div>
                            <div class="g-sy"><?php echo esc_html($this->t($bian_sy)); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>

            </div>

            <!-- 具体各卦断语区 -->
            <?php
            $desc_keys = ['bengua' => '主卦', 'biangua'=> '变卦'];
            foreach ($desc_keys as $key => $title):
                $gua = $gua_info[$key] ?? [];
                if (empty($gua) || empty($gua['gua_name'])) continue;
                $desc = $gua['gua_description'] ?? [];
                ?>
                <div class="yfj-desc-block">
                    <div style="font-size: 18px; font-weight: bold; color: #0f172a; margin-bottom: 10px;">
                        <?php echo esc_html($this->t('【' . $title . '】 ') . $this->t($gua['gua_name'] ?? '')); ?>
                    </div>

                    <div style="background: #f1f5f9; padding: 12px; border-left: 3px solid #64748b; margin-bottom: 15px; font-size: 14px;">
                        <p style="margin: 0 0 5px 0;"><strong><?php echo $this->t('象曰：'); ?></strong> <?php echo esc_html($this->t($gua['gua_qian'] ?? '')); ?></p>
                        <p style="margin: 0 0 5px 0;"><strong><?php echo $this->t('爻辞：'); ?></strong> <span style="color: #b91c1c;"><?php echo esc_html($this->t($gua['gua_yaoci'] ?? '')); ?></span></p>
                        <p style="margin: 0; color: #475569;"><strong><?php echo $this->t('解卦：'); ?></strong> <?php echo esc_html($this->t($gua['gua_qian_desc'] ?? '')); ?></p>
                    </div>

                    <div class="yfj-desc-grid">
                        <?php
                        $items = [
                            'gua_shiye' => '事业谋望', 'gua_jingshang' => '经商财运',
                            'gua_qiuming' => '求名学业', 'gua_waichu' => '外出动向',
                            'gua_hunlian' => '婚恋姻缘', 'gua_juece' => '谋事决策'
                        ];
                        foreach ($items as $k => $label): if (!empty($desc[$k])):
                            ?>
                            <div style="background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 14px;">
                                <span style="font-weight: bold; color: #1e293b; display: block; margin-bottom: 4px;">【<?php echo $this->t($label); ?>】</span>
                                <?php echo esc_html($this->t($desc[$k])); ?>
                            </div>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <!-- 公共免责声明 -->
    <?php echo $this->get_disclaimer_html(); ?>

    <!-- 返回按钮 -->
    <div style="text-align: center; margin-top: 10px;">
        <button onclick="jQuery('.yfj-result-area').hide(); jQuery('.yfj-ajax-form').fadeIn();"
                style="background: #e2e8f0; color: #334155; border: none; padding: 10px 24px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            <?php echo $this->t('返回重排'); ?>
        </button>
    </div>

</div>