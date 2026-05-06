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
$sizhu = $base['sizhu_info'] ?? [];
$shensha = $base['shensha'] ?? [];
$pan_info = $base['pan_info'] ?? [];
?>

<style>
    .yfj-jkj-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 24px; overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #0f172a; font-size: 16px; display: flex; align-items: center; gap: 8px; }
    .yfj-panel-body { padding: 20px; font-size: 14.5px; line-height: 1.8; }

    .yfj-info-box { background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0; display: grid; grid-template-columns: 1fr; gap: 10px; margin-bottom: 15px; }
    @media (min-width: 768px) { .yfj-info-box { grid-template-columns: 1fr 1fr; } }
    .yfj-info-item { margin: 0 0 8px 0; }

    .yfj-jkj-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .yfj-jkj-table th, .yfj-jkj-table td { border: 1px solid #e2e8f0; padding: 12px; text-align: center; }
    .yfj-jkj-table th { background-color: #f1f5f9; color: #475569; font-weight: bold; }

    .yfj-keyword-red { color: #dc2626; font-weight: bold; }
    .yfj-keyword-blue { color: #2563eb; }
    .yfj-keyword-purple { color: #7e22ce; }
    .yfj-keyword-green { color: #16a34a; }
</style>

<div class="yfj-jkj-wrapper">

    <!-- 基本信息 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-id-alt"></span> <?php echo $this->t('基本信息'); ?></div>
        <div class="yfj-panel-body">
            <div class="yfj-info-box">
                <div>
                    <p class="yfj-info-item"><strong><?php echo $this->t('姓名：'); ?></strong> <span><?php echo esc_html($base['name'] ?? ''); ?> (<?php echo esc_html($this->t($base['sex'] ?? '')); ?>)</span></p>
                    <p class="yfj-info-item"><strong><?php echo $this->t('公历：'); ?></strong> <?php echo esc_html($base['gongli'] ?? ''); ?></p>
                    <p class="yfj-info-item"><strong><?php echo $this->t('农历：'); ?></strong> <?php echo esc_html($base['nongli'] ?? ''); ?></p>
                    <p class="yfj-info-item"><strong><?php echo $this->t('节气：'); ?></strong> <?php echo esc_html($this->t($base['jieqi'] ?? '')); ?></p>
                    <p class="yfj-info-item"><strong><?php echo $this->t('四柱：'); ?></strong> <span class="yfj-keyword-red">
                        <?php echo esc_html(($sizhu['year_gan'] ?? '').($sizhu['year_zhi'] ?? '')) . ' '; ?>
                        <?php echo esc_html(($sizhu['month_gan'] ?? '').($sizhu['month_zhi'] ?? '')) . ' '; ?>
                        <?php echo esc_html(($sizhu['day_gan'] ?? '').($sizhu['day_zhi'] ?? '')) . ' '; ?>
                        <?php echo esc_html(($sizhu['hour_gan'] ?? '').($sizhu['hour_zhi'] ?? '')); ?>
                    </span></p>
                </div>
                <div>
                    <p class="yfj-info-item"><strong><?php echo $this->t('旬空：'); ?></strong> <span class="yfj-keyword-blue"><?php echo esc_html($this->t($base['xunkong'] ?? '')); ?></span></p>
                    <p class="yfj-info-item"><strong><?php echo $this->t('空亡：'); ?></strong> <span class="yfj-keyword-blue"><?php echo esc_html($this->t($base['kongwang'] ?? '')); ?></span></p>
                    <p class="yfj-info-item"><strong><?php echo $this->t('神煞：'); ?></strong>
                        <?php echo $this->t('贵人'); ?>—<?php echo esc_html($this->t($shensha['guiren'] ?? '')); ?> &nbsp;
                        <?php echo $this->t('日禄'); ?>—<?php echo esc_html($this->t($shensha['rilu'] ?? '')); ?><br>
                        <span style="visibility: hidden;"><strong><?php echo $this->t('神煞：'); ?></strong></span>
                        <?php echo $this->t('驿马'); ?>—<?php echo esc_html($this->t($shensha['yima'] ?? '')); ?> &nbsp;
                        <?php echo $this->t('桃花'); ?>—<?php echo esc_html($this->t($shensha['taohua'] ?? '')); ?>
                    </p>
                    <p class="yfj-info-item">
                        <strong><?php echo $this->t('月将：'); ?></strong> <span class="yfj-keyword-green"><?php echo esc_html($this->t($base['yuejiang'] ?? '')); ?></span> &nbsp;
                        <strong><?php echo $this->t('年命：'); ?></strong> <span class="yfj-keyword-green"><?php echo esc_html($this->t($base['nianming'] ?? '')); ?></span> &nbsp;
                        <strong><?php echo $this->t('行年：'); ?></strong> <span class="yfj-keyword-green"><?php echo esc_html($this->t($base['hangnian'] ?? '')); ?></span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- 金口诀排盘核心 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-grid-view"></span> <?php echo $this->t('大六壬金口诀起课'); ?></div>
        <div class="yfj-panel-body">

            <table class="yfj-jkj-table">
                <thead>
                <tr>
                    <th><?php echo $this->t('四位'); ?></th>
                    <th><?php echo $this->t('干支'); ?></th>
                    <th><?php echo $this->t('神将'); ?></th>
                    <th><?php echo $this->t('用爻'); ?></th>
                    <th><?php echo $this->t('旺衰'); ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><strong><?php echo $this->t('人元'); ?></strong></td>
                    <td style="font-size: 16px; font-weight: bold;"><?php echo esc_html($this->t($pan_info['renyuan_info']['ganzhi'] ?? '')); ?></td>
                    <td>-</td>
                    <td>-</td>
                    <td class="yfj-keyword-red">【<?php echo esc_html($this->t($pan_info['renyuan_info']['shuaiwang'] ?? '')); ?>】</td>
                </tr>
                <tr>
                    <td><strong><?php echo $this->t('贵神'); ?></strong></td>
                    <td style="font-size: 16px; font-weight: bold;"><?php echo esc_html($this->t($pan_info['guishen_info']['ganzhi'] ?? '')); ?></td>
                    <td class="yfj-keyword-purple"><?php echo esc_html($this->t($pan_info['guishen_info']['name'] ?? '')); ?></td>
                    <td class="yfj-keyword-blue"><?php echo esc_html($this->t($pan_info['guishen_info']['yongyao'] ?? '')); ?></td>
                    <td class="yfj-keyword-red">【<?php echo esc_html($this->t($pan_info['guishen_info']['shuaiwang'] ?? '')); ?>】</td>
                </tr>
                <tr>
                    <td><strong><?php echo $this->t('将神'); ?></strong></td>
                    <td style="font-size: 16px; font-weight: bold;"><?php echo esc_html($this->t($pan_info['jiangshen_info']['ganzhi'] ?? '')); ?></td>
                    <td class="yfj-keyword-purple"><?php echo esc_html($this->t($pan_info['jiangshen_info']['name'] ?? '')); ?></td>
                    <td class="yfj-keyword-blue"><?php echo esc_html($this->t($pan_info['jiangshen_info']['yongyao'] ?? '')); ?></td>
                    <td class="yfj-keyword-red">【<?php echo esc_html($this->t($pan_info['jiangshen_info']['shuaiwang'] ?? '')); ?>】</td>
                </tr>
                <tr>
                    <td><strong><?php echo $this->t('地分'); ?></strong></td>
                    <td style="font-size: 16px; font-weight: bold;"><?php echo esc_html($this->t($pan_info['difen_info']['ganzhi'] ?? '')); ?></td>
                    <td>-</td>
                    <td>-</td>
                    <td class="yfj-keyword-red">【<?php echo esc_html($this->t($pan_info['difen_info']['shuaiwang'] ?? '')); ?>】</td>
                </tr>
                </tbody>
            </table>

            <div style="margin-top: 20px; font-size: 13px; color: #64748b; text-align: center; border-top: 1px dashed #cbd5e1; padding-top: 15px;">
                * <?php echo $this->t('金口诀属于高阶数术体系，本系统提供专业级原盘起课数据，具体吉凶断事请结合易理推演。'); ?>
            </div>

        </div>
    </div>

    <?php echo $this->get_disclaimer_html(); ?>

    <div style="text-align: center; margin-top: 10px;">
        <button onclick="jQuery('.yfj-result-area').hide(); jQuery('.yfj-ajax-form').fadeIn();"
                style="background: #e2e8f0; color: #334155; border: none; padding: 10px 24px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            <?php echo $this->t('返回重排'); ?>
        </button>
    </div>

</div>