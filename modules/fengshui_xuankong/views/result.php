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

$base = $data['list'] ?? $data['data'] ?? $data;
$pan_info = $base['pan_info'] ?? [];
?>

<style>
    .yfj-xk-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 24px; overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #0f172a; font-size: 16px; display: flex; align-items: center; gap: 8px; }
    .yfj-panel-body { padding: 20px; font-size: 14.5px; line-height: 1.8; }

    .yfj-info-box { background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0; display: grid; grid-template-columns: 1fr; gap: 10px; margin-bottom: 15px; }
    @media (min-width: 768px) { .yfj-info-box { grid-template-columns: 1fr 1fr; } }
    .yfj-info-item { margin: 0 0 8px 0; }

    /* 核心：玄空飞星九宫格布局 */
    .yfj-jiugong-container { width: 100%; max-width: 400px; margin: 0 auto 20px auto; border: 2px solid #94a3b8; border-radius: 4px; overflow: hidden; }
    .yfj-jiugong-grid { display: grid; grid-template-columns: repeat(3, 1fr); grid-template-rows: repeat(3, 1fr); aspect-ratio: 1 / 1; }
    .yfj-jiugong-cell { border: 1px solid #cbd5e1; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 10px; background: #fff; position: relative; }
    /* 山盘(左上)与向盘(右上) */
    .yfj-cell-top { display: flex; justify-content: space-between; width: 100%; font-size: 18px; font-weight: bold; color: #0f172a; }
    /* 运盘(正中) */
    .yfj-cell-mid { font-size: 24px; font-weight: bold; color: #dc2626; margin: 5px 0; }
    /* 地盘(底部浅色) */
    .yfj-cell-bot { font-size: 12px; color: #94a3b8; }

    /* 排龙诀辅助样式 */
    .yfj-longjue-red { color: #dc2626; font-weight: bold; }
</style>

<div class="yfj-xk-wrapper">

    <!-- 1. 基本信息 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-id-alt"></span> <?php echo $this->t('基本信息'); ?></div>
        <div class="yfj-panel-body">
            <div class="yfj-info-box">
                <div>
                    <p class="yfj-info-item"><strong><?php echo $this->t('元运：'); ?></strong> <span><?php echo esc_html($this->t($base['yuanyun'] ?? '')); ?> (<?php echo esc_html($this->t($base['yuanyun_qizhi'] ?? '')); ?>)</span></p>
                    <p class="yfj-info-item" style="margin: 0;"><strong><?php echo $this->t('山向：'); ?></strong> <span style="color:#dc2626; font-weight:bold;"><?php echo esc_html($this->t($base['shanxiang'] ?? '')); ?></span></p>
                </div>
                <div>
                    <p class="yfj-info-item" style="margin: 0;">
                        <strong><?php echo esc_html(sprintf($this->t('%s山：'), $this->t($base['shan'] ?? ''))); ?></strong>
                        <span style="color:#dc2626; font-weight:bold;"><?php echo esc_html($this->t($base['simple_desc'] ?? '')); ?></span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. 飞星盘 -->
    <?php if (!empty($pan_info['feixing_gong'])): ?>
        <div class="yfj-panel">
            <div class="yfj-panel-heading"><span class="dashicons dashicons-grid-view"></span> <?php echo $this->t('飞星盘'); ?> (<?php echo esc_html($this->t($base['yongti'] ?? '')); ?>)</div>
            <div class="yfj-panel-body">

                <div class="yfj-jiugong-container">
                    <div class="yfj-jiugong-grid">
                        <?php
                        // 玄空排盘标准九宫位置顺序 (按照数组的固定索引顺序排布)
                        // 上排：巽(3) 离(4) 坤(5)
                        // 中排：震(2) 中(8) 兑(6)
                        // 下排：艮(1) 坎(0) 乾(7)
                        $gong_map = [3, 4, 5, 2, 8, 6, 1, 0, 7];
                        foreach ($gong_map as $idx):
                            $gong = $pan_info['feixing_gong'][$idx] ?? [];
                            if (empty($gong)) {
                                echo '<div class="yfj-jiugong-cell"></div>';
                                continue;
                            }
                            ?>
                            <div class="yfj-jiugong-cell">
                                <div class="yfj-cell-top">
                                    <span><?php echo esc_html($this->t($gong['shanpan'] ?? '')); ?></span>
                                    <span><?php echo esc_html($this->t($gong['xiangpan'] ?? '')); ?></span>
                                </div>
                                <div class="yfj-cell-mid"><?php echo esc_html($this->t($gong['yunpan'] ?? '')); ?></div>
                                <div class="yfj-cell-bot"><?php echo esc_html($this->t($gong['dipan'] ?? '')); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>
    <?php endif; ?>

    <!-- 3. 排龙诀 (可选) -->
    <?php if (!empty($pan_info['longjue_gong'])): ?>
        <div class="yfj-panel">
            <div class="yfj-panel-heading"><span class="dashicons dashicons-randomize"></span> <?php echo $this->t('排龙诀'); ?> (<?php echo esc_html(sprintf($this->t('水口在%s'), $this->t($base['shuikou'] ?? ''))); ?>)</div>
            <div class="yfj-panel-body">
                <div class="yfj-jiugong-container" style="max-width: 500px; margin-bottom: 0;">
                    <!-- 完美的 4x4 回字形格阵 -->
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr);">
                        <?php
                        $lj = $pan_info['longjue_gong'];
                        // 16格映射：十二地支环绕，中间掏空
                        $longjue_map = [
                            5, 6, 7, 8,
                            4, 'empty', 'empty', 9,
                            3, 'empty', 'empty', 10,
                            2, 1, 0, 11
                        ];

                        foreach ($longjue_map as $idx):
                            if ($idx === 'empty') {
                                // 中间掏空的格子
                                echo '<div></div>';
                                continue;
                            }
                            $g = $lj[$idx] ?? [];
                            $is_red = in_array($g['xingyaopan'] ?? '', ['左辅', '贪狼', '武曲', '巨门', '右弼']);
                            ?>
                            <div class="yfj-jiugong-cell" style="padding: 15px 5px;">
                                <div style="font-size: 15px; font-weight: bold; <?php echo $is_red ? 'color: #dc2626;' : ''; ?>">
                                    <?php echo esc_html($this->t($g['xingyaopan'] ?? '')); ?>
                                </div>
                                <div class="yfj-cell-bot"><?php echo esc_html($this->t($g['dipan'] ?? '')); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- 4. 流年命盘 (可选) -->
    <?php if (!empty($pan_info['mingpan_gong'])): ?>
        <div class="yfj-panel">
            <div class="yfj-panel-heading"><span class="dashicons dashicons-clock"></span> <?php echo $this->t('流年流月命盘'); ?> (<?php echo esc_html(sprintf($this->t('命卦：%s'), $this->t($base['minggua'] ?? ''))); ?>)</div>
            <div class="yfj-panel-body">
                <div class="yfj-jiugong-container" style="margin-bottom: 0;">
                    <div class="yfj-jiugong-grid">
                        <?php
                        $ming_map = [3, 4, 5, 2, 8, 6, 1, 0, 7];
                        foreach ($ming_map as $idx):
                            $gong = $pan_info['mingpan_gong'][$idx] ?? [];
                            if (empty($gong)) {
                                echo '<div class="yfj-jiugong-cell"></div>';
                                continue;
                            }
                            ?>
                            <div class="yfj-jiugong-cell">
                                <div class="yfj-cell-top" style="color: #2563eb;">
                                    <span><?php echo esc_html($this->t($gong['nianpan'] ?? '')); ?></span>
                                    <span><?php echo esc_html($this->t($gong['yuepan'] ?? '')); ?></span>
                                </div>
                                <div class="yfj-cell-bot" style="margin-top: 10px; font-weight:bold; color: #475569;"><?php echo esc_html($this->t($gong['mingpan'] ?? '')); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- 5. 九宫批示 (独立成卡片) -->
    <?php if (!empty($pan_info['feixing_gong'])): ?>
        <?php
        $has_desc = false;
        foreach ($pan_info['feixing_gong'] as $vo) {
            if (!empty($vo['feixing_desc'])) {
                $has_desc = true;
                break;
            }
        }
        if ($has_desc):
            ?>
            <div class="yfj-panel">
                <div class="yfj-panel-heading"><span class="dashicons dashicons-text-page"></span> <?php echo $this->t('九宫飞星批示'); ?></div>
                <div class="yfj-panel-body">
                    <div class="yfj-desc-content">
                        <?php foreach ($pan_info['feixing_gong'] as $vo): if (empty($vo['feixing_desc'])) continue; ?>
                            <div style="background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 14px; line-height: 1.8;">
                            <span style="font-weight: bold; color: #b45309; display: block; margin-bottom: 6px; font-size: 15px;">
                                [<?php echo esc_html($this->t($vo['dipan'] ?? '')); ?>]
                            </span>
                                <?php echo esc_html($this->t($vo['feixing_desc'])); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

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