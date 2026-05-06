<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fff3cd; padding:12px 15px; color:#856404; margin-bottom:20px; border-radius:6px; border-left: 4px solid #ffeeba; font-size: 14px; line-height: 1.6;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<?php
// 安全提取数据
$base       = $data['base_info'] ?? [];
$detail     = $data['detail_info'] ?? [];
$sizhu      = $detail['sizhu_info'] ?? [];
$taishen    = $detail['taishen_info'] ?? [];
$dayun_info = $detail['dayun_info'] ?? [];

// 基础变量
$name    = $base['name'] ?? ($data['name'] ?? '未知');
$sex     = $base['sex'] ?? '未知';
$display_sex = str_replace(['乾造', '坤造'], ['男', '女'], $sex); // 把专业术语替换为男女
$gongli  = $base['gongli'] ?? '';
$nongli  = $base['nongli'] ?? '';
$zhengge = $base['zhengge'] ?? '未知';

// --- 辅助闭包函数：五行颜色渲染器 ---
$getWuxingColor = function($char) {
    $wood  = ['甲','乙','寅','卯'];
    $fire  = ['丙','丁','巳','午'];
    $earth = ['戊','己','辰','丑','戌','未','醜'];
    $metal = ['庚','辛','申','酉'];

    if (in_array($char, $wood))  return '#16a34a'; // 绿 (木)
    if (in_array($char, $fire))  return '#dc2626'; // 红 (火)
    if (in_array($char, $earth)) return '#8b4513'; // 褐 (土)
    if (in_array($char, $metal)) return '#ea580c'; // 橙 (金)
    return '#2563eb'; // 蓝 (水)
};

$renderChar = function($char, $size = '18px') use ($getWuxingColor) {
    if (!$char) return '--';
    $color = $getWuxingColor($char);
    return "<span style='color: {$color}; font-size: {$size}; font-weight: bold;'>{$char}</span>";
};
?>

