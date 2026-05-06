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

// 兼容不同的数据层级
$base = $data['list'] ?? $data['data'] ?? $data;
$sizhu = $base['sizhu_info'] ?? [];
$gua_info = $base['gua_info'] ?? [];

// 提取动爻 (梅花易数的动爻是一个单纯的字符串，例如 "1")
$dongyao_str = $base['dongyao'] ?? '';
$dongyao_arr = ($dongyao_str !== '') ? [intval($dongyao_str)] : [];

/**
 * 梅花易数：纯内联 CSS 矢量大尺寸红蓝卦象绘图引擎
 */
if (!function_exists('yfj_render_meihua_gua')) {
    function yfj_render_meihua_gua($lines, $dongyao = [], $empty_text = '') {
        if (empty($lines) || !is_array($lines)) {
            return '<div style="color:red; font-size:12px; margin:20px 0;">[' . esc_html($empty_text) . ']</div>';
        }

        $html = '<div style="width: 70px; display: flex; flex-direction: column; gap: 8px; margin: 10px auto; flex-shrink: 0;">';

        // 循环渲染（已通过外层 array_reverse 确保 $lines 索引 0 是上爻）
        foreach ($lines as $index => $line_val) {
            $yao_pos = count($lines) - $index; // 算出当前是第几爻
            $is_dong = in_array($yao_pos, $dongyao);

            $html .= '<div style="position: relative; width: 100%; height: 10px; display: flex; justify-content: space-between;">';

            if ($line_val == '1' || $line_val === 1) {
                // 阳爻
                $html .= '<div style="width: 100%; height: 100%; background-color: #dc2626; border-radius: 2px;"></div>';
                $marker = $is_dong ? '<span style="color:#dc2626; font-weight:bold;">O</span>' : '';
            } else {
                // 阴爻
                $html .= '<div style="width: 44%; height: 100%; background-color: #2563eb; border-radius: 2px;"></div>';
                $html .= '<div style="width: 44%; height: 100%; background-color: #2563eb; border-radius: 2px;"></div>';
                $marker = $is_dong ? '<span style="color:#2563eb; font-weight:bold;">X</span>' : '';
            }

            // 动爻标记在右侧悬浮
            $html .= '<div style="position: absolute; right: -22px; top: -3px; font-size: 16px; line-height: 1;">' . $marker . '</div>';
            $html .= '</div>';
        }
        $html .= '</div>';
        return $html;
    }
}
?>

<style>
    .yfj-mh-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 24px; overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #0f172a; font-size: 16px; display: flex; align-items: center; gap: 8px; }
    .yfj-panel-body { padding: 20px; font-size: 14.5px; line-height: 1.8; }

    .yfj-desc-content { display: grid; grid-template-columns: 1fr; gap: 10px; }
    @media (min-width: 768px) { .yfj-desc-content { grid-template-columns: 1fr 1fr; } }
</style>

