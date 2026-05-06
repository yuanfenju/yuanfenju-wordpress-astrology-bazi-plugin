<?php
// 安全拦截
if (empty($data) || !is_array($data)) {
    echo '<div style="color:red; text-align:center; padding: 20px;">' . $this->t('暂无数据') . '</div>';
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

    /* 黄历日期头部特效 */
    .yfj-hl-header { text-align: center; padding: 20px 10px; background: #fff7ed; border-bottom: 1px solid #ffedd5; }
    .yfj-hl-yangli { font-size: 32px; font-weight: 900; color: #dc2626; margin: 10px 0; letter-spacing: 1px; }
    .yfj-hl-yinli { font-size: 16px; color: #9a3412; font-weight: 500; }
    .yfj-hl-ganzhi { font-size: 14px; color: #c2410c; margin-bottom: 10px; }

    /* 宜忌大字区块 */
    .yfj-yiji-box { display: flex; gap: 15px; margin-bottom: 20px; }
    .yfj-yiji-item { flex: 1; padding: 15px; border-radius: 8px; display: flex; align-items: flex-start; gap: 12px; }
    .yfj-yi-wrap { background: #f0fdf4; border: 1px solid #bbf7d0; }
    .yfj-ji-wrap { background: #fef2f2; border: 1px solid #fecaca; }
    .yfj-circle-badge { width: 36px; height: 36px; min-width: 36px; line-height: 36px; text-align: center; border-radius: 50%; color: #fff; font-size: 18px; font-weight: bold; }
    .yfj-circle-yi { background: #16a34a; }
    .yfj-circle-ji { background: #dc2626; }
    .yfj-yiji-text { font-size: 15px; line-height: 1.6; color: #1e293b; font-weight: 500; }

    /* 九宫格数据区 */
    .yfj-hl-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 15px; }
    /* 专为 4 个方位设计的网格，强制 4 列，手机端强制 2 列 */
    .yfj-hl-grid-4cols {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }
    @media (max-width: 768px) {
        .yfj-hl-grid-4cols {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    .yfj-hl-grid-item { background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; text-align: center; border-radius: 6px; }
    .yfj-hl-grid-title { color: #8b5cf6; font-size: 13px; margin-bottom: 5px; font-weight: bold; }
    .yfj-hl-grid-val { color: #1e293b; font-size: 14px; font-weight: 500; }

    /* 时辰表格响应式 */
    .yfj-shichen-table { width: 100%; border-collapse: collapse; font-size: 13px; text-align: center; }
    .yfj-shichen-table th, .yfj-shichen-table td { border: 1px solid #e2e8f0; padding: 10px 8px; }
    .yfj-shichen-table th { background: #f1f5f9; color: #334155; font-weight: 600; }
    .yfj-jixiong-ji { color: #dc2626; font-weight: bold; }
    .yfj-jixiong-xiong { color: #1e293b; }
</style>

<div class="yfj-result-wrapper">

    <!-- 1. 核心黄历卡片 -->
    <div class="yfj-panel">
        <div class="yfj-hl-header">
            <?php
            // 组装干支
            $ganzhi = $data['ganzhi'];
            $ganzhi_str = $ganzhi['yeargan'] . $ganzhi['yearzhi'] . $this->t('年 ') .
                $ganzhi['monthgan'] . $ganzhi['monthzhi'] . $this->t('月 ') .
                $ganzhi['daygan'] . $ganzhi['dayzhi'] . $this->t('日');
            ?>
            <div class="yfj-hl-ganzhi"><?php echo esc_html($ganzhi_str); ?> &nbsp;|&nbsp; <?php echo esc_html($data['xingqi']); ?></div>
            <div class="yfj-hl-yangli"><?php echo esc_html($data['yangli']); ?></div>
            <div class="yfj-hl-yinli">
                <?php echo esc_html($data['yinli']); ?>
                <?php if($data['jieqi'] !== '无') echo '&nbsp;|&nbsp; ' . esc_html($data['jieqi']) . $this->t('节气'); ?>
            </div>
        </div>

        <div class="yfj-panel-body" style="padding-top: 25px;">
            <!-- 宜忌区 -->
            <div class="yfj-yiji-box">
                <div class="yfj-yiji-item yfj-yi-wrap">
                    <div class="yfj-circle-badge yfj-circle-yi"><?php echo $this->t('宜'); ?></div>
                    <div class="yfj-yiji-text"><?php echo esc_html($data['yi']); ?></div>
                </div>
                <div class="yfj-yiji-item yfj-ji-wrap">
                    <div class="yfj-circle-badge yfj-circle-ji"><?php echo $this->t('忌'); ?></div>
                    <div class="yfj-yiji-text"><?php echo esc_html($data['ji']); ?></div>
                </div>
            </div>

            <!-- 神煞方位等基础属性网格 -->
            <div class="yfj-hl-grid">
                <div class="yfj-hl-grid-item">
                    <div class="yfj-hl-grid-title"><?php echo $this->t('五行'); ?></div>
                    <div class="yfj-hl-grid-val"><?php echo esc_html($data['wuxing']); ?></div>
                </div>
                <div class="yfj-hl-grid-item">
                    <div class="yfj-hl-grid-title"><?php echo $this->t('冲煞'); ?></div>
                    <div class="yfj-hl-grid-val"><?php echo esc_html($data['chongsha']); ?></div>
                </div>
                <div class="yfj-hl-grid-item">
                    <div class="yfj-hl-grid-title"><?php echo $this->t('值神'); ?></div>
                    <div class="yfj-hl-grid-val"><?php echo esc_html($data['tianshen']); ?> (<?php echo esc_html($data['huanghei'].$data['jixiong']); ?>)</div>
                </div>
                <div class="yfj-hl-grid-item">
                    <div class="yfj-hl-grid-title"><?php echo $this->t('建除十二神'); ?></div>
                    <div class="yfj-hl-grid-val"><?php echo esc_html($data['zhixing']); ?><?php echo $this->t('日'); ?></div>
                </div>
                <div class="yfj-hl-grid-item">
                    <div class="yfj-hl-grid-title"><?php echo $this->t('二十八星宿'); ?></div>
                    <div class="yfj-hl-grid-val"><?php echo esc_html($data['xingxiu']); ?></div>
                </div>
                <div class="yfj-hl-grid-item">
                    <div class="yfj-hl-grid-title"><?php echo $this->t('彭祖百忌'); ?></div>
                    <div class="yfj-hl-grid-val"><?php echo esc_html($data['baiji']); ?></div>
                </div>
            </div>

            <div style="border-top: 1px dashed #cbd5e1; margin: 20px 0;"></div>

            <!-- 方位与吉凶神 -->
            <div class="yfj-hl-grid-4cols">
                <div class="yfj-hl-grid-item" style="background:#fff;">
                    <div class="yfj-hl-grid-title" style="color:#d97706;"><?php echo $this->t('财神方位'); ?></div>
                    <div class="yfj-hl-grid-val"><?php echo esc_html($data['caishenfangwei']); ?></div>
                </div>
                <div class="yfj-hl-grid-item" style="background:#fff;">
                    <div class="yfj-hl-grid-title" style="color:#d97706;"><?php echo $this->t('喜神方位'); ?></div>
                    <div class="yfj-hl-grid-val"><?php echo esc_html($data['xishenfangwei']); ?></div>
                </div>
                <div class="yfj-hl-grid-item" style="background:#fff;">
                    <div class="yfj-hl-grid-title" style="color:#d97706;"><?php echo $this->t('福神方位'); ?></div>
                    <div class="yfj-hl-grid-val"><?php echo esc_html($data['fushenfangwei']); ?></div>
                </div>
                <div class="yfj-hl-grid-item" style="background:#fff;">
                    <div class="yfj-hl-grid-title" style="color:#d97706;"><?php echo $this->t('贵神方位'); ?></div>
                    <div class="yfj-hl-grid-val"><?php echo esc_html($data['guishenfangwei']); ?></div>
                </div>
            </div>

            <div style="margin-top: 15px; padding: 12px; background: #f8fafc; border-radius: 6px; font-size: 13px;">
                <p style="margin: 0 0 5px 0;"><strong><?php echo $this->t('吉神宜趋：'); ?></strong> <?php echo esc_html($data['jishen']); ?></p>
                <p style="margin: 0 0 5px 0;"><strong><?php echo $this->t('凶神宜忌：'); ?></strong> <?php echo esc_html($data['xiongshen']); ?></p>
                <p style="margin: 0;"><strong><?php echo $this->t('今日胎神：'); ?></strong> <?php echo esc_html($data['jinritaishen']); ?> &nbsp;|&nbsp; <strong><?php echo $this->t('本月胎神：'); ?></strong> <?php echo esc_html($data['benyuetaishen']); ?></p>
            </div>

            <?php if(!empty($data['xingxiuge'])): ?>
                <div style="margin-top: 15px; padding: 12px; border-left: 3px solid #8b5cf6; background: #f5f3ff; color: #4c1d95; font-size: 13px;">
                    <strong><?php echo $this->t('星宿歌诀：'); ?></strong> <?php echo esc_html($data['xingxiuge']); ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- 2. 十二时辰详解 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-clock"></span> <?php echo $this->t('时辰吉凶信息'); ?>
        </div>
        <div class="yfj-panel-body" style="padding: 0;">
            <div style="overflow-x: auto;">
                <table class="yfj-shichen-table">
                    <thead>
                    <tr>
                        <th style="min-width: 70px;"><?php echo $this->t('时辰'); ?></th>
                        <th style="min-width: 90px;"><?php echo $this->t('时刻'); ?></th>
                        <th style="min-width: 70px;"><?php echo $this->t('吉凶'); ?></th>
                        <th style="min-width: 150px;"><?php echo $this->t('时宜'); ?></th>
                        <th style="min-width: 150px;"><?php echo $this->t('时忌'); ?></th>
                        <th style="min-width: 80px;"><?php echo $this->t('冲煞'); ?></th>
                        <th style="min-width: 120px;"><?php echo $this->t('财/喜神'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ((array)$data['detail_info'] as $sc): ?>
                        <tr>
                            <td>
                                <strong><?php echo esc_html($sc['time_shichen']); ?></strong><br>
                                <span style="color:#64748b; font-size:12px;"><?php echo esc_html($sc['time_ganzhi']); ?></span>
                            </td>
                            <td style="color:#475569;"><?php echo esc_html($sc['time_region']); ?></td>
                            <td>
                                <?php
                                $jx_class = ($sc['time_jixiong'] === '吉') ? 'yfj-jixiong-ji' : 'yfj-jixiong-xiong';
                                ?>
                                <span class="<?php echo $jx_class; ?>"><?php echo esc_html($sc['time_huanghei']); ?><br><?php echo esc_html($sc['time_jixiong']); ?></span>
                            </td>
                            <td style="text-align: left; color: #16a34a;"><?php echo esc_html($sc['time_yi']); ?></td>
                            <td style="text-align: left; color: #dc2626;"><?php echo esc_html($sc['time_ji']); ?></td>
                            <td><?php echo esc_html($sc['time_chong']); ?><br><?php echo esc_html($sc['time_sha']); ?><?php echo $this->t('煞'); ?></td>
                            <td style="font-size: 12px; color: #475569;">
                                <?php echo $this->t('财:'); ?><?php echo esc_html($sc['time_caishenfangwei']); ?><br>
                                <?php echo $this->t('喜:'); ?><?php echo esc_html($sc['time_xishenfangwei']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>