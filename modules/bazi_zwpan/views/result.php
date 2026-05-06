<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fff3cd; padding:12px 15px; color:#856404; margin-bottom:20px; border-radius:6px; border-left: 4px solid #ffeeba; font-size: 14px; line-height: 1.6;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<?php
// 安全提取数据
$base     = $data['base_info'] ?? [];
$gong_pan = $data['gong_pan'] ?? [];

// --- 核心优化 1：PHP 闭包渲染器，彻底告别重复代码 ---
$renderZwCell = function($index) use ($gong_pan) {
    $g = $gong_pan[$index] ?? [];
    if (empty($g)) return '<div class="yfj-zw-cell"></div>';

    // 安全提取属性
    $minggong = $g['minggong'] ?? '';

    // 渲染四化高亮徽章
    $renderSihua = function($sihua) {
        return $sihua ? "<span class='yfj-sihua-badge'>{$sihua}</span>" : "";
    };

    ob_start();
    ?>
    <div class="yfj-zw-cell" data-gong="<?php echo esc_attr($minggong); ?>">
        <div class="yfj-zw-stars">
            <?php if(!empty($g['ziweixing'])): ?>
                <span style="color:#9333ea; font-weight:bold;"><?php echo esc_html($g['ziweixing']); ?></span>
                <?php echo esc_html($g['ziweixing_xingyao'] ?? ''); ?>
                <?php echo $renderSihua($g['ziweixing_sihua'] ?? ''); ?><br>
            <?php endif; ?>

            <?php if(!empty($g['tianfuxing'])): ?>
                <span style="color:#9333ea; font-weight:bold;"><?php echo esc_html($g['tianfuxing']); ?></span>
                <?php echo esc_html($g['tianfuxing_xingyao'] ?? ''); ?>
                <?php echo $renderSihua($g['tianfuxing_sihua'] ?? ''); ?><br>
            <?php endif; ?>

            <?php if(!empty($g['monthxing']) || !empty($g['monthxing_xingyao'])): ?>
                <?php echo esc_html($g['monthxing'] ?? ''); ?> <?php echo esc_html($g['monthxing_xingyao'] ?? ''); ?> <?php echo $renderSihua($g['monthxing_sihua'] ?? ''); ?><br>
            <?php endif; ?>

            <?php if(!empty($g['hourxing']) || !empty($g['hourxing_xingyao'])): ?>
                <?php echo esc_html($g['hourxing'] ?? ''); ?> <?php echo esc_html($g['hourxing_xingyao'] ?? ''); ?> <?php echo $renderSihua($g['hourxing_sihua'] ?? ''); ?><br>
            <?php endif; ?>

            <?php if(!empty($g['yearganxing']) || !empty($g['yearganxing_xingyao'])): ?>
                <?php echo esc_html($g['yearganxing'] ?? ''); ?> <?php echo esc_html($g['yearganxing_xingyao'] ?? ''); ?> <?php echo $renderSihua($g['yearganxing_sihua'] ?? ''); ?><br>
            <?php endif; ?>

            <?php if(!empty($g['yearzhixing']) || !empty($g['yearzhixing_xingyao'])): ?>
                <?php echo esc_html($g['yearzhixing'] ?? ''); ?> <?php echo esc_html($g['yearzhixing_xingyao'] ?? ''); ?> <?php echo $renderSihua($g['yearzhixing_sihua'] ?? ''); ?><br>
            <?php endif; ?>

            <?php if(!empty($g['qitaxing']) || !empty($g['qitaxing_xingyao'])): ?>
                <span style="color:#64748b;"><?php echo esc_html($g['qitaxing'] ?? ''); ?> <?php echo esc_html($g['qitaxing_xingyao'] ?? ''); ?> <?php echo $renderSihua($g['qitaxing_sihua'] ?? ''); ?></span><br>
            <?php endif; ?>
        </div>

        <div class="yfj-zw-footer">
            <span style="color:#2563eb;"><?php echo esc_html($g['boshi'] ?? ''); ?></span><br>
            <span style="color:#64748b;"><?php echo esc_html($g['jiangxin'] ?? ''); ?> <?php echo esc_html($g['xiaoxian'] ?? ''); ?></span><br>
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

    /* 紫微十二宫 CSS Grid 网格布局 */
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

    /* 宫位单元格样式 */
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

    .yfj-zw-cell:hover {
        background: #fdf8f6;
        box-shadow: inset 0 0 10px rgba(201, 154, 91, 0.2);
    }

    .yfj-zw-cell.yfj-active-target {
        background: #f0f9ff;
        box-shadow: inset 0 0 0 2px #93c5fd;
    }

    /* 中宫信息区 (跨越 2 行 2 列) */
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

    /* 内部小元素样式 */
    .yfj-sihua-badge { background: #ea580c; color: #fff; border-radius: 2px; padding: 0 3px; font-size: 11px; margin-left: 2px; }
    .yfj-zw-footer { margin-top: 8px; border-top: 1px dashed #e2e8f0; padding-top: 4px; }
</style>

<div class="yfj-result-wrapper">

    <!-- 盘面外层面板 -->
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 24px;">

        <!-- 核心命盘区 -->
        <div class="yfj-zw-grid" id="yfj-zw-grid-container">

            <!-- 动态生成的 SVG 画布，用于绘制三方四正连线 -->
            <svg id="yfj-zw-svg" style="position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none; z-index:10;"></svg>

            <!-- 第 1 行 (巳 午 未 申) -->
            <?php echo $renderZwCell(6); ?>
            <?php echo $renderZwCell(7); ?>
            <?php echo $renderZwCell(8); ?>
            <?php echo $renderZwCell(9); ?>

            <!-- 第 2 行 (辰 ... 酉) -->
            <?php echo $renderZwCell(5); ?>

            <!-- 中宫 (占位 2x2) -->
            <div class="yfj-zw-center">
                <div style="text-align:center; margin-bottom: 10px;">
                    <h3 style="margin:0; color:#1e293b; font-size:18px;"><?php echo $this->t('紫微斗数排盘'); ?></h3>
                </div>
                <div><strong><?php echo $this->t('姓名：'); ?></strong><?php echo esc_html($base['name'] ?? '-'); ?> &emsp; <strong><?php echo $this->t('性别：'); ?></strong><?php echo esc_html($base['gendertype'] ?? '-'); ?> &emsp; <strong><?php echo $this->t('年龄：'); ?></strong><?php echo esc_html($base['age'] ?? '-'); ?><?php echo $this->t('岁'); ?></div>
                <div><strong><?php echo $this->t('阳历：'); ?></strong><?php echo esc_html($base['gongli'] ?? '-'); ?></div>
                <div><strong><?php echo $this->t('农历：'); ?></strong><?php echo esc_html($base['nongli'] ?? '-'); ?></div>
                <div style="border-top: 1px dashed #cbd5e1; margin: 8px 0;"></div>
                <div><strong><?php echo $this->t('命局：'); ?></strong><span style="color:#dc2626;"><?php echo esc_html($base['mingju'] ?? '-'); ?></span> &emsp; <strong><?php echo $this->t('命四化：'); ?></strong><span style="color:#ea580c;">[<?php echo esc_html($base['mingsihua'] ?? '-'); ?>]</span></div>
                <div><strong><?php echo $this->t('命宫在：'); ?></strong><span style="color:#9333ea;"><?php echo esc_html($base['minggong'] ?? '-'); ?></span> &emsp; <strong><?php echo $this->t('身宫在：'); ?></strong><span style="color:#9333ea;"><?php echo esc_html($base['shengong'] ?? '-'); ?></span></div>
                <div><strong><?php echo $this->t('命主：'); ?></strong><span style="color:#16a34a;"><?php echo esc_html($base['mingzhu'] ?? '-'); ?></span> &emsp; <strong><?php echo $this->t('身主：'); ?></strong><span style="color:#16a34a;"><?php echo esc_html($base['shenzhu'] ?? '-'); ?></span></div>
                <div style="border-top: 1px dashed #cbd5e1; margin: 8px 0;"></div>
                <div style="color:#b45309; font-weight:bold;"><?php echo esc_html($base['sex'] ?? ''); ?>：<?php echo esc_html($base['yeargz'] ?? ''); ?> &emsp; <?php echo esc_html($base['monthgz'] ?? ''); ?> &emsp; <?php echo esc_html($base['daygz'] ?? ''); ?> &emsp; <?php echo esc_html($base['hourgz'] ?? ''); ?></div>
                <div style="color:#64748b; font-size:12px;"><?php echo $this->t('纳音：'); ?><?php echo esc_html($base['yearnayin'] ?? ''); ?> &emsp; <?php echo esc_html($base['monthnayin'] ?? ''); ?> &emsp; <?php echo esc_html($base['daynayin'] ?? ''); ?> &emsp; <?php echo esc_html($base['hournayin'] ?? ''); ?></div>
            </div>

            <?php echo $renderZwCell(10); ?>

            <!-- 第 3 行 (卯 ... 戌) -->
            <?php echo $renderZwCell(4); ?>
            <?php echo $renderZwCell(11); ?>

            <!-- 第 4 行 (寅 丑 子 亥) -->
            <?php echo $renderZwCell(3); ?>
            <?php echo $renderZwCell(2); ?>
            <?php echo $renderZwCell(1); ?>
            <?php echo $renderZwCell(0); ?>

        </div>
    </div>

    <!-- 盘面外层面板 结束 -->

    <!-- 排盘简批 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-media-text"></span> <?php echo $this->t('紫微斗数批断'); ?>
        </div>
        <div class="yfj-panel-body">
            <?php if (!empty($gong_pan) && is_array($gong_pan)): ?>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <?php foreach($gong_pan as $vo): ?>
                        <div style="background: #f8fafc; padding: 15px; border-radius: 6px; border-left: 4px solid #9333ea; border-bottom: 1px solid #e2e8f0;">
                            <strong style="font-size: 15px; color: #1e293b;"><?php echo esc_html($vo['minggong'] ?? ''); ?><?php echo $this->t('：'); ?></strong>
                            <div style="margin-top: 8px; font-size: 14px; line-height: 1.6; color: #475569;">
                                <span style="color: #9333ea; font-weight: bold;">[<?php echo $this->t('紫微主曜'); ?>]：</span><?php echo esc_html($vo['ziweixing_desc'] ?? '--'); ?><br>
                                <span style="color: #c99a5b; font-weight: bold;">[<?php echo $this->t('天府主曜'); ?>]：</span><?php echo esc_html($vo['tianfuxing_desc'] ?? '--'); ?><br>
                                <span style="color: #2563eb; font-weight: bold;">[<?php echo $this->t('辅曜批断'); ?>]：</span><?php echo esc_html($vo['fuxing_desc'] ?? '--'); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; color: #94a3b8; padding: 20px;">
                    <?php echo $this->t('暂无数据'); ?>
                </div>
            <?php endif; ?>
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

<!-- 核心交互逻辑：三方四正寻路引擎 (繁简通杀终极版) -->
<script>
    (function($) {
        // 1. 建立繁简体与别名的「归一化映射字典」
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

        // 获取归一化名称的辅助函数
        function getNormalizedGong(rawName) {
            if (!rawName) return "";
            let name = rawName.trim();
            return gongAliases[name] || name;
        }

        // 2. 三方四正标准关系树 (使用标准简体)
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

        // 3. 画线核心函数
        function drawLine(startX, startY, endX, endY) {
            const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            line.setAttribute('x1', startX);
            line.setAttribute('y1', startY);
            line.setAttribute('x2', endX);
            line.setAttribute('y2', endY);
            line.setAttribute('stroke', '#c99a5b'); // 金色虚线
            line.setAttribute('stroke-width', '1.5');
            line.setAttribute('stroke-dasharray', '6, 6');
            line.setAttribute('opacity', '0.8');
            svgCanvas.appendChild(line);
        }

        // 4. 集中绘制逻辑 (加入繁简转换)
        function drawSanFangSiZhengFor(rawGongName) {
            // 清空画布和所有高亮
            svgCanvas.innerHTML = '';
            $('.yfj-zw-cell').removeClass('yfj-active-target yfj-active-source');

            // 将传进来的原始名称转化为标准简体
            const gongName = getNormalizedGong(rawGongName);

            if (!gongName || !sanFangSiZhengDict[gongName]) return;

            const targets = sanFangSiZhengDict[gongName];

            // 找到源宫位元素 (对比归一化后的名称)
            const $sourceCell = $('.yfj-zw-cell').filter(function() {
                return getNormalizedGong($(this).data('gong')) === gongName;
            });

            if ($sourceCell.length === 0) return;

            // 源宫位高亮提示
            $sourceCell.addClass('yfj-active-source');

            // 计算源坐标
            const gridRect = gridContainer.getBoundingClientRect();
            const sourceRect = $sourceCell[0].getBoundingClientRect();
            const startX = sourceRect.left - gridRect.left + sourceRect.width / 2;
            const startY = sourceRect.top - gridRect.top + sourceRect.height / 2;

            // 遍历整个盘面寻找目标宫位并画线
            $('.yfj-zw-cell').each(function() {
                const targetGong = getNormalizedGong($(this).data('gong'));

                // 如果目标名称在字典里，则连线
                if (targets.includes(targetGong)) {
                    $(this).addClass('yfj-active-target');
                    const targetRect = this.getBoundingClientRect();
                    const endX = targetRect.left - gridRect.left + targetRect.width / 2;
                    const endY = targetRect.top - gridRect.top + targetRect.height / 2;
                    drawLine(startX, startY, endX, endY);
                }
            });
        }

        // 5. 初始化：等 DOM 渲染完后，默认画出“命宫”
        $(window).on('load', function() {
            setTimeout(function() {
                drawSanFangSiZhengFor("命宫"); // 这里传简繁体都可以，底层会自动识别
            }, 100);
        });

        // 6. 鼠标悬停：实时切换
        $('.yfj-zw-cell').on('mouseenter', function() {
            const sourceGong = $(this).data('gong');
            drawSanFangSiZhengFor(sourceGong);
        });

        // 7. 鼠标移出：恢复默认
        $('.yfj-zw-grid').on('mouseleave', function() {
            drawSanFangSiZhengFor("命宫");
        });

        // 8. 窗口缩放自适应重绘
        let resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                const $hovered = $('.yfj-zw-cell:hover');
                if ($hovered.length > 0) {
                    drawSanFangSiZhengFor($hovered.data('gong'));
                } else {
                    drawSanFangSiZhengFor("命宫");
                }
            }, 200);
        });

        // 兜底执行
        setTimeout(function() { drawSanFangSiZhengFor("命宫"); }, 300);

    })(jQuery);
</script>

<style>
    /* 补上源宫位的高亮样式，让交互更明显 */
    .yfj-zw-cell.yfj-active-source {
        background: #fffbeb !important;
        box-shadow: inset 0 0 0 2px #fbbf24 !important;
    }
</style>