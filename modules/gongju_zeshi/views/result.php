<?php
// 安全拦截
if (empty($data) || !is_array($data)) {
    echo '<div style="color:red; text-align:center; padding: 20px;">' . $this->t('未获取到有效的吉日数据，请尝试放宽查询范围。') . '</div>';
    return;
}
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

    /* 吉日列表样式 */
    .yfj-jiri-list { display: flex; flex-direction: column; gap: 15px; }
    .yfj-jiri-card { display: flex; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; background: #fff; transition: all 0.2s; }
    .yfj-jiri-card:hover { border-color: #cbd5e1; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }

    /* 左侧日历区块 */
    .yfj-jiri-date-box { width: 110px; min-width: 110px; background: #fef2f2; border-right: 1px dashed #fecaca; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 15px 10px; text-align: center; }
    .yfj-jiri-day { font-size: 26px; font-weight: 800; color: #dc2626; line-height: 1; margin-bottom: 4px; }
    .yfj-jiri-ym { font-size: 13px; color: #991b1b; font-weight: 500; }
    .yfj-jiri-week { font-size: 12px; color: #fff; background: #dc2626; padding: 2px 8px; border-radius: 10px; margin-top: 8px; }

    /* 右侧宜忌区块 */
    .yfj-jiri-info-box { flex: 1; padding: 15px; display: flex; flex-direction: column; justify-content: center; gap: 8px; font-size: 14px; }
    .yfj-yiji-row { display: flex; align-items: flex-start; gap: 8px; }
    .yfj-badge { font-size: 12px; font-weight: bold; color: #fff; padding: 2px 6px; border-radius: 4px; white-space: nowrap; }
    .yfj-badge-yi { background: #16a34a; }
    .yfj-badge-ji { background: #dc2626; }
    .yfj-badge-chong { background: #d97706; }
    .yfj-yiji-content { line-height: 1.5; color: #334155; }

    @media (max-width: 480px) {
        .yfj-jiri-card { flex-direction: column; }
        .yfj-jiri-date-box { width: 100%; border-right: none; border-bottom: 1px dashed #fecaca; flex-direction: row; gap: 10px; padding: 12px; justify-content: flex-start; }
        .yfj-jiri-day { font-size: 20px; margin-bottom: 0; }
        .yfj-jiri-week { margin-top: 0; }
    }
</style>

<div class="yfj-result-wrapper">

    <!-- 1. 查询概述 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-text-page"></span> <?php echo $this->t('吉日查询概述'); ?>
        </div>
        <div class="yfj-panel-body" style="font-size: 15px; color: #0f172a; background: #f8fafc;">
            <?php echo esc_html($data['base_info']['summarize']); ?>
        </div>
    </div>

    <!-- 2. 吉日列表 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-calendar-alt"></span> <?php echo $this->t('为您精选的黄道吉日'); ?>
        </div>
        <div class="yfj-panel-body">

            <?php if(empty($data['detail_info'])): ?>
                <div style="text-align: center; color: #64748b; padding: 30px 0;">
                    <?php echo $this->t('在您选择的时间范围内，暂无符合该事项的绝对吉日。建议放宽时间范围重新查询。'); ?>
                </div>
            <?php else: ?>
                <div class="yfj-jiri-list">
                    <?php foreach ((array)$data['detail_info'] as $vo):
                        // 解析日期用于分块显示 (假设格式为 2026-05-01)
                        $date_parts = explode('-', $vo['yangli']);
                        $year_month = isset($date_parts[0]) && isset($date_parts[1]) ? $date_parts[0] . '-' . $date_parts[1] : '';
                        $day = isset($date_parts[2]) ? $date_parts[2] : $vo['yangli'];
                        ?>
                        <div class="yfj-jiri-card">
                            <!-- 左侧日历 -->
                            <div class="yfj-jiri-date-box">
                                <div class="yfj-jiri-day"><?php echo esc_html($day); ?></div>
                                <div class="yfj-jiri-ym"><?php echo esc_html($year_month); ?></div>
                                <div class="yfj-jiri-week"><?php echo esc_html($vo['xingqi']); ?></div>
                            </div>

                            <!-- 右侧详情 -->
                            <div class="yfj-jiri-info-box">
                                <div class="yfj-yiji-row">
                                    <span class="yfj-badge yfj-badge-yi"><?php echo $this->t('宜'); ?></span>
                                    <span class="yfj-yiji-content"><?php echo esc_html($vo['yi']); ?></span>
                                </div>
                                <div class="yfj-yiji-row">
                                    <span class="yfj-badge yfj-badge-ji"><?php echo $this->t('忌'); ?></span>
                                    <span class="yfj-yiji-content"><?php echo esc_html($vo['ji']); ?></span>
                                </div>
                                <div class="yfj-yiji-row">
                                    <span class="yfj-badge yfj-badge-chong"><?php echo $this->t('冲'); ?></span>
                                    <span class="yfj-yiji-content"><?php echo esc_html($vo['chongsha']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- 3. 测算告诫 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-warning"></span> <?php echo $this->t('择日指南'); ?>
        </div>
        <div class="yfj-panel-body" style="font-size: 13.5px;">
            <p style="margin-top:0;">1. <?php echo $this->t('查询黄道吉日时，最好先取日之宜忌，确定吉日后，再进一步通过老黄历查看当日各个时辰的吉凶（时之宜忌）。'); ?></p>
            <p style="margin-bottom:0;">2. <?php echo $this->t('所谓心诚则灵，吉日测算结果为传统易学逻辑推演，仅供参考。重大事项建议结合自身实际情况统筹安排。'); ?></p>
        </div>
    </div>

    <!-- 返回按钮 -->
    <div style="text-align: center; margin-top: 10px;">
        <button onclick="jQuery('.yfj-result-area').hide(); jQuery('.yfj-ajax-form').fadeIn();"
                style="background: #e2e8f0; color: #334155; border: none; padding: 10px 24px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            <?php echo $this->t('重新查询'); ?>
        </button>
    </div>

</div>