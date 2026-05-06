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

$base = $data;
$sizhu = $data['sizhu_info'] ?? [];
$xunkong = $data['xunkong_info'] ?? [];
$sike = $data['sike_info'] ?? [];
$sanchuan = $data['sanchuan_info'] ?? [];
$desc = $data['daliuren_desc'] ?? [];
$gong_pan = $data['gong_pan'] ?? [];

// 十二地支盘的网格位置映射 (基于 CSS Grid 4x4)
// 位置： 6(巳) 7(午) 8(未) 9(申)
//        5(辰) [ 镂空 ] 10(酉)
//        4(卯) [ 镂空 ] 11(戌)
//        3(寅) 2(丑) 1(子) 0(亥)
$dlr_layout = [
    6 => 'grid-column: 1; grid-row: 1;',
    7 => 'grid-column: 2; grid-row: 1;',
    8 => 'grid-column: 3; grid-row: 1;',
    9 => 'grid-column: 4; grid-row: 1;',
    5 => 'grid-column: 1; grid-row: 2;',
    10=> 'grid-column: 4; grid-row: 2;',
    4 => 'grid-column: 1; grid-row: 3;',
    11=> 'grid-column: 4; grid-row: 3;',
    3 => 'grid-column: 1; grid-row: 4;',
    2 => 'grid-column: 2; grid-row: 4;',
    1 => 'grid-column: 3; grid-row: 4;',
    0 => 'grid-column: 4; grid-row: 4;',
];
?>

