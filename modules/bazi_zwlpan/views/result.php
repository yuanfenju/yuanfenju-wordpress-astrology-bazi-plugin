<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fff3cd; padding:12px 15px; color:#856404; margin-bottom:20px; border-radius:6px; border-left: 4px solid #ffeeba; font-size: 14px; line-height: 1.6;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<?php
// 安全提取数据结构 (注意流盘的数据结构是在 detail_info -> xiantian_info 下)
$base     = $data['base_info'] ?? [];
$detail   = $data['detail_info'] ?? [];
$xiantian = $detail['xiantian_info'] ?? [];
$gong_pan = $xiantian['gong_pan'] ?? [];
$daxian_list = $detail['daxian_info'] ?? [];

// 辅助闭包：渲染四化高亮徽章
$renderSihua = function($sihua, $color) {
    if (!$sihua) return '';
    return "<span style='background-color:{$color}; color:#fff; border-radius:2px; padding:0 3px; font-size:11px; margin-left:2px;'>{$sihua}</span>";
};

// 辅助闭包：渲染单个宫位 (初始加载先天盘)
$renderZwlCell = function($index) use ($gong_pan, $renderSihua, $base) {
    $g = $gong_pan[$index] ?? [];
    if (empty($g)) return '<div class="yfj-zw-cell" id="zw-cell-'.$index.'"></div>';

    $minggong = $g['minggong'] ?? '';

    ob_start();
    ?>
    <div class="yfj-zw-cell" id="zw-cell-<?php echo $index; ?>" data-gong="<?php echo esc_attr($minggong); ?>" data-index="<?php echo $index; ?>">
        <!-- 星曜区 -->
        <div class="yfj-zw-stars">
            <?php if(!empty($g['ziweixing'])): ?>
                <span style="color:#9333ea; font-weight:bold;"><?php echo esc_html($g['ziweixing']); ?></span>
                <?php echo esc_html($g['ziweixing_xingyao'] ?? ''); ?>
                <?php echo $renderSihua($g['ziweixing_xiantian_sihua'] ?? '', '#ef4444'); // 红色(先天) ?><br>
            <?php endif; ?>

            <?php if(!empty($g['tianfuxing'])): ?>
                <span style="color:#9333ea; font-weight:bold;"><?php echo esc_html($g['tianfuxing']); ?></span>
                <?php echo esc_html($g['tianfuxing_xingyao'] ?? ''); ?>
                <?php echo $renderSihua($g['tianfuxing_xiantian_sihua'] ?? '', '#ef4444'); ?><br>
            <?php endif; ?>

            <?php if(!empty($g['monthxing']) || !empty($g['monthxing_xingyao'])): ?>
                <?php echo esc_html($g['monthxing'] ?? ''); ?> <?php echo esc_html($g['monthxing_xingyao'] ?? ''); ?> <?php echo $renderSihua($g['monthxing_xiantian_sihua'] ?? '', '#ef4444'); ?><br>
            <?php endif; ?>

            <?php if(!empty($g['hourxing']) || !empty($g['hourxing_xingyao'])): ?>
                <?php echo esc_html($g['hourxing'] ?? ''); ?> <?php echo esc_html($g['hourxing_xingyao'] ?? ''); ?> <?php echo $renderSihua($g['hourxing_xiantian_sihua'] ?? '', '#ef4444'); ?><br>
            <?php endif; ?>

            <?php if(!empty($g['yearganxing']) || !empty($g['yearganxing_xingyao'])): ?>
                <?php echo esc_html($g['yearganxing'] ?? ''); ?> <?php echo esc_html($g['yearganxing_xingyao'] ?? ''); ?> <?php echo $renderSihua($g['yearganxing_xiantian_sihua'] ?? '', '#ef4444'); ?><br>
            <?php endif; ?>

            <?php if(!empty($g['yearzhixing']) || !empty($g['yearzhixing_xingyao'])): ?>
                <?php echo esc_html($g['yearzhixing'] ?? ''); ?> <?php echo esc_html($g['yearzhixing_xingyao'] ?? ''); ?> <?php echo $renderSihua($g['yearzhixing_xiantian_sihua'] ?? '', '#ef4444'); ?><br>
            <?php endif; ?>

            <?php if(!empty($g['qitaxing']) || !empty($g['qitaxing_xingyao'])): ?>
                <span style="color:#64748b;"><?php echo esc_html($g['qitaxing'] ?? ''); ?> <?php echo esc_html($g['qitaxing_xingyao'] ?? ''); ?> <?php echo $renderSihua($g['qitaxing_xiantian_sihua'] ?? '', '#ef4444'); ?></span><br>
            <?php endif; ?>
        </div>

        <!-- 宫位底部信息区 -->
        <div class="yfj-zw-footer">
            <span style="color:#2563eb;"><?php echo esc_html($g['boshi'] ?? ''); ?></span><br>
            <span style="color:#64748b;"><?php echo esc_html($g['jiangxin'] ?? ''); ?> <?php echo esc_html($g['xiaoxian'] ?? ''); ?></span><br>

            <!-- 流盘专属：年龄与大限 -->
            <div style="font-size: 11px; margin-top:4px; color:#475569;">
                <div><?php echo $this->t('流年：'); ?><?php echo esc_html($g['liunian_age_str'] ?? ''); ?></div>
                <div><?php echo $this->t('小限：'); ?><?php echo esc_html($g['xiaoxian_age_str'] ?? ''); ?></div>
            </div>

            <div style="display:flex; justify-content: space-between; align-items: flex-end; margin-top: 5px;">
                <span style="color:#475569; font-size:11px;"><?php echo esc_html($g['daxian'] ?? ''); ?><br><?php echo esc_html($g['changsheng'] ?? ''); ?></span>
                <span style="color:#dc2626; font-weight:bold; font-size:14px; text-align:right;">
                    <span style="color:#3b82f6; font-size:12px; font-weight:normal; margin-right:4px;"><?php echo esc_html($g['yinshou'] ?? ''); ?></span>
                    <?php echo esc_html($minggong); ?>
                </span>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
};
?>

