<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fff3cd; padding:12px 15px; color:#856404; margin-bottom:20px; border-radius:6px; border-left: 4px solid #ffeeba; font-size: 14px; line-height: 1.6;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<?php
// 安全提取精算数据结构
$base       = $data['base_info'] ?? [];
$detail     = $data['detail_info'] ?? [];
$sizhu_info = $detail['sizhu_info'] ?? [];
$indication = $sizhu_info['sizhu_indication'] ?? [];
$xys        = $base['xiyongshen'] ?? [];
$dayun_info = $detail['dayun_info'] ?? [];

// 基础变量提取
$name       = $base['name'] ?? ($data['name'] ?? '未知');
$sex     = $base['sex'] ?? '未知';
$display_sex = str_replace(['乾造', '坤造'], ['男', '女'], $sex); // 把专业术语替换为男女
$gongli     = $base['gongli'] ?? '';
$nongli     = $base['nongli'] ?? '';
$zhengge    = $base['zhengge'] ?? '未知';
$sx         = $base['shengxiao'] ?? '';
$xz         = $base['xingzuo'] ?? '';
?>

<style>
    .yfj-result-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid var(--yfj-border, #e2e8f0); border-radius: 8px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid var(--yfj-border, #e2e8f0); font-weight: 600; font-size: 16px; color: var(--yfj-text-dark, #0f172a); display: flex; align-items: center; gap: 8px; }
    .yfj-panel-body { padding: 20px; font-size: 14.5px; line-height: 1.8; color: #475569; }

    .yfj-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; font-size: 14px; line-height: 1.6; }
    .yfj-info-grid strong { color: #1e293b; }

    .yfj-badge-red { color: #dc2626; font-weight: 600; }
    .yfj-badge-blue { color: #2563eb; font-weight: 600; }
    .yfj-badge-orange { color: #ea580c; font-weight: 600; }
    .yfj-badge-green { color: #16a34a; font-weight: 600; }
    .yfj-highlight { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: #0f172a; font-weight: 500; }

    /* 大运流年专属时间轴样式 */
    .yfj-timeline-item { border-left: 2px solid #cbd5e1; padding-left: 15px; margin-left: 5px; margin-bottom: 20px; position: relative; }
    .yfj-timeline-item::before { content: ''; position: absolute; left: -6px; top: 6px; width: 10px; height: 10px; border-radius: 50%; background: #c99a5b; border: 2px solid #fff; }
    .yfj-timeline-title { font-size: 16px; font-weight: bold; color: #1e293b; margin-bottom: 8px; }
    .yfj-liunian-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; margin-top: 10px; }
    .yfj-liunian-title { font-weight: bold; color: #2563eb; margin-bottom: 5px; font-size: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; }
</style>

<div class="yfj-result-wrapper">

    <!-- 1. 基本信息 (精算版更详细) -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-id-alt"></span> <?php echo $this->t('基本信息'); ?>
        </div>
        <div class="yfj-panel-body yfj-info-grid">
            <div><strong><?php echo $this->t('命主姓名：'); ?></strong> <?php echo esc_html($name); ?> &nbsp;|&nbsp; <strong><?php echo $this->t('性别：'); ?></strong> <?php echo esc_html($display_sex); ?></div>
            <div><strong><?php echo $this->t('生肖星座：'); ?></strong> <?php echo $this->t('肖'); ?><?php echo esc_html($sx); ?> / <?php echo esc_html($xz); ?><?php echo $this->t('座'); ?></div>
            <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('出生公历：'); ?></strong> <?php echo esc_html($gongli); ?></div>
            <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('出生农历：'); ?></strong> <?php echo esc_html($nongli); ?></div>

            <div style="grid-column: 1 / -1; margin-top: 5px; padding-top: 10px; border-top: 1px dashed #e2e8f0;">
                <strong><?php echo $this->t('四柱八字：'); ?></strong><br>
                <span class="yfj-badge-red"><?php echo esc_html($sizhu_info['year']['tg'] ?? ''); ?><?php echo esc_html($sizhu_info['year']['dz'] ?? ''); ?><?php echo $this->t('年'); ?> (<?php echo esc_html($sizhu_info['year']['ny'] ?? ''); ?>)</span> &nbsp;
                <span class="yfj-badge-blue"><?php echo esc_html($sizhu_info['month']['tg'] ?? ''); ?><?php echo esc_html($sizhu_info['month']['dz'] ?? ''); ?><?php echo $this->t('月'); ?> (<?php echo esc_html($sizhu_info['month']['ny'] ?? ''); ?>)</span> &nbsp;
                <span class="yfj-badge-orange"><?php echo esc_html($sizhu_info['day']['tg'] ?? ''); ?><?php echo esc_html($sizhu_info['day']['dz'] ?? ''); ?><?php echo $this->t('日'); ?> (<?php echo esc_html($sizhu_info['day']['ny'] ?? ''); ?>)</span> &nbsp;
                <span class="yfj-badge-green"><?php echo esc_html($sizhu_info['hour']['tg'] ?? ''); ?><?php echo esc_html($sizhu_info['hour']['dz'] ?? ''); ?><?php echo $this->t('时'); ?> (<?php echo esc_html($sizhu_info['hour']['ny'] ?? ''); ?>)</span>
            </div>

            <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('胎元命身：'); ?></strong>
                <?php echo $this->t('胎息:'); ?><?php echo esc_html($base['taixi'] ?? ''); ?>(<?php echo esc_html($base['taixi_nayin'] ?? ''); ?>) &nbsp;
                <?php echo $this->t('胎元:'); ?><?php echo esc_html($base['taiyuan'] ?? ''); ?>(<?php echo esc_html($base['taiyuan_nayin'] ?? ''); ?>) &nbsp;
                <?php echo $this->t('命宫:'); ?><?php echo esc_html($base['minggong'] ?? ''); ?>(<?php echo esc_html($base['minggong_nayin'] ?? ''); ?>) &nbsp;
                <?php echo $this->t('身宫:'); ?><?php echo esc_html($base['shengong'] ?? ''); ?>(<?php echo esc_html($base['shengong_nayin'] ?? ''); ?>)
            </div>

            <div><strong><?php echo $this->t('星宿信息：'); ?></strong> <?php echo esc_html($base['xingxiu'] ?? '-'); ?></div>
            <div><strong><?php echo $this->t('命卦信息：'); ?></strong> <?php echo esc_html($base['minggua']['minggua_name'] ?? ''); ?><?php echo $this->t('卦'); ?> (<?php echo esc_html($base['minggua']['minggua_fangwei'] ?? ''); ?>)</div>
            <div><strong><?php echo $this->t('年柱纳音：'); ?></strong> <?php echo esc_html($sizhu_info['year']['ny'] ?? ''); ?><?php echo $this->t('命'); ?> (<?php echo $this->t('司令:'); ?><?php echo esc_html($base['siling'] ?? ''); ?>)</div>
            <div><strong><?php echo $this->t('五行旺度：'); ?></strong> <?php echo esc_html($base['wuxing_wangdu'] ?? '-'); ?></div>
            <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('起运交运：'); ?></strong> <?php echo esc_html($base['qiyun'] ?? ''); ?> / <?php echo esc_html($base['jiaoyun_mang'] ?? ''); ?></div>
            <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('天干留意：'); ?></strong> <?php echo esc_html($base['tiangan_liuyi'] ?? '-'); ?></div>
            <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('地支留意：'); ?></strong> <?php echo esc_html($base['dizhi_liuyi'] ?? '-'); ?></div>
        </div>
    </div>

    <!-- 2. 喜用神深度分析 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-chart-pie"></span> <?php echo $this->t('喜用神与五行能量深度分析'); ?>
        </div>
        <div class="yfj-panel-body">
            <p>
                <?php echo $this->t('日主天干为'); ?> <span class="yfj-highlight"><?php echo esc_html($xys['rizhu_tiangan'] ?? '-'); ?></span>，
                <?php echo $this->t('同类为'); ?> <span class="yfj-highlight"><?php echo esc_html($xys['tonglei'] ?? '-'); ?></span>，
                <?php echo $this->t('异类为'); ?> <span class="yfj-highlight"><?php echo esc_html($xys['yilei'] ?? '-'); ?></span>。
            </p>
            <p>
                <span class="yfj-badge-red"><?php echo esc_html($xys['qiangruo'] ?? '-'); ?></span>，
                <?php echo $this->t('以'); ?> <span class="yfj-badge-red"><?php echo esc_html($xys['xiyongshen'] ?? '-'); ?></span> <?php echo $this->t('为喜用神'); ?>。
                <strong><?php echo $this->t('阴阳参考：'); ?></strong> <?php echo esc_html($xys['yinyang'] ?? '-'); ?>
            </p>

            <div style="background: #f8fafc; padding: 12px; border-radius: 6px; margin-top: 15px; border-left: 3px solid #cbd5e1;">
                <strong><?php echo $this->t('五行个数：'); ?></strong>
                <?php echo esc_html($xys['jin_number'] ?? '0'); ?><?php echo $this->t('金'); ?>，
                <?php echo esc_html($xys['mu_number'] ?? '0'); ?><?php echo $this->t('木'); ?>，
                <?php echo esc_html($xys['shui_number'] ?? '0'); ?><?php echo $this->t('水'); ?>，
                <?php echo esc_html($xys['huo_number'] ?? '0'); ?><?php echo $this->t('火'); ?>，
                <?php echo esc_html($xys['tu_number'] ?? '0'); ?><?php echo $this->t('土'); ?>。
                <br>
                <strong><?php echo $this->t('党派分布：'); ?></strong> <?php echo $this->t('自党：'); ?><?php echo esc_html($xys['zidang'] ?? '0'); ?>，<?php echo $this->t('异党：'); ?><?php echo esc_html($xys['yidang'] ?? '0'); ?>
            </div>

            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 12px; border-radius: 6px; margin-top: 10px;">
                <strong><?php echo $this->t('五行能量打分：'); ?></strong><br>
                <?php echo $this->t('金：'); ?><?php echo esc_html($xys['jin_score'] ?? '0'); ?><?php echo $this->t('分'); ?> (<?php echo $this->t('占比'); ?><?php echo esc_html($xys['jin_score_percent'] ?? '0%'); ?>) |
                <?php echo $this->t('木：'); ?><?php echo esc_html($xys['mu_score'] ?? '0'); ?><?php echo $this->t('分'); ?> (<?php echo $this->t('占比'); ?><?php echo esc_html($xys['mu_score_percent'] ?? '0%'); ?>) |
                <?php echo $this->t('水：'); ?><?php echo esc_html($xys['shui_score'] ?? '0'); ?><?php echo $this->t('分'); ?> (<?php echo $this->t('占比'); ?><?php echo esc_html($xys['shui_score_percent'] ?? '0%'); ?>) <br>
                <?php echo $this->t('火：'); ?><?php echo esc_html($xys['huo_score'] ?? '0'); ?><?php echo $this->t('分'); ?> (<?php echo $this->t('占比'); ?><?php echo esc_html($xys['huo_score_percent'] ?? '0%'); ?>) |
                <?php echo $this->t('土：'); ?><?php echo esc_html($xys['tu_score'] ?? '0'); ?><?php echo $this->t('分'); ?> (<?php echo $this->t('占比'); ?><?php echo esc_html($xys['tu_score_percent'] ?? '0%'); ?>)
            </div>
        </div>
    </div>

    <!-- 3. 日柱论命 & 先天纳音 & 能量五行 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-buddicons-topics"></span> <?php echo $this->t('日柱、五行与纳音解析'); ?>
        </div>
        <div class="yfj-panel-body">
            <p><strong>【<?php echo $this->t('日柱论命'); ?>】</strong><br> <?php echo esc_html($indication['xingge']['rizhu'] ?? $this->t('暂无数据')); ?></p>
            <div style="border-top: 1px dashed #e2e8f0; margin: 15px 0;"></div>
            <p><strong>【<?php echo $this->t('先天纳音'); ?>】</strong><br> <?php echo esc_html($indication['wuxing']['detail_desc'] ?? ''); ?> <?php echo esc_html($indication['wuxing']['detail_description'] ?? ''); ?></p>
            <div style="border-top: 1px dashed #e2e8f0; margin: 15px 0;"></div>
            <p><strong>【<?php echo $this->t('能量五行'); ?>】</strong><br> <?php echo esc_html($indication['wuxing']['simple_description'] ?? ''); ?></p>
        </div>
    </div>

    <!-- 4. 财运与姻缘 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-heart"></span> <?php echo $this->t('财运与姻缘分析'); ?>
        </div>
        <div class="yfj-panel-body">
            <p><strong>【<?php echo $this->t('财运分析'); ?>】</strong><br>
                <span class="yfj-badge-red" style="font-size: 15px; margin-right: 8px;"><?php echo esc_html($indication['caiyun']['sanshishu_caiyun']['simple_desc'] ?? ''); ?></span>
                <?php echo esc_html($indication['caiyun']['sanshishu_caiyun']['detail_desc'] ?? ''); ?>
            </p>
            <div style="border-top: 1px dashed #e2e8f0; margin: 15px 0;"></div>
            <p><strong>【<?php echo $this->t('姻缘分析'); ?>】</strong><br>
                <?php echo esc_html($indication['yinyuan']['sanshishu_yinyuan'] ?? $this->t('暂无数据')); ?>
            </p>
        </div>
    </div>

    <!-- 5. 总体命运 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-star-filled"></span> <?php echo $this->t('一生总体命运与运程'); ?>
        </div>
        <div class="yfj-panel-body">
            <p><strong>【<?php echo $this->t('运程概括'); ?>】</strong><br> <?php echo esc_html($indication['chenggu']['description'] ?? ''); ?></p>
            <div style="border-top: 1px dashed #e2e8f0; margin: 15px 0;"></div>
            <p><strong>【<?php echo $this->t('命运总批'); ?>】</strong><br> <?php echo esc_html($indication['mingyun']['sanshishu_mingyun'] ?? ''); ?></p>
        </div>
    </div>

    <!-- 6. 十年大运与流年详批 (核心重头戏) -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-calendar-alt"></span> <?php echo $this->t('十年大运与流年详批'); ?>
        </div>
        <div class="yfj-panel-body" style="padding-top: 30px;">
            <?php if (!empty($dayun_info) && is_array($dayun_info)): ?>
                <?php foreach($dayun_info as $dayun): ?>
                    <div class="yfj-timeline-item">
                        <div class="yfj-timeline-title">
                            <?php echo $this->t('大运年：'); ?><span style="color: #dc2626;"><?php echo esc_html($dayun['dayun_start_year'] ?? ''); ?> ～ <?php echo esc_html($dayun['dayun_end_year'] ?? ''); ?></span>
                        </div>
                        <div style="font-size: 14px; margin-bottom: 12px;">
                            <p style="margin: 3px 0;"><strong>[<?php echo $this->t('大运·事业'); ?>]：</strong><?php echo esc_html($dayun['dayun_indication']['shiye'] ?? '-'); ?></p>
                            <p style="margin: 3px 0;"><strong>[<?php echo $this->t('大运·学业'); ?>]：</strong><?php echo esc_html($dayun['dayun_indication']['xueye'] ?? '-'); ?></p>
                            <p style="margin: 3px 0;"><strong>[<?php echo $this->t('大运·财运'); ?>]：</strong><?php echo esc_html($dayun['dayun_indication']['caiyun'] ?? '-'); ?></p>
                            <p style="margin: 3px 0;"><strong>[<?php echo $this->t('大运·姻缘'); ?>]：</strong><?php echo esc_html($dayun['dayun_indication']['yinyuan'] ?? '-'); ?></p>
                            <p style="margin: 3px 0;"><strong>[<?php echo $this->t('大运·健康'); ?>]：</strong><?php echo esc_html($dayun['dayun_indication']['jiankang'] ?? '-'); ?></p>
                            <p style="margin: 3px 0;"><strong>[<?php echo $this->t('大运·总运'); ?>]：</strong><?php echo esc_html($dayun['dayun_indication']['yunshi'] ?? '-'); ?></p>
                        </div>

                        <!-- 嵌套流年 -->
                        <?php if(!empty($dayun['liunian_info']) && is_array($dayun['liunian_info'])): ?>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 10px;">
                                <?php foreach($dayun['liunian_info'] as $liunian): ?>
                                    <div class="yfj-liunian-box">
                                        <div class="yfj-liunian-title">🎯 <?php echo $this->t('流年：'); ?><?php echo esc_html($liunian['liunian_year'] ?? ''); ?></div>
                                        <div style="font-size: 13px; color: #475569; line-height: 1.5;">
                                            <div><strong>[<?php echo $this->t('事业'); ?>]</strong> <?php echo esc_html($liunian['liunian_indication']['shiye'] ?? '-'); ?></div>
                                            <div><strong>[<?php echo $this->t('学业'); ?>]</strong> <?php echo esc_html($liunian['liunian_indication']['xueye'] ?? '-'); ?></div>
                                            <div><strong>[<?php echo $this->t('财运'); ?>]</strong> <?php echo esc_html($liunian['liunian_indication']['caiyun'] ?? '-'); ?></div>
                                            <div><strong>[<?php echo $this->t('姻缘'); ?>]</strong> <?php echo esc_html($liunian['liunian_indication']['yinyuan'] ?? '-'); ?></div>
                                            <div><strong>[<?php echo $this->t('健康'); ?>]</strong> <?php echo esc_html($liunian['liunian_indication']['jiankang'] ?? '-'); ?></div>
                                            <div><strong>[<?php echo $this->t('总运'); ?>]</strong> <?php echo esc_html($liunian['liunian_indication']['yunshi'] ?? '-'); ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p><?php echo $this->t('暂无大运流年数据'); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- 测算告诫，免责声明 -->
    <?php echo $this->get_disclaimer_html(); ?>

    <!-- 返回按钮 -->
    <div style="text-align: center; margin-top: 30px;">
        <button onclick="jQuery('.yfj-result-area').hide(); jQuery('.yfj-ajax-form').fadeIn();"
                style="background: #e2e8f0; color: #334155; border: none; padding: 10px 24px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            <?php echo $this->t('返回重测'); ?>
        </button>
    </div>

</div>