<style>
    .yfj-dlr-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 24px; overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #0f172a; font-size: 16px; display: flex; align-items: center; gap: 8px; }
    .yfj-panel-body { padding: 20px; font-size: 14px; line-height: 1.8; }

    /* 基本信息 */
    .yfj-dlr-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px; }
    .yfj-dlr-info-col { background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0; }
    .yfj-dlr-info-col p { margin: 0 0 8px 0; }
    .yfj-dlr-highlight { color: #dc2626; font-weight: bold; }
    .yfj-dlr-blue { color: #2563eb; font-weight: bold; }
    .yfj-dlr-green { color: #16a34a; font-weight: bold; }

    /* 天地神盘 - CSS Grid 重构 */
    .yfj-dlr-12palace { display: grid; grid-template-columns: repeat(4, 1fr); grid-template-rows: repeat(4, 1fr); gap: 6px; max-width: 500px; margin: 0 auto 20px auto; background: #cbd5e1; padding: 6px; border-radius: 8px; }
    .yfj-dlr-cell { background: #fff; border-radius: 4px; padding: 10px 5px; text-align: center; display: flex; flex-direction: column; justify-content: center; gap: 8px; box-shadow: inset 0 0 10px rgba(0,0,0,0.02); }
    .yfj-dlr-center-hole { grid-column: 2 / 4; grid-row: 2 / 4; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold; color: #94a3b8; letter-spacing: 4px; writing-mode: vertical-rl; text-orientation: upright; background: #f1f5f9; border-radius: 4px; border: 1px dashed #cbd5e1; }

    .yfj-c-shen { color: #dc2626; font-size: 16px; font-weight: bold; }
    .yfj-c-tian { color: #2563eb; font-size: 16px; font-weight: bold; }
    .yfj-c-di { color: #64748b; font-size: 13px; }

    /* 四课展示 */
    .yfj-dlr-sike { display: flex; justify-content: space-around; max-width: 400px; margin: 0 auto; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; }
    .yfj-sike-col { display: flex; flex-direction: column; align-items: center; gap: 10px; font-size: 16px; font-weight: bold; }
    .yfj-sike-v1 { color: #16a34a; } /* 贵神 */
    .yfj-sike-v2 { color: #2563eb; } /* 四课 */
    .yfj-sike-v3 { color: #64748b; font-size: 14px; } /* 旬课 */

    /* 三传展示 */
    .yfj-dlr-sanchuan { max-width: 400px; margin: 20px auto 0 auto; display: flex; flex-direction: column; gap: 8px; }
    .yfj-sc-row { display: flex; justify-content: space-between; align-items: center; background: #fffbeb; border: 1px solid #fde68a; padding: 12px 20px; border-radius: 6px; font-size: 16px; font-weight: bold; }
    .yfj-sc-badge { background: #d97706; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 14px; }
    .yfj-sc-liuqin { color: #475569; }
    .yfj-sc-ganzhi { color: #dc2626; font-size: 18px; }
    .yfj-sc-guishen { color: #16a34a; }

    /* 简批列表 */
    .yfj-dlr-desc-grid { display: grid; grid-template-columns: 1fr; gap: 10px; }
    @media (min-width: 600px) { .yfj-dlr-desc-grid { grid-template-columns: 1fr 1fr; } }
    .yfj-desc-item { background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0; }
    .yfj-desc-label { font-weight: bold; color: #b45309; margin-bottom: 4px; display: block; }
</style>

<div class="yfj-dlr-wrapper">

    <!-- 1. 基本信息 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-id-alt"></span> <?php echo $this->t('基本信息'); ?></div>
        <div class="yfj-panel-body">
            <div class="yfj-dlr-info-grid">
                <div class="yfj-dlr-info-col">
                    <p><strong><?php echo $this->t('姓名：'); ?></strong> <?php echo esc_html($base['name']); ?> (<?php echo esc_html($base['sex']); ?>)</p>
                    <p><strong><?php echo $this->t('公历：'); ?></strong> <?php echo esc_html($base['gongli']); ?></p>
                    <p><strong><?php echo $this->t('农历：'); ?></strong> <?php echo esc_html($base['nongli']); ?></p>
                    <p><strong><?php echo $this->t('节气：'); ?></strong> <?php echo esc_html($base['jieqi']); ?></p>
                </div>
                <div class="yfj-dlr-info-col">
                    <p><strong><?php echo $this->t('四柱：'); ?></strong> <span class="yfj-dlr-highlight"><?php echo esc_html($sizhu['year_gan'].$sizhu['year_zhi'].' '); ?><?php echo esc_html($sizhu['month_gan'].$sizhu['month_zhi'].' '); ?><?php echo esc_html($sizhu['day_gan'].$sizhu['day_zhi'].' '); ?><?php echo esc_html($sizhu['hour_gan'].$sizhu['hour_zhi'].' '); ?></span></p>
                    <p><strong><?php echo $this->t('旬空：'); ?></strong> <span class="yfj-dlr-blue"><?php echo esc_html($xunkong['year_xunkong'].' '.$xunkong['month_xunkong'].' '.$xunkong['day_xunkong'].' '.$xunkong['hour_xunkong']); ?></span></p>
                    <p><strong><?php echo $this->t('月将：'); ?></strong> <span class="yfj-dlr-green"><?php echo esc_html($base['yuejiang'] ?? '-'); ?></span></p>
                    <p><strong><?php echo $this->t('年命：'); ?></strong> <span class="yfj-dlr-green"><?php echo esc_html($base['nianming'] ?? '-'); ?></span> &nbsp;&nbsp; <strong><?php echo $this->t('行年：'); ?></strong> <span class="yfj-dlr-green"><?php echo esc_html($base['hangnian'] ?? '-'); ?></span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. 大六壬排盘 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-grid-view"></span> <?php echo $this->t('大六壬神机盘'); ?></div>
        <div class="yfj-panel-body">

            <!-- 天地神盘 12宫 (CSS Grid) -->
            <div class="yfj-dlr-12palace">
                <div class="yfj-dlr-center-hole"><?php echo $this->t('天地神盘'); ?></div>
                <?php foreach($dlr_layout as $idx => $grid_css):
                    $g = $gong_pan[$idx] ?? [];
                    ?>
                    <div class="yfj-dlr-cell" style="<?php echo $grid_css; ?>">
                        <div class="yfj-c-shen"><?php echo esc_html($this->t($g['shenpan'] ?? '')); ?></div>
                        <div class="yfj-c-tian"><?php echo esc_html($this->t($g['tianpan'] ?? '')); ?></div>
                        <div class="yfj-c-di"><?php echo esc_html($this->t($g['dipan'] ?? '')); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- 四课展示 -->
            <div style="text-align: center; margin: 30px 0 10px 0; font-size: 16px; font-weight: bold; color: #0f172a;"><?php echo $this->t('大六壬四课'); ?></div>
            <div class="yfj-dlr-sike">
                <?php for($i=0; $i<4; $i++): $sk = $sike[$i] ?? []; ?>
                    <div class="yfj-sike-col">
                        <div class="yfj-sike-v1"><?php echo esc_html($this->t($sk['guishen'] ?? '-')); ?></div>
                        <div class="yfj-sike-v2"><?php echo esc_html($this->t($sk['sike'] ?? '-')); ?></div>
                        <div class="yfj-sike-v3"><?php echo esc_html($this->t($sk['xunke'] ?? '-')); ?></div>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- 三传展示 -->
            <div style="text-align: center; margin: 30px 0 10px 0; font-size: 16px; font-weight: bold; color: #0f172a;"><?php echo $this->t('大六壬三传'); ?></div>
            <div class="yfj-dlr-sanchuan">
                <?php foreach($sanchuan as $sc): ?>
                    <div class="yfj-sc-row">
                        <div class="yfj-sc-badge"><?php echo esc_html($this->t($sc['sanchuan_biaoshi'] ?? '')); ?></div>
                        <div class="yfj-sc-liuqin"><?php echo esc_html($this->t($sc['sanchuan_liuqin'] ?? '')); ?></div>
                        <div class="yfj-sc-ganzhi"><?php echo esc_html($this->t($sc['sanchuan_ganzhi'] ?? '')); ?></div>
                        <div class="yfj-sc-guishen"><?php echo esc_html($this->t($sc['sanchuan_guishen'] ?? '')); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>

    <!-- 3. 大六壬简批 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading"><span class="dashicons dashicons-welcome-learn-more"></span> <?php echo $this->t('大六壬简批断语'); ?></div>
        <div class="yfj-panel-body">

            <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <div style="font-size: 16px; margin-bottom: 8px;">
                    <strong><?php echo $this->t('课体：'); ?></strong> <span style="color: #dc2626;"><?php echo esc_html($this->t($desc['keti'] ?? '-')); ?></span>
                </div>
                <div style="font-size: 15px; margin-bottom: 8px;">
                    <strong><?php echo $this->t('课义：'); ?></strong> <span style="color: #2563eb;"><?php echo esc_html($this->t($desc['keyi'] ?? '-')); ?></span>
                </div>
                <div style="font-size: 14.5px; color: #475569; margin-bottom: 8px;">
                    <strong><?php echo $this->t('解曰：'); ?></strong> <?php echo esc_html($this->t($desc['jieyue'] ?? '-')); ?>
                </div>
                <div style="font-size: 14.5px; color: #475569;">
                    <strong><?php echo $this->t('断曰：'); ?></strong> <?php echo esc_html($this->t($desc['duanyue'] ?? '-')); ?>
                </div>
            </div>

            <!-- 具体事象断语 -->
            <?php
            $m_items = [
                'tianqi' => '天气', 'moushi' => '谋事', 'jiazhai' => '家宅', 'hunyin' => '婚姻',
                'jibing' => '疾病', 'huaiyun' => '怀孕', 'qiucai' => '求财', 'xunren' => '寻人',
                'shiwu' => '失物', 'xingren' => '行人', 'chuxing' => '出行'
            ];
            ?>
            <div class="yfj-dlr-desc-grid">
                <?php foreach($m_items as $key => $label): if(!empty($desc[$key])): ?>
                    <div class="yfj-desc-item">
                        <span class="yfj-desc-label">【<?php echo $this->t($label); ?>】</span>
                        <?php echo esc_html($this->t($desc[$key])); ?>
                    </div>
                <?php endif; endforeach; ?>
            </div>

        </div>
    </div>

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