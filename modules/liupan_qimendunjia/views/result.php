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

// 提取数据
$base = $data;
$sizhu = $data['sizhu_info'] ?? [];
$xunkong = $data['xunkong_info'] ?? [];
$xunshou = $data['xunshou_info'] ?? [];
$zhifu = $data['zhifu_info'] ?? [];
$gong_pan = $data['gong_pan'] ?? [];

// 定义标记渲染辅助函数，用于处理 入墓/击刑/门迫等 高亮状态
function yfj_get_qm_mark_style($is_rumu, $is_jixing, $is_menpo = 0) {
    if ($is_menpo == 1) return 'background: #dc2626; color: #fff;'; // 门迫 红
    if ($is_rumu == 1 && $is_jixing == 1) return 'background: #3b82f6; color: #fff;'; // 刑+墓 蓝
    if ($is_jixing == 1) return 'background: #d946ef; color: #fff;'; // 击刑 紫
    if ($is_rumu == 1) return 'background: #eab308; color: #fff;'; // 入墓 黄
    return '';
}

// 九宫格的物理渲染顺序 (从左到右，从上到下：巽4-离9-坤2, 震3-中5-兑7, 艮8-坎1-乾6)
$gong_order = [3, 4, 5, 2, 8, 6, 1, 0, 7]; // 对应你的 $list.gong_pan.X 索引
?>