<style>
    .yfj-result-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid var(--yfj-border, #e2e8f0); border-radius: 8px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid var(--yfj-border, #e2e8f0); font-weight: 600; font-size: 16px; color: var(--yfj-text-dark, #0f172a); display: flex; align-items: center; gap: 8px; }
    .yfj-panel-body { padding: 20px; font-size: 14.5px; line-height: 1.8; color: #475569; }

    .yfj-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; font-size: 14px; line-height: 1.6; }
    .yfj-info-grid strong { color: #1e293b; }

    /* 现代化表格样式 */
    .yfj-table-responsive { overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch; }
    .yfj-table { width: 100%; border-collapse: collapse; margin-bottom: 0; white-space: nowrap; font-size: 14px; }
    .yfj-table th, .yfj-table td { border: 1px solid #e2e8f0; padding: 10px 12px; text-align: center; vertical-align: middle; }
    .yfj-table th { background-color: #f8fafc; color: #475569; font-weight: 600; }

    /* 交互元素的指针样式 */
    .yfj-clickable { cursor: pointer; transition: background-color 0.2s; }
    .yfj-clickable:hover { background-color: #f1f5f9; }
    .yfj-active-cell { background-color: #dbeafe !important; border-color: #bfdbfe !important; }

    .yfj-col-header { width: 60px; background-color: #f1f5f9 !important; font-weight: bold; }
    .yfj-dynamic-col { min-width: 80px; }

    /* 流日流时专属样式 */
    @keyframes yfj-spin { 100% { transform: rotate(360deg); } }
    .yfj-spin { animation: yfj-spin 1s linear infinite; display: inline-block; }

    .yfj-lr-day { border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 10px; overflow: hidden; }
    .yfj-lr-summary { background: #f8fafc; padding: 12px 15px; cursor: pointer; font-weight: bold; list-style: none; display: flex; justify-content: space-between; align-items: center; transition: background 0.2s;}
    .yfj-lr-summary::-webkit-details-marker { display: none; }
    .yfj-lr-summary:hover { background: #f1f5f9; }
    .yfj-lr-content { padding: 15px; border-top: 1px solid #e2e8f0; background: #fff; overflow-x: auto; }
    .yfj-ls-table { width: 100%; border-collapse: collapse; min-width: 650px; font-size: 13px; text-align: center; }
    .yfj-ls-table th, .yfj-ls-table td { border: 1px solid #e2e8f0; padding: 8px; }
    .yfj-ls-table th { background: #f1f5f9; color: #475569; }
</style>

<div class="yfj-result-wrapper">

    <!-- 1. 缘主资料 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-id-alt"></span> <?php echo $this->t('缘主命理基础信息'); ?>
        </div>
        <div class="yfj-panel-body yfj-info-grid">
            <div><strong><?php echo $this->t('命主姓名：'); ?></strong> <?php echo esc_html($name); ?> &nbsp;|&nbsp; <strong><?php echo $this->t('性别：'); ?></strong> <?php echo esc_html($display_sex); ?></div>
            <div><strong><?php echo $this->t('起运/交运：'); ?></strong> <?php echo esc_html($base['qiyun'] ?? ''); ?> / <?php echo esc_html($base['jiaoyun_mang'] ?? ''); ?></div>
            <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('出生公历：'); ?></strong> <?php echo esc_html($gongli); ?></div>
            <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('出生农历：'); ?></strong> <?php echo esc_html($nongli); ?> (<?php echo esc_html($sex); ?>)</div>

            <div style="grid-column: 1 / -1; margin: 4px 0;">
                <strong><?php echo $this->t('命理格局：'); ?></strong>
                <span style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 4px 12px; border-radius: 6px; font-weight: bold; font-size: 15px; margin-left: 5px; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
            <?php echo esc_html($zhengge); ?>
            </span>
            </div>

            <div style="grid-column: 1 / -1; padding-top: 12px; border-top: 1px dashed #e2e8f0;">
                <strong><?php echo $this->t('胎元命身：'); ?></strong>
                <?php echo $this->t('胎息:'); ?><?php echo esc_html($base['taixi'] ?? ''); ?>(<?php echo esc_html($base['taixi_nayin'] ?? ''); ?>) &nbsp;
                <?php echo $this->t('胎元:'); ?><?php echo esc_html($base['taiyuan'] ?? ''); ?>(<?php echo esc_html($base['taiyuan_nayin'] ?? ''); ?>) &nbsp;
                <?php echo $this->t('命宫:'); ?><?php echo esc_html($base['minggong'] ?? ''); ?>(<?php echo esc_html($base['minggong_nayin'] ?? ''); ?>) &nbsp;
                <?php echo $this->t('身宫:'); ?><?php echo esc_html($base['shengong'] ?? ''); ?>(<?php echo esc_html($base['shengong_nayin'] ?? ''); ?>)
            </div>

            <div><strong><?php echo $this->t('星宿信息：'); ?></strong> <?php echo esc_html($base['xingxiu'] ?? '-'); ?></div>
            <div><strong><?php echo $this->t('命卦信息：'); ?></strong> <?php echo esc_html($base['minggua']['minggua_name'] ?? ''); ?><?php echo $this->t('卦'); ?> (<?php echo esc_html($base['minggua']['minggua_fangwei'] ?? ''); ?>)</div>
            <div><strong><?php echo $this->t('年柱纳音：'); ?></strong> <?php echo esc_html($sizhu['year']['ny'] ?? ''); ?><?php echo $this->t('命'); ?> (<?php echo $this->t('司令:'); ?><?php echo esc_html($base['siling'] ?? ''); ?>)</div>
            <div><strong><?php echo $this->t('五行旺度：'); ?></strong> <?php echo esc_html($base['wuxing_wangdu'] ?? '-'); ?></div>
            <div><strong><?php echo $this->t('生肖星座：'); ?></strong> <?php echo $this->t('肖'); ?><?php echo esc_html($base['shengxiao'] ?? ''); ?> / <?php echo esc_html($base['xingzuo'] ?? ''); ?><?php echo $this->t('座'); ?></div>

            <div style="grid-column: 1 / -1; padding-top: 12px; border-top: 1px dashed #e2e8f0;">
                <strong><?php echo $this->t('天干留意：'); ?></strong> <?php echo esc_html($base['tiangan_liuyi'] ?? '-'); ?>
            </div>
            <div style="grid-column: 1 / -1;">
                <strong><?php echo $this->t('地支留意：'); ?></strong> <?php echo esc_html($base['dizhi_liuyi'] ?? '-'); ?>
            </div>
        </div>
    </div>

    <!-- 2. 动态流年盘 (交互核心) -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-calendar-alt"></span> <?php echo $this->t('大运流年流月动态流盘'); ?>
            <span style="font-size:12px; font-weight:normal; color:#64748b; margin-left:auto;"><?php echo $this->t('👈 左右滑动查看，点击可进行穿透查询'); ?></span>
        </div>

        <div class="yfj-panel-body" style="padding: 0;">
            <!-- 时间轴选择器 -->
            <div class="yfj-table-responsive" style="border-bottom: 2px solid #c99a5b;">
                <table class="yfj-table">
                    <tbody>
                    <tr>
                        <th class="yfj-col-header"><?php echo $this->t('大运'); ?></th>
                        <?php foreach($dayun_info as $key => $vo): ?>
                            <td class="yfj-clickable" onclick="yfjGetLiuYear(<?php echo $key; ?>, this)">
                                <span style="color:#c99a5b; font-weight:bold;"><?php echo esc_html($vo['dayun_start_year']); ?></span><br>
                                <span style="font-size:12px; color:#94a3b8;"><?php echo esc_html($vo['dayun_start_age']); ?><?php echo $this->t('岁'); ?></span>
                            </td>
                        <?php endforeach; ?>
                        <td>#</td><td>#</td><td>#</td>
                    </tr>
                    <tr>
                        <th class="yfj-col-header"><?php echo $this->t('流年'); ?></th>
                        <?php for($i=0; $i<10; $i++): ?>
                            <td class="yfj-clickable" id="liuNian<?php echo $i; ?>" onclick="yfjGetLiuMonth(<?php echo $i; ?>, this)" style="color:#94a3b8;"><?php echo $this->t('选大运'); ?></td>
                        <?php endfor; ?>
                        <td>#</td><td>#</td><td>#</td>
                    </tr>
                    <tr>
                        <th class="yfj-col-header"><?php echo $this->t('流月'); ?></th>
                        <?php for($i=0; $i<12; $i++): ?>
                            <td class="yfj-clickable" id="liuYue<?php echo $i; ?>" onclick="yfjGetLiuMonthInfo(<?php echo $i; ?>, this)" style="color:#94a3b8;"><?php echo $this->t('选流年'); ?></td>
                        <?php endfor; ?>
                        <td>#</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- 八字动态详情网格 -->
            <div class="yfj-table-responsive">
                <table class="yfj-table">
                    <thead>
                    <tr style="background-color: #f8fafc;">
                        <th class="yfj-col-header"><?php echo $this->t('属性'); ?></th>
                        <th class="yfj-dynamic-col" style="color:#2563eb;"><?php echo $this->t('流月'); ?></th>
                        <th class="yfj-dynamic-col" style="color:#2563eb;"><?php echo $this->t('流年'); ?></th>
                        <th class="yfj-dynamic-col" style="color:#2563eb;"><?php echo $this->t('大运'); ?></th>
                        <th><?php echo $this->t('年柱'); ?></th>
                        <th><?php echo $this->t('月柱'); ?></th>
                        <th><?php echo $this->t('日柱'); ?></th>
                        <th><?php echo $this->t('时柱'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('主星'); ?></td>
                        <td id="zhuXingLiuYue">--</td>
                        <td id="zhuXingLiuNian">--</td>
                        <td id="zhuXingDayun">--</td>
                        <td><?php echo esc_html($sizhu['year']['tg_god'] ?? ''); ?></td>
                        <td><?php echo esc_html($sizhu['month']['tg_god'] ?? ''); ?></td>
                        <td><?php echo esc_html($sizhu['day']['tg_god'] ?? ''); ?></td>
                        <td><?php echo esc_html($sizhu['hour']['tg_god'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('天干'); ?></td>
                        <td id="tianGanLiuYue">--</td>
                        <td id="tianGanLiuNian">--</td>
                        <td id="tianGanDayun">--</td>
                        <td><?php echo $renderChar($sizhu['year']['tg'] ?? ''); ?></td>
                        <td><?php echo $renderChar($sizhu['month']['tg'] ?? ''); ?></td>
                        <td><?php echo $renderChar($sizhu['day']['tg'] ?? ''); ?></td>
                        <td><?php echo $renderChar($sizhu['hour']['tg'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('地支'); ?></td>
                        <td id="diZhiLiuYue">--</td>
                        <td id="diZhiLiuNian">--</td>
                        <td id="diZhiDayun">--</td>
                        <td><?php echo $renderChar($sizhu['year']['dz'] ?? ''); ?></td>
                        <td><?php echo $renderChar($sizhu['month']['dz'] ?? ''); ?></td>
                        <td><?php echo $renderChar($sizhu['day']['dz'] ?? ''); ?></td>
                        <td><?php echo $renderChar($sizhu['hour']['dz'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('藏干'); ?></td>
                        <td id="cangGanLiuYue" style="font-size:12px;">--</td>
                        <td id="cangGanLiuNian" style="font-size:12px;">--</td>
                        <td id="cangGanDayun" style="font-size:12px;">--</td>
                        <?php foreach(['year','month','day','hour'] as $col): ?>
                            <td style="font-size:12px;">
                                <?php
                                if(isset($sizhu[$col]['cg']) && is_array($sizhu[$col]['cg'])) {
                                    foreach($sizhu[$col]['cg'] as $idx => $cgItem) {
                                        $god = $sizhu[$col]['dz_god'][$idx] ?? '';
                                        echo $renderChar($cgItem, '14px') . ' ' . esc_html($god) . '<br>';
                                    }
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('星运'); ?></td>
                        <td id="xingYunLiuYue">--</td>
                        <td id="xingYunLiuNian">--</td>
                        <td id="xingYunDayun">--</td>
                        <td><?php echo esc_html($sizhu['year']['dz_star_cs'] ?? ''); ?></td>
                        <td><?php echo esc_html($sizhu['month']['dz_star_cs'] ?? ''); ?></td>
                        <td><?php echo esc_html($sizhu['day']['dz_star_cs'] ?? ''); ?></td>
                        <td><?php echo esc_html($sizhu['hour']['dz_star_cs'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('自坐'); ?></td>
                        <td id="ziZuoLiuYue">--</td>
                        <td id="ziZuoLiuNian">--</td>
                        <td id="ziZuoDayun">--</td>
                        <td><?php echo esc_html($sizhu['year']['dz_self_cs'] ?? ''); ?></td>
                        <td><?php echo esc_html($sizhu['month']['dz_self_cs'] ?? ''); ?></td>
                        <td><?php echo esc_html($sizhu['day']['dz_self_cs'] ?? ''); ?></td>
                        <td><?php echo esc_html($sizhu['hour']['dz_self_cs'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('空亡'); ?></td>
                        <td id="kongWangLiuYue">--</td>
                        <td id="kongWangLiuNian">--</td>
                        <td id="kongWangDayun">--</td>
                        <td><?php echo esc_html($sizhu['year']['kw'] ?? ''); ?></td>
                        <td><?php echo esc_html($sizhu['month']['kw'] ?? ''); ?></td>
                        <td><?php echo esc_html($sizhu['day']['kw'] ?? ''); ?></td>
                        <td><?php echo esc_html($sizhu['hour']['kw'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('纳音'); ?></td>
                        <td id="naYinLiuYue" style="font-size:12px;">--</td>
                        <td id="naYinLiuNian" style="font-size:12px;">--</td>
                        <td id="naYinDayun" style="font-size:12px;">--</td>
                        <td style="font-size:12px;"><?php echo esc_html($sizhu['year']['ny'] ?? ''); ?></td>
                        <td style="font-size:12px;"><?php echo esc_html($sizhu['month']['ny'] ?? ''); ?></td>
                        <td style="font-size:12px;"><?php echo esc_html($sizhu['day']['ny'] ?? ''); ?></td>
                        <td style="font-size:12px;"><?php echo esc_html($sizhu['hour']['ny'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('神煞'); ?></td>
                        <td id="shenShaLiuYue" style="font-size:12px; color:#c99a5b;">--</td>
                        <td id="shenShaLiuNian" style="font-size:12px; color:#c99a5b;">--</td>
                        <td id="shenShaBigYun" style="font-size:12px; color:#c99a5b;">--</td>
                        <?php foreach(['year','month','day','hour'] as $col): ?>
                            <td style="font-size:12px; color:#c99a5b;">
                                <?php
                                $shenshaStr = $sizhu[$col]['shensha'] ?? '';
                                if ($shenshaStr) {
                                    echo implode('<br>', explode(' ', $shenshaStr));
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 3. 【新增】流日流时专属展示面板 (默认隐藏) -->
    <div class="yfj-panel" id="yfj-liurishi-panel" style="display: none; border-color: #bfdbfe;">
        <div class="yfj-panel-heading" style="background: #eff6ff; color: #1e3a8a;">
            <span class="dashicons dashicons-clock"></span> <?php echo $this->t('流日流时深度解析'); ?>
            <span id="yfj-liurishi-title" style="margin-left: 10px; font-size: 14px; font-weight: normal; color: #3b82f6;"></span>
        </div>
        <div class="yfj-panel-body" id="yfj-liurishi-content" style="padding: 15px; background: #f8fafc;">
            <!-- 流日流时内容渲染区 -->
        </div>
    </div>

    <!-- 4. 胎命身静态盘 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-universal-access"></span> <?php echo $this->t('胎命身辅助盘'); ?>
        </div>
        <div class="yfj-panel-body" style="padding: 0;">
            <div class="yfj-table-responsive">
                <table class="yfj-table">
                    <thead>
                    <tr style="background-color: #f8fafc;">
                        <th class="yfj-col-header"><?php echo $this->t('属性'); ?></th>
                        <th><?php echo $this->t('胎元'); ?></th>
                        <th><?php echo $this->t('胎息'); ?></th>
                        <th><?php echo $this->t('命宫'); ?></th>
                        <th><?php echo $this->t('身宫'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('主星'); ?></td>
                        <td><?php echo esc_html($taishen['taiyuan']['tg_god'] ?? ''); ?></td>
                        <td><?php echo esc_html($taishen['taixi']['tg_god'] ?? ''); ?></td>
                        <td><?php echo esc_html($taishen['minggong']['tg_god'] ?? ''); ?></td>
                        <td><?php echo esc_html($taishen['shengong']['tg_god'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('天干'); ?></td>
                        <td><?php echo $renderChar($taishen['taiyuan']['gan'] ?? ''); ?></td>
                        <td><?php echo $renderChar($taishen['taixi']['gan'] ?? ''); ?></td>
                        <td><?php echo $renderChar($taishen['minggong']['gan'] ?? ''); ?></td>
                        <td><?php echo $renderChar($taishen['shengong']['gan'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('地支'); ?></td>
                        <td><?php echo $renderChar($taishen['taiyuan']['zhi'] ?? ''); ?></td>
                        <td><?php echo $renderChar($taishen['taixi']['zhi'] ?? ''); ?></td>
                        <td><?php echo $renderChar($taishen['minggong']['zhi'] ?? ''); ?></td>
                        <td><?php echo $renderChar($taishen['shengong']['zhi'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('藏干'); ?></td>
                        <?php foreach(['taiyuan','taixi','minggong','shengong'] as $col): ?>
                            <td style="font-size:12px;">
                                <?php
                                if(isset($taishen[$col]['dz_cg']) && is_array($taishen[$col]['dz_cg'])) {
                                    foreach($taishen[$col]['dz_cg'] as $idx => $cgItem) {
                                        $god = $taishen[$col]['dz_god'][$idx] ?? '';
                                        echo $renderChar($cgItem, '14px') . ' ' . esc_html($god) . '<br>';
                                    }
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('星运'); ?></td>
                        <td><?php echo esc_html($taishen['taiyuan']['dz_star_cs'] ?? ''); ?></td>
                        <td><?php echo esc_html($taishen['taixi']['dz_star_cs'] ?? ''); ?></td>
                        <td><?php echo esc_html($taishen['minggong']['dz_star_cs'] ?? ''); ?></td>
                        <td><?php echo esc_html($taishen['shengong']['dz_star_cs'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('自坐'); ?></td>
                        <td><?php echo esc_html($taishen['taiyuan']['dz_self_cs'] ?? ''); ?></td>
                        <td><?php echo esc_html($taishen['taixi']['dz_self_cs'] ?? ''); ?></td>
                        <td><?php echo esc_html($taishen['minggong']['dz_self_cs'] ?? ''); ?></td>
                        <td><?php echo esc_html($taishen['shengong']['dz_self_cs'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('空亡'); ?></td>
                        <td><?php echo esc_html($taishen['taiyuan']['kongwang'] ?? ''); ?></td>
                        <td><?php echo esc_html($taishen['taixi']['kongwang'] ?? ''); ?></td>
                        <td><?php echo esc_html($taishen['minggong']['kongwang'] ?? ''); ?></td>
                        <td><?php echo esc_html($taishen['shengong']['kongwang'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('纳音'); ?></td>
                        <td style="font-size:12px;"><?php echo esc_html($taishen['taiyuan']['nayin'] ?? ''); ?></td>
                        <td style="font-size:12px;"><?php echo esc_html($taishen['taixi']['nayin'] ?? ''); ?></td>
                        <td style="font-size:12px;"><?php echo esc_html($taishen['minggong']['nayin'] ?? ''); ?></td>
                        <td style="font-size:12px;"><?php echo esc_html($taishen['shengong']['nayin'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="yfj-col-header"><?php echo $this->t('神煞'); ?></td>
                        <?php foreach(['taiyuan','taixi','minggong','shengong'] as $col): ?>
                            <td style="font-size:12px; color:#c99a5b;">
                                <?php
                                $shenshaStr = $taishen[$col]['shensha'] ?? '';
                                if ($shenshaStr) {
                                    echo implode('<br>', explode(' ', $shenshaStr));
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 测算告诫，免责声明 -->
    <?php echo $this->get_disclaimer_html(); ?>

    <!-- 返回按钮 -->
    <div style="text-align: center; margin-top: 30px;">
        <button onclick="jQuery('.yfj-result-area').hide(); jQuery('.yfj-ajax-form').fadeIn();"
                style="background: #e2e8f0; color: #334155; border: none; padding: 10px 24px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            <?php echo $this->t('返回重排'); ?>
        </button>
    </div>

</div>

<!-- 核心交互逻辑 -->
<script>
    (function($) {
        const yfjJingpanData = <?php echo wp_json_encode($data); ?>;
        let currentDayunIndex = 0;
        let currentLiunianIndex = 0;

        function formatWuxingHtml(char, size = '18px') {
            if (!char) return '--';
            const wood = ['甲','乙','寅','卯'];
            const fire = ['丙','丁','巳','午'];
            const earth = ['戊','己','辰','丑','戌','未','醜'];
            const metal = ['庚','辛','申','酉'];

            let color = '#2563eb';
            if (wood.includes(char)) color = '#16a34a';
            else if (fire.includes(char)) color = '#dc2626';
            else if (earth.includes(char)) color = '#8b4513';
            else if (metal.includes(char)) color = '#ea580c';

            return `<span style="color:${color}; font-size:${size}; font-weight:bold;">${char}</span>`;
        }

        function setActiveCell(cellElement) {
            $(cellElement).siblings().removeClass('yfj-active-cell');
            $(cellElement).addClass('yfj-active-cell');
        }

        window.yfjGetLiuYear = function(index, element) {
            if (!yfjJingpanData.detail_info || !yfjJingpanData.detail_info.dayun_info) return;

            setActiveCell(element);
            currentDayunIndex = index;
            const dayunNode = yfjJingpanData.detail_info.dayun_info[index];

            for (let i = 0; i < 10; i++) {
                if (dayunNode.liunian_info && dayunNode.liunian_info[i]) {
                    const ln = dayunNode.liunian_info[i];
                    $('#liuNian' + i).html(`<span style="color:#0f172a; font-weight:bold;">${ln.liunian_year}</span><br><span style="font-size:12px; color:#64748b;">(${ln.liunian_age}岁)</span>`);
                } else {
                    $('#liuNian' + i).html('--');
                }
            }

            for (let i = 0; i < 12; i++) { $('#liuYue' + i).html('<span style="color:#94a3b8;"><?php echo $this->t("选流年"); ?></span>'); }

            $('#zhuXingDayun').text(dayunNode.dayun_year_tg_god || '--');
            $('#tianGanDayun').html(formatWuxingHtml(dayunNode.dayun_year_tg));
            $('#diZhiDayun').html(formatWuxingHtml(dayunNode.dayun_year_dz));

            let cgHtml = '';
            if (dayunNode.dayun_year_cg && dayunNode.dayun_year_dz_god) {
                for (let j = 0; j < dayunNode.dayun_year_cg.length; j++) {
                    cgHtml += `${formatWuxingHtml(dayunNode.dayun_year_cg[j], '14px')} ${dayunNode.dayun_year_dz_god[j]}<br>`;
                }
            }
            $('#cangGanDayun').html(cgHtml || '--');

            $('#xingYunDayun').text(dayunNode.dayun_dz_star_cs || '--');
            $('#ziZuoDayun').text(dayunNode.dayun_dz_self_cs || '--');
            $('#kongWangDayun').text(dayunNode.dayun_kongwang || '--');
            $('#naYinDayun').text(dayunNode.dayun_nayin || '--');
            $('#shenShaBigYun').html(dayunNode.dayun_shensha ? dayunNode.dayun_shensha.split(' ').join('<br>') : '--');

            clearColumn('LiuNian');
            clearColumn('LiuYue');

            // 切换大运时，隐藏底部的流日流时面板
            $('#yfj-liurishi-panel').slideUp();
        };

        window.yfjGetLiuMonth = function(index, element) {
            if (!yfjJingpanData.detail_info || !yfjJingpanData.detail_info.dayun_info) return;
            const dayunNode = yfjJingpanData.detail_info.dayun_info[currentDayunIndex];
            if (!dayunNode || !dayunNode.liunian_info || !dayunNode.liunian_info[index]) return;

            setActiveCell(element);
            currentLiunianIndex = index;
            const liunianNode = dayunNode.liunian_info[index];

            for (let i = 0; i < 12; i++) {
                if (liunianNode.liuyue_info && liunianNode.liuyue_info[i]) {
                    const ly = liunianNode.liuyue_info[i];
                    $('#liuYue' + i).html(`<span style="color:#0f172a; font-weight:bold;">${ly.liuyue_month_tg}</span><br><span style="font-size:12px; color:#64748b;">${ly.liuyue_month_dz}</span>`);
                } else {
                    $('#liuYue' + i).html('--');
                }
            }

            $('#zhuXingLiuNian').text(liunianNode.liunian_tg_god || '--');
            $('#tianGanLiuNian').html(formatWuxingHtml(liunianNode.liunian_year_tg));
            $('#diZhiLiuNian').html(formatWuxingHtml(liunianNode.liunian_year_dz));

            let cgHtml = '';
            if (liunianNode.liunian_year_cg && liunianNode.liunian_dz_god) {
                for (let j = 0; j < liunianNode.liunian_year_cg.length; j++) {
                    cgHtml += `${formatWuxingHtml(liunianNode.liunian_year_cg[j], '14px')} ${liunianNode.liunian_dz_god[j]}<br>`;
                }
            }
            $('#cangGanLiuNian').html(cgHtml || '--');

            $('#xingYunLiuNian').text(liunianNode.liunian_dz_star_cs || '--');
            $('#ziZuoLiuNian').text(liunianNode.liunian_dz_self_cs || '--');
            $('#kongWangLiuNian').text(liunianNode.liunian_kongwang || '--');
            $('#naYinLiuNian').text(liunianNode.liunian_nayin || '--');
            $('#shenShaLiuNian').html(liunianNode.liunian_shensha ? liunianNode.liunian_shensha.split(' ').join('<br>') : '--');

            clearColumn('LiuYue');

            // 切换流年时，隐藏底部的流日流时面板
            $('#yfj-liurishi-panel').slideUp();
        };

        // --- 核心拓展：点击流月不仅更新表格，还自动穿透加载流日流时 ---
        window.yfjGetLiuMonthInfo = function(index, element) {
            if (!yfjJingpanData.detail_info || !yfjJingpanData.detail_info.dayun_info) return;
            const liunianNode = yfjJingpanData.detail_info.dayun_info[currentDayunIndex].liunian_info[currentLiunianIndex];
            if (!liunianNode || !liunianNode.liuyue_info || !liunianNode.liuyue_info[index]) return;

            setActiveCell(element);
            const liuyueNode = liunianNode.liuyue_info[index];

            // 1. 渲染原有的流月纵向表格数据
            $('#zhuXingLiuYue').text(liuyueNode.liuyue_month_tg_god || '--');
            $('#tianGanLiuYue').html(formatWuxingHtml(liuyueNode.liuyue_month_tg));
            $('#diZhiLiuYue').html(formatWuxingHtml(liuyueNode.liuyue_month_dz));

            let cgHtml = '';
            if (liuyueNode.liuyue_month_cg && liunianNode.liunian_dz_god) {
                for (let j = 0; j < liuyueNode.liuyue_month_cg.length; j++) {
                    let god = liunianNode.liunian_dz_god[j] || '';
                    cgHtml += `${formatWuxingHtml(liuyueNode.liuyue_month_cg[j], '14px')} ${god}<br>`;
                }
            }
            $('#cangGanLiuYue').html(cgHtml || '--');

            $('#xingYunLiuYue').text(liuyueNode.liuyue_dz_star_cs || '--');
            $('#ziZuoLiuYue').text(liuyueNode.liuyue_dz_self_cs || '--');
            $('#kongWangLiuYue').text(liuyueNode.liuyue_kongwang || '--');
            $('#naYinLiuYue').text(liuyueNode.liuyue_nayin || '--');
            $('#shenShaLiuYue').html(liuyueNode.liuyue_shensha ? liuyueNode.liuyue_shensha.split(' ').join('<br>') : '--');

            // 2. 呼出底部的流日流时面板并进入 Loading 状态
            $('#yfj-liurishi-panel').slideDown();
            $('#yfj-liurishi-title').text(`【 ${liunianNode.liunian_year}<?php echo $this->t('年'); ?> - <?php echo $this->t('第'); ?>${index + 1}<?php echo $this->t('个月'); ?> 】`);
            $('#yfj-liurishi-content').html(`
                <div style="text-align:center; padding: 30px; color:#64748b;">
                    <span class="dashicons dashicons-update yfj-spin" style="font-size: 24px; width: 24px; height: 24px;"></span>
                    <div style="margin-top: 10px;"><?php echo $this->t('正在穿透查询流日流时数据，请稍候...'); ?></div>
                </div>
            `);

            // 3. 从潜伏在页面里的表单中提取八字基础参数
            let formBaziYear = $('select[name="year"]').val() || 1990;
            let formBaziMonth = $('select[name="month"]').val() || 1;
            let formBaziDay = $('select[name="day"]').val() || 1;
            let formBaziHours = $('select[name="hours"]').val() || 12;
            let formBaziMinute = $('select[name="minute"]').val() || 0;
            let formSex = $('input[name="sex"]:checked').val() || 0;
            let formType = $('input[name="type"]:checked').val() || 1;
            let formSect = $('select[name="sect"]').val() || 2;
            let formZhen = $('input[name="zhen"]:checked').val() || 2;
            let formProv = $('select[name="province"]').val() || '';
            let formCity = $('select[name="city"]').val() || '';
            let formLng = $('#raw_longitude').val() || '';
            let formLat = $('#raw_latitude').val() || '';

            // 4. 发起 AJAX 请求
            $.post(yfj_globals.ajax_url, {
                action: 'yfj_get_liurishi',
                nonce: $('#yfj_nonce_field').val(),
                year: parseInt(liunianNode.liunian_year), // API要求的阴历流年
                month: index + 1,                         // API要求的阴历流月 (直接传下标+1)
                sex: formSex,
                bazi_year: formBaziYear,
                bazi_month: formBaziMonth,
                bazi_day: formBaziDay,
                bazi_hours: formBaziHours,
                bazi_minute: formBaziMinute,
                bazi_type: formType,
                bazi_sect: formSect,
                zhen: formZhen,
                province: formProv,
                city: formCity,
                longitude: formLng,
                latitude: formLat
            }, function(res) {
                if(res.success) {
                    renderLiuRiShiAccordion(res.data);
                } else {
                    $('#yfj-liurishi-content').html(`<div style="color:#dc2626; text-align:center; padding: 20px;">❌ <?php echo $this->t('获取数据失败：'); ?> ${res.data}</div>`);
                }
            });
        };

        // --- 将获取到的数据渲染成精美的手风琴折叠面板 ---
        function renderLiuRiShiAccordion(data) {
            if(!data || !data.liurishi_info || data.liurishi_info.length === 0) {
                $('#yfj-liurishi-content').html('<div style="text-align:center; padding:20px; color:#64748b;"><?php echo $this->t("此月份暂无流日流时数据"); ?></div>');
                return;
            }

            let html = '';
            data.liurishi_info.forEach((day, dIndex) => {
                let dayColor = formatWuxingHtml(day.liuri_day_gan, '15px') + formatWuxingHtml(day.liuri_day_zhi, '15px');

            // 使用原生 HTML5 details 标签实现手风琴效果
            html += `
                <details class="yfj-lr-day" ${dIndex === 0 ? 'open' : ''}>
                    <summary class="yfj-lr-summary">
                        <div style="display:flex; align-items:center; flex-wrap:wrap; gap:15px;">
                            <span style="color:#2563eb; width: 140px;">📅 ${day.liuri_solar} (${day.liuri_lunar})</span>
                            <span><?php echo $this->t('干支：'); ?>${dayColor}</span>
                            <span style="color:#64748b; font-weight:normal;"><?php echo $this->t('纳音：'); ?>${day.liuri_nayin}</span>
                        </div>
                        <span style="font-size:12px; color:#94a3b8;">▼ <?php echo $this->t('点击展开12时辰'); ?></span>
                    </summary>
                    <div class="yfj-lr-content">
                        <div style="margin-bottom: 15px; font-size: 13.5px; color: #475569; background: #eff6ff; padding: 12px; border-radius: 6px; border-left: 3px solid #bfdbfe;">
                            <strong><?php echo $this->t('当日神煞：'); ?></strong> ${day.liuri_shensha || '--'} <br>
                            <div style="margin-top: 5px;"><strong><?php echo $this->t('当日留意：'); ?></strong> ${day.liuri_tg_liuyi || '--'} / ${day.liuri_dz_liuyi || '--'}</div>
                        </div>
                        <div class="yfj-table-responsive">
                            <table class="yfj-ls-table">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->t('时辰'); ?></th>
                                        <th><?php echo $this->t('干支'); ?></th>
                                        <th><?php echo $this->t('十神'); ?></th>
                                        <th><?php echo $this->t('星运/自坐'); ?></th>
                                        <th><?php echo $this->t('纳音'); ?></th>
                                        <th><?php echo $this->t('神煞'); ?></th>
                                        <th><?php echo $this->t('留意'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>`;

            if(day.liushi_info && day.liushi_info.length > 0) {
                day.liushi_info.forEach(hour => {
                    let hourColor = formatWuxingHtml(hour.liushi_hour_gan, '14px') + formatWuxingHtml(hour.liushi_hour_zhi, '14px');
                html += `
                                    <tr>
                                        <td><strong>${hour.liushi_hour}<?php echo $this->t('时'); ?></strong></td>
                                        <td>${hourColor}</td>
                                        <td>${hour.liushi_tg_god} / ${(hour.liushi_dz_god||[]).join(',')}</td>
                                        <td>${hour.liushi_dz_star_cs} / ${hour.liushi_dz_self_cs}</td>
                                        <td>${hour.liushi_nayin}</td>
                                        <td style="color:#c99a5b; font-size:12px; text-align:left;">${(hour.liushi_shensha || '--').split(' ').join('<br>')}</td>
                                        <td style="font-size:12px; color:#64748b; text-align:left;">${hour.liushi_tg_liuyi || '--'}<br>${hour.liushi_dz_liuyi || '--'}</td>
                                    </tr>`;
            });
            } else {
                html += `<tr><td colspan="7"><?php echo $this->t("该日暂无流时数据"); ?></td></tr>`;
            }

            html += `       </tbody>
                            </table>
                        </div>
                    </div>
                </details>`;
        });

            $('#yfj-liurishi-content').html(html);
        }

        function clearColumn(suffix) {
            $('#zhuXing' + suffix).text('--');
            $('#tianGan' + suffix).html('--');
            $('#diZhi' + suffix).html('--');
            $('#cangGan' + suffix).html('--');
            $('#xingYun' + suffix).text('--');
            $('#ziZuo' + suffix).text('--');
            $('#kongWang' + suffix).text('--');
            $('#naYin' + suffix).text('--');
            $('#shenSha' + (suffix === 'Dayun' ? 'BigYun' : suffix)).html('--');
        }

    })(jQuery);
</script>