<style>
    .yfj-result-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }

    .yfj-zw-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: repeat(4, minmax(130px, auto));
        gap: 0px;
        background: #e2e8f0;
        border: 1px solid #94a3b8;
        position: relative;
        overflow: hidden;
    }

    .yfj-zw-cell {
        background: #fff;
        border: 1px solid #cbd5e1;
        padding: 8px;
        font-size: 12px;
        line-height: 1.5;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s ease;
        cursor: crosshair;
        z-index: 2;
    }

    .yfj-zw-cell:hover { background: #fdf8f6; box-shadow: inset 0 0 10px rgba(201, 154, 91, 0.2); }
    .yfj-zw-cell.yfj-active-target { background: #f0f9ff; box-shadow: inset 0 0 0 2px #93c5fd; }
    .yfj-zw-cell.yfj-active-source { background: #fffbeb !important; box-shadow: inset 0 0 0 2px #fbbf24 !important; }

    .yfj-zw-center {
        grid-column: 2 / 4;
        grid-row: 2 / 4;
        background: #f8fafc;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border: 1px solid #cbd5e1;
        z-index: 1;
        font-size: 13px;
        line-height: 1.8;
    }

    .yfj-zw-footer { margin-top: 8px; border-top: 1px dashed #e2e8f0; padding-top: 4px; }

    /* 底部时间流选项卡样式 */
    .yfj-timeline-table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 13px; text-align: center; }
    .yfj-timeline-table th { background: #f1f5f9; color: #475569; padding: 10px; border: 1px solid #cbd5e1; width: 60px;}
    .yfj-timeline-table td { border: 1px solid #e2e8f0; padding: 8px; cursor: pointer; transition: background 0.2s;}
    .yfj-timeline-table td:hover { background: #fdf8f6; color: #c99a5b; }
    .yfj-timeline-table td.yfj-active-time { background: #fff7ed; color: #ea580c; font-weight: bold; border-bottom: 2px solid #ea580c; }

    @keyframes yfj-pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
    .yfj-loading-mask { animation: yfj-pulse 1.5s infinite; filter: blur(2px); pointer-events: none; }
</style>

<div class="yfj-result-wrapper">

    <!-- 核心盘面区 -->
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 24px;">

        <div class="yfj-zw-grid" id="yfj-zw-grid-container">
            <svg id="yfj-zw-svg" style="position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none; z-index:10;"></svg>

            <!-- 第 1 行 (巳 午 未 申) -->
            <?php echo $renderZwlCell(6); ?>
            <?php echo $renderZwlCell(7); ?>
            <?php echo $renderZwlCell(8); ?>
            <?php echo $renderZwlCell(9); ?>

            <!-- 第 2 行 (辰 ... 酉) -->
            <?php echo $renderZwlCell(5); ?>

            <!-- 中宫 (占位 2x2) -->
            <div class="yfj-zw-center">
                <div style="text-align:center; margin-bottom: 10px;">
                    <h3 style="margin:0; color:#1e293b; font-size:18px;"><?php echo $this->t('紫微斗数排盘'); ?> <span id="yfj-current-pan-status" style="font-size:12px; color:#ea580c; font-weight:normal; border:1px solid #ea580c; padding:2px 6px; border-radius:12px;"><?php echo $this->t('先天盘'); ?></span></h3>
                </div>
                <div><strong><?php echo $this->t('姓名：'); ?></strong><?php echo esc_html($base['name'] ?? '-'); ?> &emsp; <strong><?php echo $this->t('性别：'); ?></strong><?php echo esc_html($base['gendertype'] ?? '-'); ?> &emsp; <strong><?php echo $this->t('年龄：'); ?></strong><?php echo esc_html($base['age'] ?? '-'); ?><?php echo $this->t('岁'); ?></div>
                <div><strong><?php echo $this->t('阳历：'); ?></strong><?php echo esc_html($base['gongli'] ?? '-'); ?></div>
                <div><strong><?php echo $this->t('农历：'); ?></strong><?php echo esc_html($base['nongli'] ?? '-'); ?></div>
                <div style="border-top: 1px dashed #cbd5e1; margin: 8px 0;"></div>
                <div><strong><?php echo $this->t('命局：'); ?></strong><span style="color:#dc2626;"><?php echo esc_html($base['mingju'] ?? '-'); ?></span> &emsp; <strong><?php echo $this->t('命四化：'); ?></strong><span style="color:#ea580c;">[<?php echo esc_html($base['mingsihua'] ?? '-'); ?>]</span></div>
                <div><strong><?php echo $this->t('命宫：'); ?></strong><span style="color:#9333ea;"><?php echo esc_html($base['minggong'] ?? '-'); ?></span> &emsp; <strong><?php echo $this->t('身宫：'); ?></strong><span style="color:#9333ea;"><?php echo esc_html($base['shengong'] ?? '-'); ?></span></div>
                <div><strong><?php echo $this->t('子斗：'); ?></strong><span style="color:#2563eb;"><?php echo esc_html($base['zidou'] ?? '-'); ?></span> &emsp; <strong><?php echo $this->t('流斗：'); ?></strong><span style="color:#2563eb;"><?php echo esc_html($base['liudou'] ?? '-'); ?></span></div>
                <div style="border-top: 1px dashed #cbd5e1; margin: 8px 0;"></div>
                <div style="color:#b45309; font-weight:bold;"><?php echo esc_html($base['sex'] ?? ''); ?>：<?php echo esc_html($base['yeargz'] ?? ''); ?> &emsp; <?php echo esc_html($base['monthgz'] ?? ''); ?> &emsp; <?php echo esc_html($base['daygz'] ?? ''); ?> &emsp; <?php echo esc_html($base['hourgz'] ?? ''); ?></div>
                <div style="color:#64748b; font-size:12px;"><?php echo $this->t('纳音：'); ?><?php echo esc_html($base['yearnayin'] ?? ''); ?> &emsp; <?php echo esc_html($base['monthnayin'] ?? ''); ?> &emsp; <?php echo esc_html($base['daynayin'] ?? ''); ?> &emsp; <?php echo esc_html($base['hournayin'] ?? ''); ?></div>
            </div>

            <?php echo $renderZwlCell(10); ?>

            <!-- 第 3 行 (卯 ... 戌) -->
            <?php echo $renderZwlCell(4); ?>
            <?php echo $renderZwlCell(11); ?>

            <!-- 第 4 行 (寅 丑 子 亥) -->
            <?php echo $renderZwlCell(3); ?>
            <?php echo $renderZwlCell(2); ?>
            <?php echo $renderZwlCell(1); ?>
            <?php echo $renderZwlCell(0); ?>

        </div>

        <!-- 动态流盘选项卡 (核心) -->
        <div style="overflow-x: auto; margin-top: 15px;">
            <table class="yfj-timeline-table">
                <tbody>
                <!-- 1. 大限 -->
                <tr id="yfj-row-daxian">
                    <th><?php echo $this->t('大限'); ?></th>
                    <td class="yfj-timeline-btn yfj-active-time" data-model="0" data-key="0"><?php echo $this->t('童限'); ?></td>
                    <?php foreach($daxian_list as $key => $vo): ?>
                        <td class="yfj-timeline-btn" data-model="0" data-key="<?php echo $key + 1; ?>">
                            <?php echo esc_html($vo['daxian_frame_number'] ?? ''); ?><br>
                            <span style="font-size:11px; color:#94a3b8;"><?php echo esc_html($vo['daxian_frame_desc'] ?? ''); ?></span>
                        </td>
                    <?php endforeach; ?>
                </tr>
                <!-- 2. 流年 -->
                <tr id="yfj-row-liunian">
                    <th><?php echo $this->t('流年'); ?></th>
                    <td colspan="10" style="color:#94a3b8; text-align:left; padding-left:15px;"><?php echo $this->t('请先选择大限...'); ?></td>
                </tr>
                <!-- 3. 流月 -->
                <tr id="yfj-row-liuyue">
                    <th><?php echo $this->t('流月'); ?></th>
                    <td colspan="10" style="color:#94a3b8; text-align:left; padding-left:15px;"><?php echo $this->t('等待选择...'); ?></td>
                </tr>
                <!-- 4. 流日 -->
                <tr id="yfj-row-liuri">
                    <th><?php echo $this->t('流日'); ?></th>
                    <td colspan="10" style="color:#94a3b8; text-align:left; padding-left:15px;"><?php echo $this->t('等待选择...'); ?></td>
                </tr>
                <!-- 5. 流时 -->
                <tr id="yfj-row-liushi">
                    <th><?php echo $this->t('流时'); ?></th>
                    <td colspan="10" style="color:#94a3b8; text-align:left; padding-left:15px;"><?php echo $this->t('等待选择...'); ?></td>
                </tr>
                </tbody>
            </table>
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

<!-- JavaScript 核心逻辑 -->
<!-- JavaScript 核心逻辑 -->
<script>
    (function($) {
        // 注入初始 PHP 数据供前端使用
        const initialData = <?php echo wp_json_encode($data); ?>;

        // --- 1. 三方四正画线引擎 ---
        // 注意：这里的字典是用来匹配接口返回的数据值的，属于业务逻辑键名，绝对不能加翻译！
        const gongAliases = {
            "命宫": "命宫", "命宮": "命宫",
            "兄弟宫": "兄弟宫", "兄弟宮": "兄弟宫",
            "夫妻宫": "夫妻宫", "夫妻宮": "夫妻宫",
            "子女宫": "子女宫", "子女宮": "子女宫",
            "财帛宫": "财帛宫", "財帛宮": "财帛宫",
            "疾厄宫": "疾厄宫", "疾厄宮": "疾厄宫",
            "迁移宫": "迁移宫", "遷移宮": "迁移宫",
            "奴仆宫": "奴仆宫", "奴仆宮": "奴仆宫",
            "官禄宫": "官禄宫", "官祿宮": "官禄宫",
            "田宅宫": "田宅宫", "田宅宮": "田宅宫",
            "福德宫": "福德宫", "福德宮": "福德宫",
            "父母宫": "父母宫", "父母宮": "父母宫"
        };

        function getNormalizedGong(rawName) {
            if (!rawName) return "";
            let name = rawName.trim();
            name = name.replace(/^身/, '');
            return gongAliases[name] || name;
        }

        const sanFangSiZhengDict = {
            "命宫": ["财帛宫", "官禄宫", "迁移宫"],
            "兄弟宫": ["疾厄宫", "田宅宫", "奴仆宫"],
            "夫妻宫": ["迁移宫", "福德宫", "官禄宫"],
            "子女宫": ["奴仆宫", "父母宫", "田宅宫"],
            "财帛宫": ["命宫", "官禄宫", "福德宫"],
            "疾厄宫": ["兄弟宫", "田宅宫", "父母宫"],
            "迁移宫": ["夫妻宫", "福德宫", "命宫"],
            "奴仆宫": ["子女宫", "父母宫", "兄弟宫"],
            "官禄宫": ["财帛宫", "命宫", "夫妻宫"],
            "田宅宫": ["疾厄宫", "兄弟宫", "子女宫"],
            "福德宫": ["迁移宫", "夫妻宫", "财帛宫"],
            "父母宫": ["奴仆宫", "子女宫", "疾厄宫"]
        };

        const gridContainer = document.getElementById('yfj-zw-grid-container');
        const svgCanvas = document.getElementById('yfj-zw-svg');

        function drawLine(startX, startY, endX, endY) {
            const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            line.setAttribute('x1', startX);
            line.setAttribute('y1', startY);
            line.setAttribute('x2', endX);
            line.setAttribute('y2', endY);
            line.setAttribute('stroke', '#c99a5b');
            line.setAttribute('stroke-width', '2');
            line.setAttribute('stroke-dasharray', '6, 6');
            line.setAttribute('opacity', '0.8');
            svgCanvas.appendChild(line);
        }

        function drawSanFangSiZhengFor(rawGongName) {
            svgCanvas.innerHTML = '';
            $('.yfj-zw-cell').removeClass('yfj-active-target yfj-active-source');
            const gongName = getNormalizedGong(rawGongName);
            if (!gongName || !sanFangSiZhengDict[gongName]) return;
            const targets = sanFangSiZhengDict[gongName];

            const $sourceCell = $('.yfj-zw-cell').filter(function() {
                return getNormalizedGong($(this).data('gong')) === gongName;
            });

            if ($sourceCell.length === 0) return;
            $sourceCell.addClass('yfj-active-source');

            const gridRect = gridContainer.getBoundingClientRect();
            const sourceRect = $sourceCell[0].getBoundingClientRect();
            const startX = sourceRect.left - gridRect.left + sourceRect.width / 2;
            const startY = sourceRect.top - gridRect.top + sourceRect.height / 2;

            $('.yfj-zw-cell').each(function() {
                const targetGong = getNormalizedGong($(this).data('gong'));
                if (targets.includes(targetGong)) {
                    $(this).addClass('yfj-active-target');
                    const targetRect = this.getBoundingClientRect();
                    const endX = targetRect.left - gridRect.left + targetRect.width / 2;
                    const endY = targetRect.top - gridRect.top + targetRect.height / 2;
                    drawLine(startX, startY, endX, endY);
                }
            });
        }

        $(window).on('load', function() { setTimeout(function() { drawSanFangSiZhengFor("命宫"); }, 100); });

        // 使用事件委托，因为 AJAX 刷新后 DOM 会变化
        $(document).on('mouseenter', '.yfj-zw-cell', function() { drawSanFangSiZhengFor($(this).data('gong')); });
        $(document).on('mouseleave', '.yfj-zw-grid', function() { drawSanFangSiZhengFor("命宫"); });

        let resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                const $hovered = $('.yfj-zw-cell:hover');
                if ($hovered.length > 0) drawSanFangSiZhengFor($hovered.data('gong'));
                else drawSanFangSiZhengFor("命宫");
            }, 200);
        });
        setTimeout(function() { drawSanFangSiZhengFor("命宫"); }, 300);


        // --- 2. 局部渲染模板 (将 JSON 转化为宫位 HTML) ---
        function renderSihuaSpan(text, color) {
            if (!text) return '';
            return `<span style="background-color:${color}; color:#fff; border-radius:2px; padding:0 3px; font-size:11px; margin-left:2px;">${text}</span>`;
        }

        function buildCellHtml(g, index, currentModel) {
            if (!g) return '';

            let starsHtml = '';

            // 紫微星系
            if (g.ziweixing) {
                starsHtml += `<span style="color:#9333ea; font-weight:bold;">${g.ziweixing}</span> ${g.ziweixing_xingyao || ''}
                          ${renderSihuaSpan(g.ziweixing_xiantian_sihua, '#ef4444')}
                          ${renderSihuaSpan(g.ziweixing_daxian_sihua, '#16a34a')}
                          ${renderSihuaSpan(g.ziweixing_xiaoxian_sihua, '#0ea5e9')}
                          ${renderSihuaSpan(g.ziweixing_liunian_sihua, '#2563eb')}
                          ${renderSihuaSpan(g.ziweixing_liuyue_sihua, '#ea580c')}
                          ${renderSihuaSpan(g.ziweixing_liuri_sihua, '#9333ea')}
                          ${renderSihuaSpan(g.ziweixing_liushi_sihua, '#047857')}<br>`;
            }

            // 天府星系
            if (g.tianfuxing) {
                starsHtml += `<span style="color:#9333ea; font-weight:bold;">${g.tianfuxing}</span> ${g.tianfuxing_xingyao || ''}
                          ${renderSihuaSpan(g.tianfuxing_xiantian_sihua, '#ef4444')}
                          ${renderSihuaSpan(g.tianfuxing_daxian_sihua, '#16a34a')}
                          ${renderSihuaSpan(g.tianfuxing_xiaoxian_sihua, '#0ea5e9')}
                          ${renderSihuaSpan(g.tianfuxing_liunian_sihua, '#2563eb')}
                          ${renderSihuaSpan(g.tianfuxing_liuyue_sihua, '#ea580c')}
                          ${renderSihuaSpan(g.tianfuxing_liuri_sihua, '#9333ea')}
                          ${renderSihuaSpan(g.tianfuxing_liushi_sihua, '#047857')}<br>`;
            }

            // 月系
            if (g.monthxing || g.monthxing_xingyao) {
                starsHtml += `${g.monthxing || ''} ${g.monthxing_xingyao || ''}
                          ${renderSihuaSpan(g.monthxing_xiantian_sihua, '#ef4444')}
                          ${renderSihuaSpan(g.monthxing_daxian_sihua, '#16a34a')}
                          ${renderSihuaSpan(g.monthxing_xiaoxian_sihua, '#0ea5e9')}
                          ${renderSihuaSpan(g.monthxing_liunian_sihua, '#2563eb')}
                          ${renderSihuaSpan(g.monthxing_liuyue_sihua, '#ea580c')}
                          ${renderSihuaSpan(g.monthxing_liuri_sihua, '#9333ea')}
                          ${renderSihuaSpan(g.monthxing_liushi_sihua, '#047857')}<br>`;
            }

            // 时系
            if (g.hourxing || g.hourxing_xingyao) {
                starsHtml += `${g.hourxing || ''} ${g.hourxing_xingyao || ''}
                          ${renderSihuaSpan(g.hourxing_xiantian_sihua, '#ef4444')}
                          ${renderSihuaSpan(g.hourxing_daxian_sihua, '#16a34a')}
                          ${renderSihuaSpan(g.hourxing_xiaoxian_sihua, '#0ea5e9')}
                          ${renderSihuaSpan(g.hourxing_liunian_sihua, '#2563eb')}
                          ${renderSihuaSpan(g.hourxing_liuyue_sihua, '#ea580c')}
                          ${renderSihuaSpan(g.hourxing_liuri_sihua, '#9333ea')}
                          ${renderSihuaSpan(g.hourxing_liushi_sihua, '#047857')}<br>`;
            }

            // 年干
            if (g.yearganxing || g.yearganxing_xingyao) {
                starsHtml += `${g.yearganxing || ''} ${g.yearganxing_xingyao || ''}
                          ${renderSihuaSpan(g.yearganxing_xiantian_sihua, '#ef4444')}
                          ${renderSihuaSpan(g.yearganxing_daxian_sihua, '#16a34a')}
                          ${renderSihuaSpan(g.yearganxing_xiaoxian_sihua, '#0ea5e9')}
                          ${renderSihuaSpan(g.yearganxing_liunian_sihua, '#2563eb')}
                          ${renderSihuaSpan(g.yearganxing_liuyue_sihua, '#ea580c')}
                          ${renderSihuaSpan(g.yearganxing_liuri_sihua, '#9333ea')}
                          ${renderSihuaSpan(g.yearganxing_liushi_sihua, '#047857')}<br>`;
            }

            // 年支
            if (g.yearzhixing || g.yearzhixing_xingyao) {
                starsHtml += `${g.yearzhixing || ''} ${g.yearzhixing_xingyao || ''}
                          ${renderSihuaSpan(g.yearzhixing_xiantian_sihua, '#ef4444')}
                          ${renderSihuaSpan(g.yearzhixing_daxian_sihua, '#16a34a')}
                          ${renderSihuaSpan(g.yearzhixing_xiaoxian_sihua, '#0ea5e9')}
                          ${renderSihuaSpan(g.yearzhixing_liunian_sihua, '#2563eb')}
                          ${renderSihuaSpan(g.yearzhixing_liuyue_sihua, '#ea580c')}
                          ${renderSihuaSpan(g.yearzhixing_liuri_sihua, '#9333ea')}
                          ${renderSihuaSpan(g.yearzhixing_liushi_sihua, '#047857')}<br>`;
            }

            // 其他
            if (g.qitaxing || g.qitaxing_xingyao) {
                starsHtml += `<span style="color:#64748b;">${g.qitaxing || ''} ${g.qitaxing_xingyao || ''}
                          ${renderSihuaSpan(g.qitaxing_xiantian_sihua, '#ef4444')}
                          ${renderSihuaSpan(g.qitaxing_daxian_sihua, '#16a34a')}
                          ${renderSihuaSpan(g.qitaxing_xiaoxian_sihua, '#0ea5e9')}
                          ${renderSihuaSpan(g.qitaxing_liunian_sihua, '#2563eb')}
                          ${renderSihuaSpan(g.qitaxing_liuyue_sihua, '#ea580c')}
                          ${renderSihuaSpan(g.qitaxing_liuri_sihua, '#9333ea')}
                          ${renderSihuaSpan(g.qitaxing_liushi_sihua, '#047857')}</span><br>`;
            }

            // 动态底部标签 (加入小限 desc)
            let dynamicTags = '';
            if(currentModel >= 0) dynamicTags += renderSihuaSpan(g.daxian_desc, '#16a34a');
            if(currentModel >= 0) dynamicTags += renderSihuaSpan(g.xiaoxian_desc, '#0ea5e9');
            if(currentModel >= 1) dynamicTags += renderSihuaSpan(g.liunian_desc, '#2563eb');
            if(currentModel >= 2) dynamicTags += renderSihuaSpan(g.liuyue_desc, '#ea580c');
            if(currentModel >= 3) dynamicTags += renderSihuaSpan(g.liuri_desc, '#9333ea');
            if(currentModel >= 4) dynamicTags += renderSihuaSpan(g.liushi_desc, '#047857');

            return `
            <div class="yfj-zw-stars">${starsHtml}</div>
            <div class="yfj-zw-footer">
                <span style="color:#2563eb;">${g.boshi || ''}</span><br>
                <span style="color:#64748b;">${g.jiangxin || ''} ${g.xiaoxian || ''}</span><br>
                <div style="font-size: 11px; margin-top:4px; color:#475569;">
                    <div><?php echo $this->t('流年：'); ?>${g.liunian_age_str || ''}</div>
                    <div><?php echo $this->t('小限：'); ?>${g.xiaoxian_age_str || ''}</div>
                </div>
                <div style="margin-top:4px;">${dynamicTags}</div>
                <div style="display:flex; justify-content: space-between; align-items: flex-end; margin-top: 5px;">
                    <span style="color:#475569; font-size:11px;">${g.daxian || ''}<br>${g.changsheng || ''}</span>
                    <span style="color:#dc2626; font-weight:bold; font-size:14px; text-align:right;">
                        <span style="color:#3b82f6; font-size:12px; font-weight:normal; margin-right:4px;">${g.yinshou || ''}</span>
                        ${g.minggong || ''}
                    </span>
                </div>
            </div>
        `;
        }

        // --- 3. AJAX 穿透交互引擎 ---
        $(document).on('click', '.yfj-timeline-btn', function() {
            // 高亮当前按钮
            $(this).siblings().removeClass('yfj-active-time');
            $(this).addClass('yfj-active-time');

            let targetModel = parseInt($(this).data('model'));
            let targetKey = $(this).data('key'); // 对于 model=0 是 index，对于其他是 timestamp

            // 界面反馈
            $('#yfj-zw-grid-container').addClass('yfj-loading-mask');

            // 【翻译修复点】：替换为 PHP 多语言输出
            let statusText = [
                '<?php echo $this->t("先天/大限盘"); ?>',
                '<?php echo $this->t("流年盘"); ?>',
                '<?php echo $this->t("流月盘"); ?>',
                '<?php echo $this->t("流日盘"); ?>',
                '<?php echo $this->t("流时盘"); ?>'
            ][targetModel];

            // 【翻译修复点】
            $('#yfj-current-pan-status').text(statusText + " (<?php echo $this->t('加载中...'); ?>)");

            // 穿透读取潜伏的表单参数
            let requestData = {
                action: 'yfj_ajax_get_zwlpan',
                nonce: $('#yfj_nonce_field').val(),
                pan_model: targetModel,
                liu_time: targetModel === 0 ? '' : targetKey,

                // 基础八字参数
                name: $('input[name="name"]').val(),
                sex: $('input[name="sex"]:checked').val(),
                type: $('input[name="type"]:checked').val(),
                year: $('select[name="year"]').val(),
                month: $('select[name="month"]').val(),
                day: $('select[name="day"]').val(),
                hours: $('select[name="hours"]').val(),
                minute: $('select[name="minute"]').val(),
                sect: $('select[name="sect"]').val(),
                zhen: $('input[name="zhen"]:checked').val(),
                province: $('select[name="province"]').val(),
                city: $('select[name="city"]').val(),
                longitude: $('#raw_longitude').val(),
                latitude: $('#raw_latitude').val(),

                // 紫微特有参数 (走默认)
                leap_bound: 0,
                pros_cons: 2,
                dx_model: 1
            };

            // 如果是点击的大限（model = 0），我们其实不需要请求接口，因为 initialData 里全都有！
            if (targetModel === 0) {
                handleLocalDaxian(targetKey);
                return;
            }

            // 发起请求获取流年/流月/流日/流时
            $.post(yfj_globals.ajax_url, requestData, function(res) {
                $('#yfj-zw-grid-container').removeClass('yfj-loading-mask');
                $('#yfj-current-pan-status').text(statusText);

                if(res.success) {
                    renderDynamicPan(res.data, targetModel);
                } else {
                    // 【翻译修复点】
                    alert('<?php echo $this->t("获取流盘数据失败："); ?>' + res.data);
                }
            });
        });

        // 处理本地大限数据切换 (免请求)
        function handleLocalDaxian(indexKey) {
            let node;
            if (indexKey === 0) {
                node = initialData.detail_info.xiantian_info;
            } else {
                node = initialData.detail_info.daxian_info[indexKey - 1];
            }

            // 1. 渲染宫位
            renderGongPanCells(node.gong_pan, 0);

            // 2. 渲染下一级时间轴 (流年)
            let $rowLiunian = $('#yfj-row-liunian');
            $rowLiunian.find('td').remove();
            if (node.liunian_list && node.liunian_list.length > 0) {
                node.liunian_list.forEach(function(item) {
                    // 【翻译修复点】：翻译“岁”字
                    $rowLiunian.append(`<td class="yfj-timeline-btn" data-model="1" data-key="${item.liu_year_timestamp}">${item.liu_year}<br><span style="font-size:11px; color:#94a3b8;">${item.liu_sui}<?php echo $this->t('岁'); ?></span></td>`);
                });
            } else {
                $rowLiunian.append(`<td colspan="10" style="color:#94a3b8; text-align:left; padding-left:15px;"><?php echo $this->t('暂无数据'); ?></td>`);
            }

            // 3. 清空更下级
            $('#yfj-row-liuyue, #yfj-row-liuri, #yfj-row-liushi').find('td').remove().end().append(`<td colspan="10" style="color:#94a3b8; text-align:left; padding-left:15px;"><?php echo $this->t('等待选择...'); ?></td>`);

            $('#yfj-zw-grid-container').removeClass('yfj-loading-mask');

            // 【翻译修复点】
            $('#yfj-current-pan-status').text(indexKey === 0 ? '<?php echo $this->t("先天盘"); ?>' : '<?php echo $this->t("大限盘"); ?>');

            // 重绘画线
            setTimeout(function() { drawSanFangSiZhengFor("命宫"); }, 100);
        }

        // 渲染服务器返回的动态流盘数据
        function renderDynamicPan(data, currentModel) {
            let targetNode;
            let nextList = [];
            let nextRowId = '';
            let nextLabelKey = '';

            if (currentModel === 1) {
                targetNode = data.detail_info.liunian_info;
                nextList = targetNode.liuyue_list || [];
                nextRowId = '#yfj-row-liuyue';
                nextLabelKey = 'liu_month';
            } else if (currentModel === 2) {
                targetNode = data.detail_info.liuyue_info;
                nextList = targetNode.liuri_list || [];
                nextRowId = '#yfj-row-liuri';
                nextLabelKey = 'liu_day';
            } else if (currentModel === 3) {
                targetNode = data.detail_info.liuri_info;
                nextList = targetNode.liushi_list || [];
                nextRowId = '#yfj-row-liushi';
                nextLabelKey = 'liu_hour';
            } else if (currentModel === 4) {
                targetNode = data.detail_info.liushi_info;
            }

            if (!targetNode || !targetNode.gong_pan) {
                // 【翻译修复点】
                console.error('<?php echo $this->t("未找到有效的 gong_pan 数据"); ?>', targetNode);
                return;
            }

            // 1. 刷新 12 宫格
            renderGongPanCells(targetNode.gong_pan, currentModel);

            // 2. 刷新下一级时间轴
            if (currentModel < 4) {
                let $nextRow = $(nextRowId);
                $nextRow.find('td').remove();

                if (nextList.length > 0) {
                    nextList.forEach(function(item) {
                        let ts = item[nextLabelKey + '_timestamp'];
                        let label = item[nextLabelKey];
                        $nextRow.append(`<td class="yfj-timeline-btn" data-model="${currentModel + 1}" data-key="${ts}">${label}</td>`);
                    });
                } else {
                    $nextRow.append(`<td colspan="10" style="color:#94a3b8; text-align:left; padding-left:15px;"><?php echo $this->t('暂无数据'); ?></td>`);
                }

                // 清空更下级
                if(currentModel === 1) { $('#yfj-row-liuri, #yfj-row-liushi').find('td').remove().end().append(`<td colspan="10" style="color:#94a3b8; text-align:left; padding-left:15px;"><?php echo $this->t('等待选择...'); ?></td>`); }
                if(currentModel === 2) { $('#yfj-row-liushi').find('td').remove().end().append(`<td colspan="10" style="color:#94a3b8; text-align:left; padding-left:15px;"><?php echo $this->t('等待选择...'); ?></td>`); }
            }

            // 重绘
            setTimeout(function() { drawSanFangSiZhengFor("命宫"); }, 100);
        }

        // 执行 DOM 替换
        function renderGongPanCells(gong_pan_array, currentModel) {
            for(let i=0; i<12; i++) {
                let html = buildCellHtml(gong_pan_array[i], i, currentModel);
                let $cell = $('#zw-cell-' + i);
                $cell.data('gong', gong_pan_array[i].minggong || '');
                $cell.html(html);
            }
        }

    })(jQuery);
</script>