<style>
    .yfj-qm-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 24px; overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 12px 15px; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #0f172a; font-size: 16px; }
    .yfj-panel-body { padding: 20px; font-size: 14px; line-height: 1.8; }

    /* 基础信息区 */
    .yfj-qm-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px; }
    .yfj-qm-info-col { background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0; }
    .yfj-qm-info-col p { margin: 0 0 5px 0; }
    .yfj-qm-highlight { color: #dc2626; font-weight: bold; }
    .yfj-qm-blue { color: #2563eb; font-weight: bold; }
    .yfj-qm-green { color: #16a34a; font-weight: bold; }

    /* 核心九宫格布局 */
    .yfj-jiugong-container { max-width: 600px; margin: 0 auto 20px auto; display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px; background: #cbd5e1; padding: 4px; border-radius: 8px; }

    /* 单个宫位样式 */
    .yfj-gong-box { background: #fff; padding: 10px; border-radius: 4px; min-height: 120px; display: flex; flex-direction: column; justify-content: space-between; border: 1px solid #fff; transition: 0.3s; }
    .yfj-gong-box:hover { border-color: #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

    /* 四角排布 */
    .yfj-gong-top { display: flex; justify-content: space-between; align-items: center; font-size: 14px; margin-bottom: 5px; }
    .yfj-gong-mid { display: flex; justify-content: space-between; align-items: center; font-size: 16px; font-weight: bold; margin: 5px 0; }
    .yfj-gong-bottom { display: flex; justify-content: space-between; align-items: flex-end; font-size: 14px; margin-top: auto; }

    /* 特殊标记 */
    .yfj-gong-yingan { color: #94a3b8; font-size: 12px; }

    /* 新版：马星与空亡的标签样式 */
    .yfj-qm-marks { display: flex; gap: 4px; font-size: 12px; font-weight: bold; line-height: 1.2; }
    .yfj-mark-ma { color: #dc2626; background: #fef2f2; border: 1px solid #fecaca; padding: 1px 4px; border-radius: 3px; }
    .yfj-mark-kong { color: #2563eb; background: #eff6ff; border: 1px solid #bfdbfe; padding: 1px 4px; border-radius: 3px; }

    /* 特殊状态的 Span 包装器 */
    .yfj-status-span { display: inline-block; padding: 2px 4px; border-radius: 4px; line-height: 1; }

    /* 状态颜色说明图例 */
    .yfj-qm-legend { text-align: center; font-size: 13px; margin-top: 15px; padding: 10px; background: #f8fafc; border-radius: 6px; }
    .yfj-legend-item { display: inline-block; padding: 2px 6px; border-radius: 4px; color: #fff; margin: 0 5px; font-weight: bold; }
</style>

<div class="yfj-qm-wrapper">
    <!-- 1. 基本信息 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-id-alt"></span> <?php echo $this->t('基本信息'); ?></div>
        <div class="yfj-panel-body">
            <div class="yfj-qm-info-grid">
                <div class="yfj-qm-info-col">
                    <p><strong><?php echo $this->t('姓名：'); ?></strong> <?php echo esc_html($base['name']); ?> (<?php echo esc_html($base['sex']); ?>)</p>
                    <p><strong><?php echo $this->t('公历：'); ?></strong> <?php echo esc_html($base['gongli']); ?></p>
                    <p><strong><?php echo $this->t('农历：'); ?></strong> <?php echo esc_html($base['nongli']); ?></p>
                    <p><strong><?php echo $this->t('上一节气：'); ?></strong> <?php echo esc_html($base['jieqi_pre']); ?> </p>
                    <p><strong><?php echo $this->t('下一节气：'); ?></strong> <?php echo esc_html($base['jieqi_next']); ?> </p>
                </div>
                <div class="yfj-qm-info-col">
                    <p><strong><?php echo $this->t('四柱：'); ?></strong> <span class="yfj-qm-highlight"><?php echo esc_html($sizhu['year_gan'].$sizhu['year_zhi']); ?> &nbsp; <?php echo esc_html($sizhu['month_gan'].$sizhu['month_zhi']); ?> &nbsp; <?php echo esc_html($sizhu['day_gan'].$sizhu['day_zhi']); ?> &nbsp; <?php echo esc_html($sizhu['hour_gan'].$sizhu['hour_zhi']); ?></span></p>
                    <p><strong><?php echo $this->t('旬空：'); ?></strong> <span class="yfj-qm-blue"><?php echo esc_html($xunkong['year_xunkong']); ?> <?php echo esc_html($xunkong['month_xunkong']); ?> <?php echo esc_html($xunkong['day_xunkong']); ?> <?php echo esc_html($xunkong['hour_xunkong']); ?> </span></p>
                    <p><strong><?php echo $this->t('旬首：'); ?></strong> <span class="yfj-qm-green"><?php echo esc_html($base['xunshou'] ?? ''); ?></span> <span class="yfj-yp-highlight"><?php echo esc_html($base['kongwang'] ?? ''); ?></span></p>
                    <p><strong><?php echo $this->t('定局：'); ?></strong> <span class="yfj-qm-highlight"><?php echo esc_html($base['dunju']); ?></span> <?php echo esc_html($base['dingju']); ?> <?php echo esc_html($base['panlei']); ?></p>
                    <p><strong><?php echo $this->t('值符：'); ?></strong> <span class="yfj-qm-highlight"><?php echo esc_html($zhifu['zhifu_name'] ?? ''); ?></span><?php echo $this->t('落'); ?><?php echo esc_html($zhifu['zhifu_luogong'] ?? ''); ?><?php echo $this->t('宫'); ?></p>
                    <p><strong><?php echo $this->t('值使：'); ?></strong> <span class="yfj-qm-highlight"><?php echo esc_html($zhifu['zhishi_name'] ?? ''); ?></span><?php echo $this->t('落'); ?><?php echo esc_html($zhifu['zhishi_luogong'] ?? ''); ?><?php echo $this->t('宫'); ?></p>

                </div>
            </div>
        </div>
    </div>

    <!-- 2. 九宫格奇门盘 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-grid-view"></span> <?php echo $this->t('奇门遁甲盘'); ?></div>
        <div class="yfj-panel-body">

            <div class="yfj-jiugong-container">
                <?php foreach($gong_order as $idx):
                    $g = $gong_pan[$idx] ?? [];
                    if(empty($g)) continue;
                    ?>
                    <div class="yfj-gong-box">
                        <!-- 右上角 马星 / 空亡 角标 -->
                        <div class="yfj-gong-badge">
                            <?php if(($g['is_maxing'] ?? 0) == 1): ?><span class="yfj-badge-item" style="color: #dc2626;"><?php echo $this->t('马'); ?></span><?php endif; ?>
                            <?php if(($g['is_kongwang'] ?? 0) == 1): ?><span class="yfj-badge-item" style="color: #2563eb;">〇</span><?php endif; ?>
                        </div>

                        <!-- 顶部：暗干 | (马空角标) + 八神 -->
                        <div class="yfj-gong-top">
                            <span class="yfj-gong-yingan"><?php echo esc_html($g['yingan'] ?? ''); ?></span>

                            <!-- 将角标和八神用 Flex 组合，设置 8px 的间距，彻底告别拥挤 -->
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="yfj-qm-marks">
                                    <?php if(($g['is_maxing'] ?? 0) == 1): ?>
                                        <span class="yfj-mark-ma"><?php echo $this->t('马'); ?></span>
                                    <?php endif; ?>
                                    <?php if(($g['is_kongwang'] ?? 0) == 1): ?>
                                        <span class="yfj-mark-kong">〇</span>
                                    <?php endif; ?>
                                </div>
                                <span style="color: #475569;"><?php echo esc_html($g['shenpan']['bashen'] ?? ''); ?></span>
                            </div>
                        </div>

                        <!-- 中部：九星 天禽 三奇六仪(天) -->
                        <div class="yfj-gong-mid" style="justify-content: center; gap: 8px;">
                            <?php
                            // 处理天禽星三奇六仪的高亮
                            $tianqin_style = yfj_get_qm_mark_style(
                                $g['tianpan']['is_tianqin_sanqiliuyi_rumu'] ?? 0,
                                $g['tianpan']['is_tianqin_sanqiliuyi_jixing'] ?? 0
                            );
                            $sqly_style = yfj_get_qm_mark_style(
                                $g['tianpan']['is_sanqiliuyi_rumu'] ?? 0,
                                $g['tianpan']['is_sanqiliuyi_jixing'] ?? 0
                            );
                            ?>

                            <!-- 天盘九星 -->
                            <div style="text-align: right; color: #475569;">
                                <div><?php echo esc_html($g['tianpan']['jiuxing_tianqin'] ?? ''); ?></div>
                                <div><?php echo esc_html($g['tianpan']['jiuxing'] ?? ''); ?></div>
                            </div>

                            <!-- 天盘三奇六仪 -->
                            <div style="text-align: left; color: #b91c1c;">
                                <div><span class="yfj-status-span" style="<?php echo $tianqin_style; ?>"><?php echo esc_html($g['tianpan']['jiuxing_tianqin_sanqiliuyi'] ?? ''); ?></span></div>
                                <div><span class="yfj-status-span" style="<?php echo $sqly_style; ?>"><?php echo esc_html($g['tianpan']['sanqiliuyi'] ?? ''); ?></span></div>
                            </div>
                        </div>

                        <!-- 底部：八门 | 地盘三奇六仪 | 八卦 -->
                        <div class="yfj-gong-bottom" style="align-items: flex-end;">
                            <?php
                            // 八门门迫高亮
                            $men_style = yfj_get_qm_mark_style(0, 0, $g['renpan']['is_bamen_menpo'] ?? 0);
                            // 地盘三奇高亮
                            $di_sqly_style = yfj_get_qm_mark_style(
                                $g['dipan']['is_sanqiliuyi_rumu'] ?? 0,
                                $g['dipan']['is_sanqiliuyi_jixing'] ?? 0
                            );
                            ?>
                            <div style="text-align:left;">
                                <div><?php echo esc_html($g['dipan']['bashen'] ?? ''); ?></div>
                                <div style="font-size: 16px; font-weight: bold;"><span class="yfj-status-span" style="<?php echo $men_style; ?>"><?php echo esc_html($g['renpan']['bamen'] ?? ''); ?></span></div>
                            </div>
                            <div style="text-align:right;">
                                <div style="color: #94a3b8; font-size: 12px;"><?php echo esc_html($g['dipan']['bagua'] ?? ''); ?></div>
                                <div style="font-size: 16px; font-weight: bold; color: #15803d;"><span class="yfj-status-span" style="<?php echo $di_sqly_style; ?>"><?php echo esc_html($g['dipan']['sanqiliuyi'] ?? ''); ?></span></div>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

            <!-- 图例说明[cite: 7] -->
            <div class="yfj-qm-legend">
                <?php echo $this->t('符号颜色说明：'); ?>
                <span class="yfj-legend-item" style="background: #eab308;"><?php echo $this->t('入墓'); ?></span>
                <span class="yfj-legend-item" style="background: #d946ef;"><?php echo $this->t('击刑'); ?></span>
                <span class="yfj-legend-item" style="background: #dc2626;"><?php echo $this->t('门迫'); ?></span>
                <span class="yfj-legend-item" style="background: #3b82f6;"><?php echo $this->t('刑+墓'); ?></span>
            </div>

        </div>
    </div>

    <!-- 3. 排盘简批 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-welcome-learn-more"></span> <?php echo $this->t('排盘简批'); ?></div>
        <div class="yfj-panel-body">
            <?php
            // 按照正常数字顺序展示简批 (1-9宫)
            for($i = 0; $i <= 8; $i++):
                $g = $gong_pan[$i] ?? [];
                if(empty($g) || empty($g['renpan']['bamen'])) continue;
                ?>
                <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px dashed #cbd5e1;">
                    <div style="font-size: 16px; font-weight: bold; color: #0f172a; margin-bottom: 8px;">
                        【<?php echo esc_html($g['dipan']['bagua'] ?? ''); ?><?php echo $this->t('宫'); ?>】 <?php echo esc_html($g['renpan']['bamen'] ?? ''); ?>
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

    <!-- 公共底部免责声明 -->
    <?php echo $this->get_disclaimer_html(); ?>

    <!-- 返回按钮 -->
    <div style="text-align: center; margin-top: 10px;">
        <button onclick="jQuery('.yfj-result-area').hide(); jQuery('.yfj-ajax-form').fadeIn();"
                style="background: #e2e8f0; color: #334155; border: none; padding: 10px 24px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            <?php echo $this->t('返回重排'); ?>
        </button>
    </div>

</div>