<div class="yfj-mh-wrapper">

    <!-- 1. 排盘基本信息 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-id-alt"></span> <?php echo $this->t('基本信息'); ?></div>
        <div class="yfj-panel-body" style="padding: 15px 20px;">
            <div style="background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0;">
                <p style="margin:0 0 8px 0;"><strong><?php echo $this->t('年命：'); ?></strong> <span style="color:#16a34a; font-weight:bold;"><?php echo esc_html($this->t($base['nianming'] ?? '')); ?></span></p>
                <p style="margin:0 0 8px 0;"><strong><?php echo $this->t('起卦：'); ?></strong> <span style="color:#2563eb; font-weight:bold;"><?php echo esc_html($this->t($base['model'] ?? '')); ?></span></p>
                <p style="margin:0 0 8px 0;"><strong><?php echo $this->t('公历：'); ?></strong> <?php echo esc_html($base['gongli'] ?? ''); ?></p>
                <p style="margin:0 0 8px 0;"><strong><?php echo $this->t('农历：'); ?></strong> <?php echo esc_html($base['nongli'] ?? ''); ?></p>
                <p style="margin:0;"><strong><?php echo $this->t('四柱：'); ?></strong> <span style="color:#dc2626; font-weight:bold;">
                    <?php echo esc_html(($sizhu['year_gan'] ?? '').($sizhu['year_zhi'] ?? '')) . ' '; ?>
                    <?php echo esc_html(($sizhu['month_gan'] ?? '').($sizhu['month_zhi'] ?? '')) . ' '; ?>
                    <?php echo esc_html(($sizhu['day_gan'] ?? '').($sizhu['day_zhi'] ?? '')) . ' '; ?>
                    <?php echo esc_html(($sizhu['hour_gan'] ?? '').($sizhu['hour_zhi'] ?? '')); ?>
                </span></p>
            </div>
        </div>
    </div>

    <!-- 2. 梅花易数卦象与断语 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-visibility"></span> <?php echo $this->t('梅花神机断'); ?></div>
        <div class="yfj-panel-body">

            <?php
            $gua_keys = [
                'bengua'  => ['title' => '主卦', 'sub' => '当前'],
                'hugua'   => ['title' => '互卦', 'sub' => '发展'],
                'biangua' => ['title' => '变卦', 'sub' => '结果'],
                'cuogua'  => ['title' => '错卦', 'sub' => '对立'],
                'zonggua' => ['title' => '综卦', 'sub' => '另一视角']
            ];

            $has_advanced = false;
            $empty_gua_text = $this->t('暂无数据');

            foreach ($gua_keys as $key => $names):
            $gua = $gua_info[$key] ?? [];
            if (empty($gua)) continue;

            // 高级折叠功能
            if (in_array($key, ['cuogua', 'zonggua']) && !$has_advanced) {
            $has_advanced = true;
            ?>
            <div style="text-align: center; margin: 15px 0 25px 0;">
                <button type="button" onclick="jQuery('#yfj-adv-guas').slideToggle(); jQuery(this).find('.yfj-adv-icon').toggleClass('dashicons-arrow-down-alt2 dashicons-arrow-up-alt2');"
                        style="background: transparent; border: 1px dashed #94a3b8; padding: 10px 24px; border-radius: 6px; color: #64748b; cursor: pointer; font-size: 14px; font-weight: normal; display: inline-flex; align-items: center; gap: 5px; transition: all 0.2s;">
                    <span class="dashicons dashicons-editor-expand"></span>
                    <?php echo $this->t('高级展开：错卦与综卦'); ?>
                    <span class="dashicons dashicons-arrow-down-alt2 yfj-adv-icon" style="font-size: 16px; line-height: 1;"></span>
                </button>
            </div>
            <div id="yfj-adv-guas" style="display: none;">
                <?php
                }

                // API 返回的 gua_mark 是从初爻(底)到上爻(顶)的顺序，比如 111110。
                // 截取前6位，然后反转数组，这样 $lines_array[0] 就是上爻，渲染时从上往下画，完美对应！
                $lines_array = [];
                if (!empty($gua['gua_mark'])) {
                    $clean_mark = substr(trim($gua['gua_mark']), 0, 6);
                    $lines_array = array_reverse(str_split($clean_mark));
                }
                $desc = $gua['gua_description'] ?? [];
                ?>
                <!-- 独立区块 -->
                <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 25px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">

                    <div style="display: flex; gap: 30px; align-items: center; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px dashed #cbd5e1; flex-wrap: wrap;">
                        <!-- 卦图绘制区 -->
                        <div style="text-align: center; width: 110px; margin: 0 auto;">
                            <div style="font-weight: bold; color: #0f172a; margin-bottom: 15px; font-size: 15px;">
                                <?php echo $this->t($names['title']) . ' (' . $this->t($names['sub']) . ')'; ?>
                            </div>
                            <!-- 只有主卦才画动爻圈叉标记 -->
                            <?php echo yfj_render_meihua_gua($lines_array, ($key == 'bengua') ? $dongyao_arr : [], $empty_gua_text); ?>
                            <div style="color: #dc2626; font-weight: bold; margin-top: 15px; font-size: 18px;"><?php echo esc_html($this->t($gua['gua_name'] ?? '')); ?></div>
                        </div>

                        <!-- 象辞解卦区 -->
                        <div style="flex: 1; min-width: 260px; font-size: 15px; line-height: 1.8;">
                            <span style="background: #fee2e2; color: #dc2626; padding: 3px 10px; border-radius: 4px; font-size: 13px; font-weight: bold; margin-bottom: 10px; display: inline-block; border: 1px solid #fecaca;">
                                <?php echo esc_html(!empty($gua['gua_xiongji']) ? $this->t($gua['gua_xiongji']) : $this->t('平')); ?>
                            </span>
                            <p style="margin:0 0 8px 0;"><strong><?php echo $this->t('象曰：'); ?></strong> <?php echo esc_html($this->t($gua['gua_qian'] ?? '')); ?></p>
                            <p style="margin:0 0 8px 0;"><strong><?php echo $this->t('爻辞：'); ?></strong> <span style="color: #b91c1c;"><?php echo esc_html($this->t($gua['gua_yaoci'] ?? '')); ?></span></p>
                            <p style="margin:0;"><strong><?php echo $this->t('解卦：'); ?></strong> <span style="color: #475569;"><?php echo esc_html($this->t($gua['gua_qian_desc'] ?? '')); ?></span></p>
                        </div>
                    </div>

                    <div class="yfj-desc-content">
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
                <?php endforeach;

                if ($has_advanced) {
                    echo '</div>';
                }
                ?>

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