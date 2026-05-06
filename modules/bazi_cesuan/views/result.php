<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fff3cd; padding:12px 15px; color:#856404; margin-bottom:20px; border-radius:6px; border-left: 4px solid #ffeeba; font-size: 14px; line-height: 1.6;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<?php
// 安全提取所有数据结构，防止报错
$base       = $data['base_info'] ?? [];
$bazi       = $data['bazi_info'] ?? [];
$xys        = $data['xiyongshen'] ?? [];
$sizhu      = $data['sizhu'] ?? [];
$wuxing     = $data['wuxing'] ?? [];
$caiyun     = $data['caiyun']['sanshishu_caiyun'] ?? [];
$yinyuan    = $data['yinyuan'] ?? [];
$chenggu    = $data['chenggu'] ?? [];
$mingyun    = $data['mingyun'] ?? [];

// 基础变量提取
$name    = $base['name'] ?? ($data['name'] ?? '未知');
$sex     = $base['sex'] ?? '未知';
$display_sex = str_replace(['乾造', '坤造'], ['男', '女'], $sex); // 把专业术语替换为男女
$gongli  = $base['gongli'] ?? '';
$nongli  = $base['nongli'] ?? '';
$zhengge = $base['zhengge'] ?? '未知';
$sx      = $data['sx'] ?? '';
$xz      = $data['xz'] ?? '';
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
    .yfj-highlight { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: #0f172a; font-weight: 500; }
</style>

<div class="yfj-result-wrapper">

    <!-- 1. 基础信息 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-id-alt"></span> <?php echo $this->t('基本信息'); ?>
        </div>
        <div class="yfj-panel-body yfj-info-grid">
            <div><strong><?php echo $this->t('命主姓名：'); ?></strong> <?php echo esc_html($name); ?> &nbsp;|&nbsp; <strong><?php echo $this->t('性别：'); ?></strong> <?php echo esc_html($display_sex); ?></div>
            <div><strong><?php echo $this->t('生肖星座：'); ?></strong> <?php echo esc_html($sx); ?> / <?php echo esc_html($xz); ?></div>
            <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('出生公历：'); ?></strong> <?php echo esc_html($gongli); ?></div>
            <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('出生农历：'); ?></strong> <?php echo esc_html($nongli); ?></div>

            <div style="grid-column: 1 / -1; margin-top: 5px; padding-top: 10px; border-top: 1px dashed #e2e8f0;">
                <strong><?php echo $this->t('四柱八字：'); ?></strong> <span class="yfj-badge-red" style="font-size: 16px;"><?php echo esc_html($bazi['bazi'] ?? ''); ?></span>
            </div>
            <div>
                <strong><?php echo $this->t('年柱纳音：'); ?></strong> <span class="yfj-badge-blue"><?php echo esc_html($bazi['na_yin'] ?? ''); ?></span>
            </div>
            <div>
                <strong><?php echo $this->t('命理格局：'); ?></strong> <span class="yfj-highlight"><?php echo esc_html($zhengge); ?></span>
            </div>
        </div>
    </div>

    <!-- 2. 喜用神分析 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-chart-pie"></span> <?php echo $this->t('喜用神分析'); ?>
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
                <?php echo $this->t('以'); ?> <span class="yfj-highlight"><?php echo esc_html($xys['jishen'] ?? '-'); ?></span> <?php echo $this->t('为忌神'); ?>。
            </p>
            <div style="background: #f8fafc; padding: 12px; border-radius: 6px; margin-top: 15px; border-left: 3px solid #cbd5e1;">
                <strong><?php echo $this->t('五行参考：'); ?></strong> <?php echo esc_html($xys['xiyongshen_desc'] ?? ''); ?>
            </div>
            <div style="margin-top: 10px;">
                <strong><?php echo $this->t('五行统计：'); ?></strong>
                <?php echo esc_html($xys['jin_number'] ?? '0'); ?><?php echo $this->t('金'); ?>，
                <?php echo esc_html($xys['mu_number'] ?? '0'); ?><?php echo $this->t('木'); ?>，
                <?php echo esc_html($xys['shui_number'] ?? '0'); ?><?php echo $this->t('水'); ?>，
                <?php echo esc_html($xys['huo_number'] ?? '0'); ?><?php echo $this->t('火'); ?>，
                <?php echo esc_html($xys['tu_number'] ?? '0'); ?><?php echo $this->t('土'); ?>
            </div>
        </div>
    </div>

    <!-- 3. 日柱论命 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-calendar-alt"></span> <?php echo $this->t('日柱论命'); ?>
        </div>
        <div class="yfj-panel-body">
            <?php echo esc_html($sizhu['rizhu'] ?? $this->t('暂无数据')); ?>
        </div>
    </div>

    <!-- 4. 先天纳音 & 能量五行 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-buddicons-topics"></span> <?php echo $this->t('五行与纳音分析'); ?>
        </div>
        <div class="yfj-panel-body">
            <p><strong>【<?php echo $this->t('先天纳音'); ?>】</strong><br> <?php echo esc_html($wuxing['detail_desc'] ?? ''); ?> <?php echo esc_html($wuxing['detail_description'] ?? ''); ?></p>
            <div style="border-top: 1px dashed #e2e8f0; margin: 15px 0;"></div>
            <p><strong>【<?php echo $this->t('能量五行'); ?>】</strong><br> <?php echo esc_html($wuxing['simple_description'] ?? ''); ?></p>
        </div>
    </div>

    <!-- 5. 财运分析 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-chart-bar"></span> <?php echo $this->t('财运分析'); ?>
        </div>
        <div class="yfj-panel-body">
            <span class="yfj-badge-red" style="font-size: 16px; margin-right: 8px;"><?php echo esc_html($caiyun['simple_desc'] ?? ''); ?></span>
            <?php echo esc_html($caiyun['detail_desc'] ?? $this->t('暂无数据')); ?>
        </div>
    </div>

    <!-- 6. 姻缘分析 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-heart"></span> <?php echo $this->t('姻缘分析'); ?>
        </div>
        <div class="yfj-panel-body">
            <?php echo esc_html($yinyuan['sanshishu_yinyuan'] ?? $this->t('暂无数据')); ?>
        </div>
    </div>

    <!-- 7. 运程与命运分析 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-star-filled"></span> <?php echo $this->t('运程与命运分析'); ?>
        </div>
        <div class="yfj-panel-body">
            <p><strong>【<?php echo $this->t('大运简批'); ?>】</strong><br> <?php echo esc_html($chenggu['description'] ?? ''); ?></p>
            <div style="border-top: 1px dashed #e2e8f0; margin: 15px 0;"></div>
            <p><strong>【<?php echo $this->t('总体命运'); ?>】</strong><br> <?php echo esc_html($mingyun['sanshishu_mingyun'] ?? ''); ?></p>
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