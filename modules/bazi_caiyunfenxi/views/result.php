<?php
// 安全拦截
if (empty($data) || !is_array($data)) {
    echo '<div style="color:red; text-align:center; padding: 20px;">' . $this->t('暂无数据') . '</div>';
    return;
}

$base = $data['base_info'] ?? [];
$bazi = $data['bazi_info'] ?? [];
$cy   = $data['caiyun_info'] ?? [];

// 提取分数
$score = intval($cy['yearlyOverallFortuneScore'] ?? 0);
$score_color = $score >= 80 ? '#dc2626' : ($score >= 60 ? '#d97706' : '#2563eb');
?>

<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fffbeb; padding:12px 15px; color:#b45309; margin-bottom:20px; border-radius:6px; border-left: 4px solid #fcd34d; font-size: 14px;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<style>
    .yfj-cy-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid #e2e8f0; font-weight: 600; font-size: 16px; color: #0f172a; display: flex; align-items: center; gap: 8px; }
    .yfj-panel-body { padding: 20px; font-size: 14.5px; line-height: 1.8; color: #475569; }

    /* 基础信息区 */
    .yfj-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; }
    .yfj-info-grid p { margin: 0; }

    /* 八字表格美化 */
    .yfj-bazi-table { width: 100%; border-collapse: collapse; margin-top: 15px; text-align: center; font-size: 14px; }
    .yfj-bazi-table th, .yfj-bazi-table td { border: 1px solid #e2e8f0; padding: 10px 5px; }
    .yfj-bazi-table th { background: #f1f5f9; color: #475569; font-weight: 600; }
    .yfj-bazi-table tr:hover { background: #f8fafc; }
    .yfj-bazi-col-label { font-weight: bold; background: #f8fafc; width: 60px; }

    /* 财运模块定制 */
    .yfj-cy-score-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 25px; text-align: center; margin-bottom: 25px; }
    .yfj-cy-score-title { font-size: 16px; color: #92400e; font-weight: bold; margin-bottom: 10px; }
    .yfj-cy-score-num { font-size: 48px; font-weight: 900; line-height: 1; }

    .yfj-cy-item { margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px dashed #e2e8f0; }
    .yfj-cy-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .yfj-cy-title { font-weight: bold; color: #b45309; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; font-size: 15px; }
    .yfj-cy-content { background: #fafaf9; padding: 12px 15px; border-radius: 6px; border-left: 3px solid #d97706; }

    /* 12个月运势网格 */
    .yfj-month-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 15px; margin-top: 15px; }
    .yfj-month-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; }
    .yfj-month-header { background: #f8fafc; padding: 8px 12px; font-weight: bold; color: #0f172a; border-bottom: 1px solid #e2e8f0; text-align: center; }
    .yfj-month-body { padding: 12px; font-size: 13.5px; color: #475569; }

    /* 警示与大师建议 */
    .yfj-alert-box { background: #fef2f2; border: 1px solid #fecaca; border-left: 4px solid #ef4444; padding: 15px; border-radius: 6px; margin-top: 20px; }
    .yfj-master-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-left: 4px solid #22c55e; padding: 15px; border-radius: 6px; margin-top: 20px; }
</style>

<div class="yfj-cy-wrapper">

    <!-- 1. 八字基础排盘 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-id"></span> <?php echo $this->t('八字排盘'); ?>
        </div>
        <div class="yfj-panel-body">
            <div class="yfj-info-grid">
                <p><strong><?php echo $this->t('命主姓名：'); ?></strong> <?php echo esc_html($base['name'] ?? ''); ?></p>
                <p><strong><?php echo $this->t('出生公历：'); ?></strong> <?php echo esc_html($base['gongli'] ?? ''); ?></p>
                <p><strong><?php echo $this->t('出生农历：'); ?></strong> <?php echo esc_html($base['nongli'] ?? ''); ?></p>
                <p><strong><?php echo $this->t('起运/交运：'); ?></strong> <?php echo esc_html($base['qiyun'] ?? ''); ?> (<?php echo esc_html($base['jiaoyun'] ?? ''); ?>)</p>
                <p><strong><?php echo $this->t('大运流年：'); ?></strong> <?php echo esc_html($base['dayun'] ?? ''); ?> / <?php echo esc_html($base['liunian'] ?? ''); ?></p>
                <p><strong><?php echo $this->t('八字强弱：'); ?></strong> <?php echo esc_html($this->t($base['qiangruo'] ?? '-')); ?></p>
                <p><strong><?php echo $this->t('命理格局：'); ?></strong> <?php echo esc_html($this->t($base['zhengge'] ?? '-')); ?></p>
            </div>

            <table class="yfj-bazi-table">
                <tbody>
                <tr style="color: #2563eb;">
                    <td class="yfj-bazi-col-label">#</td>
                    <?php foreach(($bazi['tg_cg_god'] ?? []) as $v): ?>
                        <td><?php echo esc_html($this->t($v)); ?></td>
                    <?php endforeach; ?>
                    <td>#</td>
                </tr>
                <tr style="color: #dc2626; font-size: 16px; font-weight: bold;">
                    <td class="yfj-bazi-col-label" style="color: #475569; font-weight: normal; font-size: 14px;"><?php echo esc_html($this->t($base['sex'] ?? '')); ?></td>
                    <?php foreach(($bazi['bazi'] ?? []) as $v): ?>
                        <td><?php echo esc_html($this->t($v)); ?></td>
                    <?php endforeach; ?>
                    <td style="font-size: 13px; font-weight: normal; color: #64748b;">(<?php echo esc_html($this->t($bazi['kw'] ?? '')); ?><?php echo $this->t('空'); ?>)</td>
                </tr>
                <tr>
                    <td class="yfj-bazi-col-label"><?php echo $this->t('藏干'); ?></td>
                    <?php foreach(($bazi['dz_cg'] ?? []) as $v): ?>
                        <td><?php echo esc_html($this->t($v)); ?></td>
                    <?php endforeach; ?>
                    <td>#</td>
                </tr>
                <tr>
                    <td class="yfj-bazi-col-label"></td>
                    <?php foreach(($bazi['dz_cg_god'] ?? []) as $v): ?>
                        <td><?php echo esc_html($this->t($v)); ?></td>
                    <?php endforeach; ?>
                    <td>#</td>
                </tr>
                <tr>
                    <td class="yfj-bazi-col-label"><?php echo $this->t('衰旺'); ?></td>
                    <?php foreach(($bazi['day_cs'] ?? []) as $v): ?>
                        <td><?php echo esc_html($this->t($v)); ?></td>
                    <?php endforeach; ?>
                    <td>#</td>
                </tr>
                <tr style="color: #16a34a;">
                    <td class="yfj-bazi-col-label"><?php echo $this->t('纳音'); ?></td>
                    <?php foreach(($bazi['na_yin'] ?? []) as $v): ?>
                        <td><?php echo esc_html($this->t($v)); ?></td>
                    <?php endforeach; ?>
                    <td>#</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. 财运深度解析 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-money-alt" style="color: #d97706;"></span> <?php echo $this->t('流年财运深度解析'); ?>
        </div>
        <div class="yfj-panel-body">

            <!-- 大分展示 -->
            <div class="yfj-cy-score-box">
                <div class="yfj-cy-score-title"><?php echo $this->t('流年整体财运指数'); ?></div>
                <div class="yfj-cy-score-num" style="color: <?php echo $score_color; ?>;">
                    <?php echo $score; ?><span style="font-size: 20px; font-weight: normal;"> <?php echo $this->t('分'); ?></span>
                </div>
            </div>

            <!-- 详细断语条目 -->
            <div class="yfj-cy-item">
                <div class="yfj-cy-title"><span class="dashicons dashicons-chart-pie"></span> <?php echo $this->t('先天财运格局'); ?></div>
                <div class="yfj-cy-content"><?php echo esc_html($this->t($cy['innateFortunePattern'] ?? '暂无数据')); ?></div>
            </div>

            <div class="yfj-cy-item">
                <div class="yfj-cy-title"><span class="dashicons dashicons-star-filled"></span> <?php echo $this->t('流年财星剖析'); ?></div>
                <div class="yfj-cy-content">
                    <p style="margin: 0 0 5px 0;"><strong><?php echo $this->t('类别：'); ?></strong> <?php echo esc_html($this->t($cy['yearlyFortuneStarCategory'] ?? '暂无数据')); ?></p>
                    <p style="margin: 0;"><strong><?php echo $this->t('显隐：'); ?></strong> <?php echo esc_html($this->t($cy['yearlyFortuneStarVisibility'] ?? '暂无数据')); ?></p>
                </div>
            </div>

            <div class="yfj-cy-item">
                <div class="yfj-cy-title"><span class="dashicons dashicons-update"></span> <?php echo $this->t('命理干支作用'); ?></div>
                <div class="yfj-cy-content">
                    <ul style="margin: 0; padding-left: 20px;">
                        <li><strong><?php echo $this->t('大运与流年：'); ?></strong> <?php echo esc_html($this->t($cy['majorFortunePeriodVsYearly'] ?? '暂无数据')); ?></li>
                        <li><strong><?php echo $this->t('身强与身弱：'); ?></strong> <?php echo esc_html($this->t($cy['bodyStrengthVsWeakness'] ?? '暂无数据')); ?></li>
                        <li><strong><?php echo $this->t('流年与命支：'); ?></strong> <?php echo esc_html($this->t($cy['yearlyVsDestinyBranch'] ?? '暂无数据')); ?></li>
                    </ul>
                </div>
            </div>

            <div class="yfj-cy-item">
                <div class="yfj-cy-title"><span class="dashicons dashicons-vault"></span> <?php echo $this->t('流年财库信息'); ?></div>
                <div class="yfj-cy-content"><?php echo esc_html($this->t($cy['yearlyFortuneTreasuryInfo'] ?? '暂无数据')); ?></div>
            </div>

            <div class="yfj-cy-item" style="border-bottom: none; padding-bottom: 0;">
                <div class="yfj-cy-title"><span class="dashicons dashicons-calendar-alt"></span> <?php echo $this->t('流年每月财运及投资提示'); ?></div>
                <div class="yfj-month-grid">
                    <?php
                    $months = $cy['yearlyMonthlyFortuneAndInvestmentTips'] ?? [];
                    for ($i = 0; $i < 12; $i++):
                        $m_text = $months[$i] ?? $this->t('暂无数据');
                        ?>
                        <div class="yfj-month-card">
                            <div class="yfj-month-header"><?php echo ($i + 1) . $this->t('月'); ?></div>
                            <div class="yfj-month-body"><?php echo esc_html($this->t($m_text)); ?></div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

        </div>
    </div>

    <!-- 3. 大师护航建议 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-shield"></span> <?php echo $this->t('财富化解与提升护航'); ?>
        </div>
        <div class="yfj-panel-body" style="padding-top: 0;">

            <div class="yfj-alert-box">
                <strong style="color: #b91c1c; display: block; margin-bottom: 5px;"><span class="dashicons dashicons-warning"></span> <?php echo $this->t('漏财风险防范提示：'); ?></strong>
                <span style="color: #991b1b;"><?php echo esc_html($this->t($cy['fortuneLeakageRiskTips'] ?? '今年财务尚属平稳，正常开销即可。')); ?></span>
            </div>

            <div class="yfj-master-box">
                <strong style="color: #166534; display: block; margin-bottom: 5px;"><span class="dashicons dashicons-lightbulb"></span> <?php echo $this->t('提升财运执行建议：'); ?></strong>
                <span style="color: #15803d;"><?php echo esc_html($this->t($cy['fortuneImprovementSuggestions'] ?? '多劳多得，顺势而为。')); ?></span>
            </div>

            <div style="margin-top: 20px; padding: 15px; border-radius: 6px; background: #f8fafc; border: 1px solid #e2e8f0;">
                <strong style="display: block; margin-bottom: 5px; color: #0f172a;"><span class="dashicons dashicons-admin-users"></span> <?php echo $this->t('大师总评寄语：'); ?></strong>
                <span style="color: #475569;"><?php echo esc_html($this->t($cy['masterFortuneSuggestions'] ?? '祝缘主财源广进，万事如意。')); ?></span>
            </div>

        </div>
    </div>

    <!-- 测算告诫，免责声明 -->
    <?php echo $this->get_disclaimer_html(); ?>

    <!-- 返回按钮 -->
    <div style="text-align: center; margin-top: 10px;">
        <button onclick="jQuery('.yfj-result-area').hide(); jQuery('.yfj-ajax-form').fadeIn();"
                style="background: #e2e8f0; color: #334155; border: none; padding: 10px 24px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            <?php echo $this->t('返回重测'); ?>
        </button>
    </div>

</div>